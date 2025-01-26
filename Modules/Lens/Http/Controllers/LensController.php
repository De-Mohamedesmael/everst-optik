<?php

namespace Modules\Lens\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
use Modules\Lens\Entities\BrandLens;
use Modules\Lens\Entities\BrandLensProduct;
use Modules\Lens\Entities\Design;
use Modules\Lens\Entities\Focus;
use Modules\Lens\Entities\FocusProduct;
use Modules\Lens\Entities\IndexLens;
use Modules\Lens\Entities\IndexLensProduct;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductDiscount;
use Modules\Product\Entities\ProductStore;
use Modules\Setting\Entities\Color;
use Modules\Setting\Entities\SpecialBase;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\System;
use Modules\Setting\Entities\Tax;
use Yajra\DataTables\Facades\DataTables;


class LensController extends Controller
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
     * @return Application|Factory|View
     */
    public function getLensStocks(Request $request)
    {
        $brands = BrandLens::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = Customer::getCustomerTreeArray();
        $stores  = Store::getDropdown();
        $admins  = Admin::orderBy('name', 'asc')->pluck('name', 'id');
        $page = 'product_stock';

        return view('lens::back-end.lenses.index')->with(compact(
            'admins',
            'brands',
            'colors',
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
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $process_type = $request->process_type??null;
        if (request()->ajax()) {
            $products = Product::Lens()->leftjoin('add_stock_lines', function ($join) {
                    $join->on('products.id', 'add_stock_lines.product_id');
                })
                ->leftjoin('colors', 'products.color_id', 'colors.id')
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



            if (!empty(request()->color_id)) {
                $products->where('products.color_id', request()->color_id);
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
                'colors.name as color',
                'taxes.name as tax',
                'add_stock_lines.manufacturing_date as manufacturing_date',
                'admins.name as created_by_name',
                'edited.name as edited_by_name',
                DB::raw('(SELECT SUM(product_stores.qty_available) FROM product_stores JOIN products as v ON product_stores.product_id=v.id WHERE v.id=products.id ' . $store_query . ') as current_stock'),
            )->groupBy('products.id');

            //  return $products_;
            return DataTables::of($products)
                ->addColumn('image', function ($row) {
                    $image = $row->getFirstMediaUrl('products');
                    if (!empty($image)) {
                        return '<img src="' . $image . '" height="50px" width="50px">';
                    } else {
                        return '<img src="' . asset('/uploads/' . \Modules\Setting\Entities\System::getProperty('logo')) . '" height="50px" width="50px">';
                    }
                })
                ->editColumn('is_service',function ($row) {
                    return $row->is_service=='1'?'<span class="badge badge-danger">'.Lang::get('lang.is_have_service').'</span>':'';
                })
                ->addColumn('purchase_history', function ($row) {
                    $html = '<a data-href="' .  route('admin.lenses.getPurchaseHistory', $row->id) . '"
                    data-container=".view_modal" class="btn btn-modal">' . __('lang.view') . '</a>';
                    return $html;
                })
                ->editColumn('batch_number', '{{$batch_number}}')
                ->editColumn('sell_price', function ($row) {
                    $price= AddStockLine::where('product_id',$row->product_id)
                    ->whereHas('transaction', function ($query) {
                        $query->where('type', '!=', 'supplier_service');
                    })
                    ->latest()->first();
                    $price= $price? $price->sell_price:$row->sell_price;
                    return number_format($price,2);
                })//, '{{@num_format($default_sell_price)}}')
                ->editColumn('purchase_price', function ($row) {
                    $price= AddStockLine::where('product_id',$row->product_id)
                    ->whereHas('transaction', function ($query) {
                        $query->where('type', '!=', 'supplier_service');
                    })
                    ->latest()->first();
                    $price= $price? ($price->purchase_price > 0 ? $price->purchase_price : $row->purchase_price):$row->purchase_price;

                    return number_format($price,2);
                })
                ->addColumn('tax', '{{$tax}}')
                ->editColumn('color', function ($row){
                    return  $row->color;

                })

                ->editColumn('current_stock', function ($row) {
                    if(!$row->is_service)
                        return $this->productUtil->num_f($row->current_stock ,false,null,true);
                    return 0;
                })
                ->addColumn('current_stock_value', function ($row) {
                    $price= AddStockLine::where('product_id',$row->product_id)
                    ->whereHas('transaction', function ($query) {
                        $query->where('type', '!=', 'supplier_service');
                    })
                    ->latest()->first();
                    $price= $price? ($price->purchase_price > 0 ? $price->purchase_price : $row->default_purchase_price):$row->default_purchase_price;
                    return $this->productUtil->num_f($row->current_stock * $price);
                })
                ->addColumn('customer_type', function ($row) {
                    return $row->customer_type;
                })
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
                        $html = '<input type="checkbox" name="product_selected" disabled class="product_selected" value="' . $row->product_id . '" data-product_id="' . $row->id . '" />';

                    } else {
                        if ($row->current_stock >= 0 ) {
                            $html = '<input type="checkbox" name="product_selected" class="product_selected" value="' . $row->product_id . '" data-product_id="' . $row->id . '" />';
                        } else {
                            $html = '<input type="checkbox" name="product_selected" disabled class="product_selected" value="' . $row->product_id . '" data-product_id="' . $row->id . '" />';
                        }
                    }
                    return $html;

                })->addColumn('selection_checkbox_send', function ($row)  {
                    $html = '<input type="checkbox" name="product_selected_send" class="product_selected_send" value="' . $row->product_id . '" data-product_id="' . $row->id . '" />';

                    return $html;
                })
                ->addColumn('selection_checkbox_delete', function ($row)  {
                    $html = '<input type="checkbox" name="product_selected_delete" class="product_selected_delete" value="' . $row->product_id . '" data-product_id="' . $row->id . '" />';


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

                        if (auth()->user()->can('lens_module.lens.view')) {
                            $html .=
                                '<li><a data-href="' .  route('admin.lenses.show', $row->id) . '"
                                data-container=".view_modal" class="btn btn-modal"><i class="fa fa-eye"></i>
                                ' . __('lang.view') . '</a></li>';
                        }

                        if (auth()->user()->can('lens_module.lens.create_and_edit')) {
                            $html .=
                                '<li><a href="' .  route('admin.lenses.edit', $row->id) . '" class="btn"
                            target="_blank"><i class="dripicons-document-edit"></i> ' . __('lang.edit') . '</a></li>';
                        }
                        if (auth()->user()->can('stock.add_stock.create_and_edit')) {
                            $html .=
                                '<li><a target="_blank" href="' .  route('admin.add-stock.create', ['product_id' => $row->id]) . '" class="btn"
                            target="_blank"><i class="fa fa-plus"></i> ' . __('lang.add_new_stock') . '</a></li>';
                        }
                        if (auth()->user()->can('lens_module.lens.delete')) {

                            $html .=
                                '<li>
                            <a data-href="' . route('admin.lenses.destroy', $row->id) . '"
                                data-check_password="' . route('admin.check-password', Auth::user()->id) . '"
                                class="btn text-red delete_lens"><i class="fa fa-trash"></i>
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
                            return   route('admin.lenses.show', [$row->id]);
                        } else {
                            return '';
                        }
                    }
                ])
                ->rawColumns([
                    'selection_checkbox',
                    'selection_checkbox_send',
                    'selection_checkbox_delete',
                    'categories_names',
                    'sell_price',
                    'purchase_price',
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
        $brands = BrandLens::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::where('type', 'product_tax')
            ->orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = Customer::getCustomerTreeArray();
        $stores  = Store::getDropdown();
        $admins = Admin::pluck('name', 'id');

        return view('lens::back-end.lenses.index')->with(compact(
            'categories',
            'brands',
            'colors',
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
     * @return Application|Factory|View
     */
    public function create()
    {
        if (!auth()->user()->can('lens_module.lens.create_and_edit')) {
            abort(403, translate('Unauthorized action.'));
        }

        $foci = Focus::orderBy('name', 'asc')->pluck('name', 'id');
        $index_lenses = IndexLens::orderBy('name', 'asc')->pluck('name', 'id');
        $brands = BrandLens::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::where('type', 'product_tax')->orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = CustomerType::pluck('name', 'id');
        $stores  = Store::all();
        $stores_select  = Store::getDropdown();
        $quick_add = request()->quick_add;

        if ($quick_add) {
            return view('lens::back-end.lenses.create_quick_add')->with(compact(
                'quick_add',
                'brands',
                'foci',
                'index_lenses',
                'colors',
                'stores_select',
                'taxes',
                'customers',
                'customer_types',
                'discount_customer_types',
                'stores'
            ));
        }

        return view('lens::back-end.lenses.create')->with(compact(
            'brands',
            'colors',
            'foci',
            'index_lenses',
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

        if (!auth()->user()->can('lens_module.lens.create_and_edit')) {
            abort(403, translate('Unauthorized action.'));
        }
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['store_ids' => ['required']],
        );

        DB::beginTransaction();

        try {
            $lens_data = [
                'name' => $request->name,
                'is_lens' => 1,
                'sell_price' => $request->sell_price,
                'purchase_price' => $request->purchase_price,
                'translations' => !empty($request->translations) ? $request->translations : [],
                'sku' => !empty($request->sku) ? $request->sku : $this->productUtil->generateSku($request->name),
                'color_id' => $request->color_id,
                'barcode_type' => $request->barcode_type ?? 'C128',
                'alert_quantity' => $request->alert_quantity,
                'tax_id' => $request->tax_id,
                'tax_method' => $request->tax_method,
                'different_prices_for_stores' => !empty($request->different_prices_for_stores) ? 1 : 0,
                'automatic_consumption' => !empty($request->automatic_consumption) ? 1 : 0,
                'buy_from_supplier' =>  0,
                'active' => !empty($request->active) ? 1 : 0,
                'created_by' => Auth::user()->id,
                'show_at_the_main_pos_page' => 'no',
                'weighing_scale_barcode' => !empty($request->weighing_scale_barcode) ? 1 : 0
            ];


            $lens = Product::create($lens_data);
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($request->cropImages as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $lens->addMedia($filePath)->toMediaCollection('products');
                }
            }





            if ($request->has('brand_id')){
                $lens->brand_lenses()->attach($request->brand_id);
            }
            if ($request->has('focus_id')){
                $lens->foci()->attach($request->focus_id);
            }
            if ($request->has('index_lens_id')){
                $lens->index_lenses()->attach($request->index_lens_id);
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
     * @return Application|Factory|View
     */
    public function show($id)
    {
        if (!auth()->user()->can('lens_module.lens.view')) {
            abort(403, translate('Unauthorized action.'));
        }

        $product = Product::find($id);

        $stock_detials = ProductStore::where('product_id', $id)->get();

        $add_stocks = Transaction::leftjoin('add_stock_lines', 'transactions.id', '=', 'add_stock_lines.transaction_id')
            ->where('transactions.type', '=', 'add_stock')
            ->where('add_stock_lines.product_id', '=', $id)
            ->select(
                'transactions.*',
                DB::raw('SUM(add_stock_lines.quantity - add_stock_lines.quantity_sold) as current_stock')
            )->groupBy('add_stock_lines.id')
            ->get();

        return view('lens::back-end.lenses.show')->with(compact(
            'product',
            'stock_detials',
            'add_stocks',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        if (!auth()->user()->can('lens_module.lens.create_and_edit')) {
            abort(403, translate('Unauthorized action.'));
        }
        $lens = Product::Lens()->with(['index_lenses','foci','brand_lenses'])->findOrFail($id);
        $foci = Focus::orderBy('name', 'asc')->pluck('name', 'id');
        $index_lenses = IndexLens::orderBy('name', 'asc')->pluck('name', 'id');
        $brands = BrandLens::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes = Tax::where('type', 'product_tax')->orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = CustomerType::pluck('name', 'id');
        $stores  = Store::all();
        $stores_select  = Store::getDropdown();
        $quick_add = request()->quick_add;



        $stores_selected=$lens->stores()->pluck( 'store_id')->toarray();
        $index_lenses_selected =$lens->index_lenses()->pluck( 'index_lenses.id')->toarray();
        $brand_lenses_selected =$lens->brand_lenses()->pluck( 'brand_lenses.id')->toarray();
        $foci_selected =$lens->foci()->pluck( 'foci.id')->toarray();
        return view('lens::back-end.lenses.edit')->with(compact(
            'lens',
            'stores_select',
            'quick_add',
            'foci',
            'stores',
            'index_lenses',
            'stores_selected',
            'index_lenses_selected',
            'brand_lenses_selected',
            'foci_selected',
            'brands',
            'colors',
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
        if (!auth()->user()->can('lens_module.lens.create_and_edit')) {
            abort(403, translate('Unauthorized action.'));
        }

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

         try {
             $lens_data = [
                'name' => $request->name,
                'is_lens' => 1,
                'sell_price' => $request->sell_price,
                'purchase_price' => $request->purchase_price,
                'translations' => !empty($request->translations) ? $request->translations : [],
                'sku' => !empty($request->sku) ? $request->sku : $this->productUtil->generateSku($request->name),
                'color_id' => $request->color_id,
                'barcode_type' => $request->barcode_type ?? 'C128',
                'alert_quantity' => $request->alert_quantity,
                'tax_id' => $request->tax_id,
                'tax_method' => $request->tax_method,
                'different_prices_for_stores' => !empty($request->different_prices_for_stores) ? 1 : 0,
                'automatic_consumption' => !empty($request->automatic_consumption) ? 1 : 0,
                'buy_from_supplier' =>  0,
                'active' => !empty($request->active) ? 1 : 0,
                'edited_by' => Auth::user()->id,
                'show_at_the_main_pos_page' => 'no',
                'weighing_scale_barcode' => !empty($request->weighing_scale_barcode) ? 1 : 0
            ];


            DB::beginTransaction();
             $lens = Product::find($id);
             $lens->update($lens_data);

             if ($request->has('store_ids')){
                 $this->productUtil->createOrUpdateProductStore($lens, $request);
             }



            //////////////////////////
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach (getCroppedImages($request->cropImages) as $key=>$imageData) {
                    if($key == 0){
                        $lens->clearMediaCollection('products');
                    }
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                    $lens->addMedia($filePath)->toMediaCollection('products');
                }
            }


             if ($request->has('brand_id')){
                 $lens->brand_lenses()->attach($request->brand_id);
             }
             if ($request->has('focus_id')){
                 $lens->foci()->attach($request->focus_id);
             }
             if ($request->has('index_lens_id')){
                 $lens->index_lenses()->attach($request->index_lens_id);
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
        if (!auth()->user()->can('lens_module.lens.delete')) {
            abort(403, translate('Unauthorized action.'));
        }
        try {
            DB::beginTransaction();

            $product = Product::where('id', $id)->first();
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

    public function getLenses()
    {
        if (request()->ajax()) {

            $term = request()->term;

            if (empty($term)) {
                return json_encode([]);
            }

            $q = Product::where(function ($query) use ($term) {
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
     * @return Factory|View|Application
     */
    public function getPurchaseHistory($id): Factory|View|Application
    {
        $product = Product::find($id);
        $add_stocks = Transaction::leftjoin('add_stock_lines', 'transactions.id', 'add_stock_lines.transaction_id')
            ->where('add_stock_lines.product_id', $id)
            ->groupBy('transactions.id')
            ->select('transactions.*')
            ->get();

        return view('lens::back-end.lenses.partial.purchase_history')->with(compact(
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

        return view('lens::back-end.lenses.import');
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
        $product_sku = Product::where('sku', $sku)->first();

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
        $query = Product::where('name', $request->name);
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

    public function deleteLensImage($id)
    {
        try {
            $product = Product::find($id);
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

        return view('lens::back-end.lenses.partial.raw_discount')->with(compact(
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

    public function multiDeleteRow(Request $request){
        if (!auth()->user()->can('lens_module.lenses.delete')) {
            abort(403, translate('Unauthorized action.'));
        }

        try {
            DB::beginTransaction();
            foreach ($request->ids as $id){
                ProductStore::where('product_id', $id)->delete();
                $product = Product::where('id', $$id)->first();
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



    /**
     * get Dropdown Filter Lenses.
     * @param Request $request
     * @return array{success: true, data: array}
     *

     */
    public function getDropdownFilterLenses(Request $request): array
    {
        $designs =Design::when($request->focus_id , function ($q) use ($request) {
            return $q-> wherehas('foci', function ($q_f) use ($request) {
                        return $q_f->where('foci.id',$request->focus_id);
                    });
        })->pluck('name', 'id');
        $data['designs'] = $this->commonUtil->createDropdownHtml($designs,__('lang.please_select'));


        $lenses=Product::Lens()
            ->when($request->brand_id , function ($q) use ($request) {
                $q-> wherehas('brand_lenses', function ($q_f) use ($request) {
                     $q_f->where('brand_lenses.id',$request->brand_id);
                });
            })
            ->when($request->index_id , function ($q) use ($request) {
                $q-> wherehas('index_lenses', function ($q_f) use ($request) {
                     $q_f->where('index_lenses.id',$request->index_id);
                });
            })
            ->when($request->focus_id , function ($q) use ($request) {
                $q-> wherehas('foci', function ($q_f) use ($request) {
                    $q_f->where('foci.id',$request->focus_id);
                });
            })
            ->when($request->color_id , function ($q) use ($request) {
                 $q-> where('color_id', $request->color_id);
            })
            ->orderBy('name', 'asc')->pluck('name', 'id');
        $data['lenses'] = $this->commonUtil->createDropdownHtml($lenses,__('lang.please_select'));

        return [
            'success' => true,
            'data' => $data
        ];
    }


    /**
     *
     * @param Request $request
     * @return array
     */
    public function getPriceLenses(Request $request): array
    {
       $lens= Product::where('id', $request->lens_id)->Lens()->first();

        if(!$lens){
            return [
                'success' => false,
                'msg' => translate('lens_not_found'),
            ];
        }
        $stockLines = \Modules\AddStock\Entities\AddStockLine::where('sell_price', '>', 0)
            ->where('product_id', $lens->id)
            ->latest()
            ->first();

        $default["sell_price"]= $stockLines ? $stockLines->sell_price : $lens->sell_price;
        $default["purchase_price"] = $stockLines
            ? $stockLines->purchase_price
            : $lens->purchase_price;

        $default['Base_amount']=0;
        if ($request->check_base && $request->special_base) {
            $Base=SpecialBase::whereId($request->special_base)->first();
            if($Base){
                $default['Base_amount']= $Base->price;
            }
        }


        $default['sell_price_format']=num_format($default['sell_price']);
        $default['purchase_price_format']=num_format($default['purchase_price']);
        $default['Base_amount_format']=num_format($default['Base_amount']);
        return [
            'success' => true,
            'data' => $default
        ];
    }
}
