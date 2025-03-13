<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use App\Models\Admin;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Modules\AddStock\Entities\AddStockLine;
use Modules\AddStock\Entities\Transaction;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerType;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductDiscount;
use Modules\Product\Entities\ProductStore;
use Modules\Setting\Entities\Brand;
use Modules\Setting\Entities\Color;
use Modules\Setting\Entities\Size;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\Tax;
use Yajra\DataTables\Facades\DataTables;

//use ExpenseBeneficiary;
//use ExpenseCategory;
class ProductController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected Util $commonUtil;
    protected ProductUtil $productUtil;
    protected TransactionUtil $transactionUtil;

    /**
     * Constructor
     *
     * @param transactionUtil $transactionUtil
     * @param Util $commonUtil
     * @param ProductUtil $productUtil
     * @return void
     */
    public function __construct(Util $commonUtil, ProductUtil $productUtil, TransactionUtil $transactionUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getProductStocks(Request $request)
    {
        $categories = Category::orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = Customer::getCustomerTreeArray();
        $stores  = Store::getDropdown();
        $admins  = Admin::orderBy('name', 'asc')->pluck('name', 'id');
        $page = 'product_stock';

        return view('product::back-end.products.index')->with(compact(
            'admins',
            'categories',
            'brands',
            'colors',
            'sizes',
            'taxes',
            'customers',
            'customer_types',
            'discount_customer_types',
            'stores',
            'page'
        ));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $process_type = $request->process_type??null;
        if (request()->ajax()) {
            $products = product::query();
            if(request()->type == 'lenses'){
                $products=  $products->Lens();
            }elseif(request()->type != 'all'){
                $products=   $products->Product();
            }

            $products= $products->leftjoin('add_stock_lines', function ($join) {
                $join->on('products.id', 'add_stock_lines.product_id');
            })
                ->leftjoin('colors', 'products.color_id', 'colors.id')
                ->leftjoin('sizes', 'products.size_id', 'sizes.id')
                ->leftjoin('brands', 'products.brand_id', 'brands.id')
                ->leftjoin('admins', 'products.created_by', 'admins.id')
                ->leftjoin('admins as edited', 'products.edited_by', 'admins.id')
                ->leftjoin('taxes', 'products.tax_id', 'taxes.id')
                ->leftjoin('product_stores', 'products.id', 'product_stores.product_id');

            $store_id = $this->transactionUtil->getFilterOptionValues($request)['store_id'];

            $store_query = '';
            if (!empty($store_id)) {
                // $products->where('product_stores.store_id', $store_id);
                $store_query = 'AND store_id=' . $store_id;
            }

            if (!empty(request()->product_id)) {
                $products->where('products.id', request()->product_id);
            }
            if (!empty(request()->category_id) && request()->category_id[0] != null) {
                $products->wherehas('categories', function($q){
                    $q->wherein('categories.id', request()->category_id);
                });
            }

            if (!empty(request()->tax_id)) {
                $products->where('tax_id', request()->tax_id);
            }

            if (!empty(request()->brand_id)) {
                $products->where('products.brand_id', request()->brand_id);
            }

            if (!empty(request()->color_id)) {
                $products->where('products.color_id', request()->color_id);
            }

            if (!empty(request()->size_id)) {
                $products->where('products.size_id', request()->size_id);
            }


            if (!empty(request()->customer_type_id)) {
                $products->whereJsonContains('show_to_customer_types', request()->customer_type_id);
            }

            if (!empty(request()->created_by)) {
                $products->where('products.created_by', request()->created_by);
            }
            if (request()->active == '1' || request()->active == '0') {
                $products->where('products.active', request()->active);
            }
            if (request()->show_zero_stocks == '0') {
                $products->where('is_service', 0)->havingRaw('(SELECT SUM(product_stores.qty_available) FROM product_stores JOIN products as v ON product_stores.product_id=v.id WHERE v.id=products.id ' . $store_query . ') > ?', [0]);
            }


            $is_add_stock = request()->is_add_stock;
            $products = $products->select(
                'products.*',
                'add_stock_lines.batch_number',
                'brands.name as brand',
                'colors.name as color',
                'sizes.name as size',
                'taxes.name as tax',
                'add_stock_lines.manufacturing_date as manufacturing_date',
                'admins.name as created_by_name',
                'edited.name as edited_by_name',
                DB::raw('(SELECT SUM(product_stores.qty_available) FROM product_stores JOIN products as v ON product_stores.product_id=v.id WHERE v.id=products.id ' . $store_query . ') as current_stock'),
            )->groupBy('products.id');

            //  return $products_;
            return DataTables::of($products)
                ->addColumn('show_at_the_main_pos_page', function ($row) {
                    if (!empty($row->show_at_the_main_pos_page)&& $row->show_at_the_main_pos_page=="yes"){
                        $checked='checked';
                    }else{
                        $checked='';
                    }
                    return ' <input id="show_at_the_main_pos_page'.$row->id.'" data-id='.$row->id.' name="show_at_the_main_pos_page" type="checkbox"
                    '. $checked .' value="1" class="show_at_the_main_pos_page">';
                })
                ->addColumn('image', function ($row) {
                    $image = $row->getFirstMediaUrl('products');
                    if (!empty($image)) {
                        return '<img src="' . $image . '" height="50px" width="50px">';
                    } else {
                        return '<img src="' . asset('/uploads/' . \Modules\Setting\Entities\System::getProperty('logo')) . '" height="50px" width="50px">';
                    }
                })

                ->addColumn('categories_names', function ($row){
                    $html='';
                    foreach ($row->categories as $key => $category) {
                        $html.= '<span class="category_name">'.( $key > 0 ?' - ':'').$category->name.'</span>';
                    }
                    return $html;
                })
                ->addColumn('purchase_history', function ($row) {
                    $html = '<a data-href="' .  route('admin.products.getPurchaseHistory', $row->id) . '"
                    data-container=".view_modal" class="btn btn-modal">' . __('lang.view') . '</a>';
                    return $html;
                })
                ->editColumn('batch_number', '{{$batch_number}}')
                ->editColumn('sell_price', function ($row) {
                    $price= AddStockLine::where('product_id',$row->id)
                        ->latest()->first();
                    $price= $price? $price->sell_price:$row->sell_price;
                    return number_format($price,2);
                })//, '{{@num_format($default_sell_price)}}')
                ->editColumn('default_purchase_price', function ($row) {
                    $price= AddStockLine::where('product_id',$row->id)
                        ->latest()->first();
                    $price= $price? ($price->purchase_price > 0 ? $price->purchase_price : $row->default_purchase_price):$row->default_purchase_price;

                    return number_format($price,2);
                })
                ->addColumn('tax', '{{$tax}}')
                ->editColumn('brand', '{{$brand}}')
                ->editColumn('color', function ($row){
                    return  $row->color;

                })
                ->editColumn('size', function ($row){
                    return $row->size;
                })
                ->editColumn('current_stock', function ($row) {
                    if(!$row->is_service)
                        return $this->productUtil->num_f($row->current_stock ,false,null,true);
                    return 0;
                })
                ->addColumn('current_stock_value', function ($row) {
                    $price= AddStockLine::where('product_id',$row->id)

                        ->latest()->first();
                    $price= $price? ($price->purchase_price > 0 ? $price->purchase_price : $row->default_purchase_price):$row->default_purchase_price;
                    return $this->productUtil->num_f($row->current_stock * $price);
                })
                ->addColumn('customer_type', function ($row) {
                    return $row->customer_type;
                })
                ->editColumn('exp_date', '@if(!empty($exp_date)){{@format_date($exp_date)}}@endif')
                ->addColumn('manufacturing_date', '@if(!empty($manufacturing_date)){{@format_date($manufacturing_date)}}@endif')
                ->editColumn('discount',function ($row) {
                    $discount_text='';
                    $discounts= ProductDiscount::where('product_id',$row->id)->get();
                    foreach ($discounts as $k=>$discount){
                        if($k != 0){
                            $discount_text.=' - ';
                        }
                        $discount_text.= $discount->discount;
                    }
                    return $discount_text;
                })
                ->editColumn('active', function ($row) {
                    if ($row->active) {
                        return __('lang.yes');
                    } else {
                        return __('lang.no');
                    }
                })
                ->editColumn('created_by', '{{$created_by_name}}')
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('updated_at', '{{@format_datetime($updated_at)}}')
                ->addColumn('selection_checkbox', function ($row) use ($is_add_stock) {
                    if ($is_add_stock == 1 && $row->is_service == 1) {
                        $html = '<input type="checkbox" name="product_selected" disabled class="product_selected" value="' . $row->id . '" data-product_id="' . $row->id . '" />';

                    } else {
                        if ($row->current_stock >= 0 ) {
                            $html = '<input type="checkbox" name="product_selected" class="product_selected" value="' . $row->id . '" data-product_id="' . $row->id . '" />';
                        } else {
                            $html = '<input type="checkbox" name="product_selected" disabled class="product_selected" value="' . $row->id . '" data-product_id="' . $row->id . '" />';
                        }
                    }
                    return $html;

                })->addColumn('selection_checkbox_send', function ($row)  {
                    $html = '<input type="checkbox" name="product_selected_send" class="product_selected_send" value="' . $row->id . '" data-product_id="' . $row->id . '" />';
                    return $html;
                })
                ->addColumn('selection_checkbox_delete', function ($row)  {
                    $html = '<input type="checkbox" name="product_selected_delete" class="product_selected_delete" value="' . $row->id . '" data-product_id="' . $row->id . '" />';
                    return $html;
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html =
                            '<div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">' . __('lang.action') .
                            '<span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                        if (auth()->user()->can('product_module.products.view')) {
                            $html .=
                                '<li><a data-href="' .  route('admin.products.show', $row->id) . '"
                                data-container=".view_modal" class="btn btn-modal"><i class="fa fa-eye"></i>
                                ' . __('lang.view') . '</a></li>';
                        }

                        if (auth()->user()->can('product_module.products.create_and_edit')) {
                            $html .=
                                '<li><a href="' .  route('admin.products.edit', $row->id) . '" class="btn"
                            target="_blank"><i class="dripicons-document-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }
                        if (auth()->user()->can('stock.add_stock.create_and_edit')) {
                            $html .=
                                '<li><a target="_blank" href="' . route('admin.add-stock.create', ['product_id' => $row->id]) . '" class="btn"
                            target="_blank"><i class="fa fa-plus"></i> ' . __('lang.add_new_stock') . '</a></li>';
                        }
                        if (auth()->user()->can('product_module.products.delete')) {

                            $html .=
                                '<li>
                            <a data-href="' . route('admin.products.destroy', $row->id) . '"
                                data-check_password="' . route('admin.check-password', Auth::user()->id) . '"
                                class="btn text-red delete_product"><i class="fa fa-trash"></i>
                                ' . __('lang.delete') . '</a>
                        </li>';
                        }

                        $html .= '</ul></div>';

                        return $html;
                    }
                )

                ->setRowAttr([
                    'data-href' => function ($row) {
                        if (auth()->user()->can("products.view")) {
                            return   route('admin.products.show', [$row->id]);
                        } else {
                            return '';
                        }
                    }
                ])
                ->rawColumns([
                    'show_at_the_main_pos_page',
                    'selection_checkbox',
                    'selection_checkbox_send',
                    'selection_checkbox_delete',
                    'categories_names',
                    'sell_price',
                    'default_purchase_price',
                    'image',
                    'sku',
                    'purchase_history',
                    'batch_number',
                    'sell_price',
                    'tax',
                    'brand',
                    'color',
                    'size',
                    'customer_type',
                    'manufacturing_date',
                    'discount',
                    'purchase_price',
                    'created_by',
                    'action',
                ])
                ->make(true);
        }
        $categories = Category::orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::where('type', 'product_tax')
            ->orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = Customer::getCustomerTreeArray();
        $stores  = Store::getDropdown();
        $admins = Admin::pluck('name', 'id');

        return view('product::back-end.products.index')->with(compact(
            'categories',
            'brands',
            'colors',
            'sizes',
            'taxes',
            'customers',
            'customer_types',
            'discount_customer_types',
            'admins',
            'stores'
        ));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        if (!auth()->user()->can('product_module.products.create_and_edit')) {
            abort(403, translate('Unauthorized action.'));
        }

        $categories = Category::orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::where('type', 'product_tax')->orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = CustomerType::pluck('name', 'id');
        $stores  = Store::all();
        $stores_select  = Store::getDropdown();
        $quick_add = request()->quick_add;

        if ($quick_add) {
            return view('product::back-end.products.create_quick_add')->with(compact(
                'quick_add',
                'categories',
                'brands',
                'colors',
                'sizes',
                'stores_select',
                'taxes',
                'customers',
                'customer_types',
                'discount_customer_types',
                'stores'
            ));
        }

        return view('product::back-end.products.create')->with(compact(
            'categories',
            'brands',
            'colors',
            'sizes',
            'taxes',
            'stores_select',
            'customers',
            'customer_types',
            'discount_customer_types',
            'stores'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {

        if (!auth()->user()->can('product_module.products.create_and_edit')) {
            abort(403, translate('Unauthorized action.'));
        }
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['store_ids' => ['required']],
        );
        DB::beginTransaction();

        try {
            $product_data = [
                'name' => $request->name,
                'translations' => !empty($request->translations) ? $request->translations : [],
                'brand_id' => $request->brand_id,
                'sku' => !empty($request->sku) ? $request->sku : $this->productUtil->generateSku($request->name),
                'color_id' => $request->color_id,
                'size_id' => $request->size_id,
                'is_service' => !empty($request->is_service) ? 1 : 0,
                'barcode_type' => $request->barcode_type ?? 'C128',
                'alert_quantity' => $request->alert_quantity,
                'tax_id' => $request->tax_id,
                'tax_method' => $request->tax_method,
                'show_to_customer' => !empty($request->show_to_customer) ? 1 : 0,
                'show_to_customer_types' => $request->show_to_customer_types,
                'different_prices_for_stores' => !empty($request->different_prices_for_stores) ? 1 : 0,
                'automatic_consumption' => !empty($request->automatic_consumption) ? 1 : 0,
                'buy_from_supplier' =>  0,
                'active' => !empty($request->active) ? 1 : 0,
                'created_by' => Auth::user()->id,
                'show_at_the_main_pos_page' => !empty($request->show_at_the_main_pos_page) ? 'yes' : 'no',
                'weighing_scale_barcode' => !empty($request->weighing_scale_barcode) ? 1 : 0
            ];


            $product = product::create($product_data);
            $index_discounts=[];
            if($request->has('discount_type')){
                if(count($request->discount_type)>0){
                    $index_discounts=array_keys($request->discount_type);
                }
            }


                foreach ($index_discounts as $index_discount){
                    $discount_customers = $this->getDiscountCustomerFromType($request->get('discount_customer_types_'.$index_discount));
                    $data_des=[
                        'product_id' => $product->id,
                        'discount_type' => $request->discount_type[$index_discount],
                        'discount_category' => $request->discount_category[$index_discount],
                        'is_discount_permenant'=>!empty($request->is_discount_permenant[$index_discount])? 1 : 0,
                        'discount_customer_types' => $request->get('discount_customer_types_'.$index_discount),
                        'discount_customers' => $discount_customers,
                        'discount' => $this->commonUtil->num_uf($request->discount[$index_discount]),
                        'discount_start_date' => !empty($request->discount_start_date[$index_discount]) ? $this->commonUtil->uf_date($request->discount_start_date[$index_discount]) : null,
                        'discount_end_date' => !empty($request->discount_end_date[$index_discount]) ? $this->commonUtil->uf_date($request->discount_end_date[$index_discount]) : null
                    ];

                    ProductDiscount::create($data_des);
                }

            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($request->cropImages as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $product->addMedia($filePath)->toMediaCollection('products');
                }
            }





            if ($request->has('category_id')){
                $product->categories()->attach($request->category_id);
            }
            if ($request->store_ids){
                foreach ($request->store_ids as $store_id) {
                    ProductStore::create([
                        'product_id' => $product->id,
                        'store_id' => $store_id,
                        'qty_available' => 0
                    ]);
                }
            }


            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }

    public function getDiscountCustomerFromType($customer_types)
    {

        $discount_customers = [];
        if (!empty($customer_types)) {
            $customers = Customer::whereIn('customer_type_id', $customer_types)->get();
            foreach ($customers as $customer) {
                $discount_customers[] = $customer->id;
            }
        }

        return $discount_customers;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        if (!auth()->user()->can('product_module.products.view')) {
            abort(403, translate('Unauthorized action.'));
        }

        $product = product::Product()->find($id);

        $stock_detials = ProductStore::where('product_id', $id)->get();

        $add_stocks = Transaction::leftjoin('add_stock_lines', 'transactions.id', '=', 'add_stock_lines.transaction_id')
            ->where('transactions.type', '=', 'add_stock')
            ->where('add_stock_lines.product_id', '=', $id)
            ->select(
                'transactions.*',
                DB::raw('SUM(add_stock_lines.quantity - add_stock_lines.quantity_sold) as current_stock')
            )->groupBy('add_stock_lines.id')
            ->get();

        return view('product::back-end.products.show')->with(compact(
            'product',
            'stock_detials',
            'add_stocks',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        if (!auth()->user()->can('product_module.products.create_and_edit')) {
            abort(403, translate('Unauthorized action.'));
        }
        $product = product::Product()->findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::where('type', 'product_tax')->orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = CustomerType::pluck('name', 'id');
        $stores_select  = Store::getDropdown();

        $stores_selected=$product->stores()->pluck( 'store_id')->toarray();
        $category_id_selected =$product->categories()->pluck( 'categories.id')->toarray();
        return view('product::back-end.products.edit')->with(compact(
            'product',
            'categories',
            'stores_select',
            'stores_selected',
            'category_id_selected',
            'brands',
            'colors',
            'sizes',
            'taxes',
            'customers',
            'customer_types',
            'discount_customer_types'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('product_module.products.create_and_edit')) {
            abort(403, translate('Unauthorized action.'));
        }

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

         try {
            $product_data = [
                'name' => $request->name,
                'translations' => !empty($request->translations) ? $request->translations : [],
                'brand_id' => $request->brand_id,
                'sku' => $request->sku,
                'color_id' => $request->color_id,
                'size_id' => $request->size_id,
                'is_service' => !empty($request->is_service) ? 1 : 0,
                'product_details' => $request->product_details,
                'barcode_type' => $request->barcode_type ?? 'C128',
                'alert_quantity' => $request->alert_quantity,
                'tax_id' => $request->tax_id,
                'tax_method' => $request->tax_method,
                'show_to_customer' => !empty($request->show_to_customer) ? 1 : 0,
                'show_to_customer_types' => $request->show_to_customer_types,
                'different_prices_for_stores' => !empty($request->different_prices_for_stores) ? 1 : 0,
                'automatic_consumption' => !empty($request->automatic_consumption) ? 1 : 0,
                'buy_from_supplier' =>  0,
                'active' => !empty($request->active) ? 1 : 0,
                'edited_by' => Auth::user()->id,
                'show_at_the_main_pos_page' => !empty($request->show_at_the_main_pos_page) ? 'yes' : 'no',
                'weighing_scale_barcode' => !empty($request->weighing_scale_barcode) ? 1 : 0,
            ];


            DB::beginTransaction();
            $product = product::find($id);
            $product->update($product_data);


            ProductDiscount::where('product_id',$product->id)->delete();
            $index_discounts=[];
            if($request->has('discount_type')){
                if(count($request->discount_type)>0){
                    $index_discounts=array_keys($request->discount_type);
                }
            }
             if ($request->has('store_ids')){
                 $this->productUtil->createOrUpdateProductStore($product, $request);
             }

             foreach ($index_discounts as $index_discount){
                    $discount_customers = $this->getDiscountCustomerFromType($request->get('discount_customer_types_'.$index_discount));
                    $data_des=[
                        'product_id' => $product->id,
                        'discount_type' => $request->discount_type[$index_discount],
                        'discount_category' => $request->discount_category[$index_discount],
                        'is_discount_permenant'=>!empty($request->is_discount_permenant[$index_discount])? 1 : 0,
                        'discount_customer_types' => $request->get('discount_customer_types_'.$index_discount),
                        'discount_customers' => $discount_customers,
                        'discount' => $this->commonUtil->num_uf($request->discount[$index_discount]),
                        'discount_start_date' => !empty($request->discount_start_date[$index_discount]) ? $this->commonUtil->uf_date($request->discount_start_date[$index_discount]) : null,
                        'discount_end_date' => !empty($request->discount_end_date[$index_discount]) ? $this->commonUtil->uf_date($request->discount_end_date[$index_discount]) : null
                    ];

                    ProductDiscount::create($data_des);
                }
            //////////////////////////
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                // Clear the media collection only once, before the loop

                foreach (getCroppedImages($request->cropImages) as $key=>$imageData) {
                    if($key == 0){
                        $product->clearMediaCollection('products');
                    }
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $product->addMedia($filePath)->toMediaCollection('products');
                }
            }


             if ($request->has('category_id')) {
                 $product->categories()->sync($request->category_id);
             }


             if ($request->store_ids){
                 foreach ($request->store_ids as $store_id) {
                     ProductStore::firstOrCreate(
                         [
                             'product_id' => $product->id,
                             'store_id' => $store_id
                         ],
                         [
                             'qty_available' => 0
                         ]
                     );
                 }
             }

            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
         } catch (\Exception $e) {
             DB::rollback();
             Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
             $output = [
                 'success' => false,
                 'msg' => __('lang.something_went_wrong')
             ];
         }

        if ($request->ajax()) {
            return $output;
        } else {
            return redirect()->back()->with('status', $output);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('product_module.products.delete')) {
            abort(403, translate('Unauthorized action.'));
        }
        try {
            DB::beginTransaction();

            $product = product::where('id', $id)->first();
            ProductStore::where('product_id', $id)->delete();
            $product->deleted_by= request()->user()->id;
            $product->save();
            $product->clearMediaCollection('products');
            $product->delete();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
            DB::commit();
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }

    public function getProducts()
    {
        if (request()->ajax()) {

            $term = request()->term;

            if (empty($term)) {
                return json_encode([]);
            }

            $q = product::Product()->where(function ($query) use ($term) {
                    $query->where('products.name', 'like', '%' . $term . '%');
                    $query->orWhere('sku', 'like', '%' . $term . '%');
                    $query->orWhere('sub_sku', 'like', '%' . $term . '%');
                })
                ->whereNull('deleted_at')
                ->select(
                    'products.id as product_id',
                    'products.name',
                     'products.sku as sku',
                );

            if (!empty(request()->store_id)) {
                $q->ForLocation(request()->store_id);
            }
            $products = $q->get();

            $products_array = [];
            foreach ($products as $product) {
                $products_array[$product->product_id]['name'] = $product->name;
                $products_array[$product->product_id]['sku'] = $product->sub_sku;
                $products_array[$product->product_id]['type'] = $product->type;
            }

            $result = [];
            $i = 1;
            $no_of_records = $products->count();
            if (!empty($products_array)) {
                foreach ($products_array as $key => $value) {
                    if ($no_of_records > 1 && $value['type'] != 'single') {
                        $result[] = [
                            'id' => $i,
                            'text' => $value['name'] . ' - ' . $value['sku'],
                            'product_id' => $key
                        ];
                    }
                    $i++;
                }
            }

            return json_encode($result);
        }
    }

    /**
     * get the list of porduct purchases
     *
     * @param [type] $id
     * @return void
     */
    public function getPurchaseHistory($id)
    {
        $product = product::find($id);
        $add_stocks = Transaction::leftjoin('add_stock_lines', 'transactions.id', 'add_stock_lines.transaction_id')
            ->where('add_stock_lines.product_id', $id)
            ->groupBy('transactions.id')
            ->select('transactions.*')
            ->get();

        return view('product::back-end.products.partial.purchase_history')->with(compact(
            'product',
            'add_stocks',
        ));
    }

    /**
     * get import page
     *
     */
    public function getImport()
    {

        return view('product::back-end.products.import');
    }

    /**
     * save import resource to stores
     *
     */
    public function saveImport(Request $request)
    {



        $this->validate($request, [
            'file' => 'required|mimes:csv,txt,xlsx'
        ]);
        try {
            DB::beginTransaction();
            Excel::import(new ProductImport($this->productUtil, $request), $request->file);
            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
/*            $failures = $e->failures();
            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
              return  $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }*/
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong') .' , '. __('lang.import_req')
            ];
        }

        return redirect()->back()->with('status', $output);
    }

    /**
     * check sku if already in use
     *
     * @param string $sku
     * @return array
     */
    public function checkSku($sku)
    {
        $product_sku = product::Product()->where('sku', $sku)->first();

        if (!empty($product_sku)) {
            $output = [
                'success' => false,
                'msg' => __('lang.sku_already_in_use')
            ];
        } else {
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        }

        return $output;
    }

    /**
     * check name if already in use
     *
     * @param string $name
     * @return array
     */
    public function checkName(Request $request)
    {
        $query = product::Product()->where('name', $request->name);
        $product_name = $query->first();

        if (!empty($product_name)) {
            $output = [
                'success' => false,
                'msg' => __('lang.name_already_in_use')
            ];
        } else {
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        }

        return $output;
    }

    public function deleteProductImage($id)
    {
        try {
            $product = product::find($id);
            $product->clearMediaCollection('products');

            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }


 /**
     * get raw material row
     *
     * @return void
     */
    public function getRawDiscount()
    {
        $row_id = request()->row_id ?? 0;
        $discount_customer_types = CustomerType::pluck('name', 'id');

        return view('product::back-end.products.partial.raw_discount')->with(compact(
            'row_id',
            'discount_customer_types',
        ));
    }



    public function updateColumnVisibility(Request $request)
    {
        $columnVisibility = $request->input('columnVisibility');
        Cache::forever('key_' . auth()->id(), $columnVisibility);
        return response()->json(['success' => true]);
    }
    public function toggleAppearancePos($id,Request $request){
        $products_count=product::where('show_at_the_main_pos_page','yes')->count();
        if(isset($products_count) && $products_count <40){
            $product=product::find($id);
            if($product->show_at_the_main_pos_page=='no'){
                $product->show_at_the_main_pos_page='yes';
                $product->save();
            }else{
                $product->show_at_the_main_pos_page='no';
                $product->save();
            }
        }else{
            $product=product::find($id);
                if($product->show_at_the_main_pos_page=='yes'){
                    $product->show_at_the_main_pos_page='no';
                    $product->save();
                }else{
                    if($request->check=="yes"){
                        return [
                            'success' => 'Failed!',
                            'msg' => __("lang.Cant_Add_More_Than_40_Products"),
                            'status'=>'error'
                        ];
                    }
                }
        }
    }
    public function multiDeleteRow(Request $request){
        if (!auth()->user()->can('product_module.products.delete')) {
            abort(403, translate('Unauthorized action.'));
        }

        try {
            DB::beginTransaction();
            foreach ($request->ids as $id){
                ProductStore::where('product_id', $id)->delete();
                $product = product::where('id', $$id)->first();
                $product->clearMediaCollection('products');
                $product->delete();

            }
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];

            DB::commit();
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }
}
