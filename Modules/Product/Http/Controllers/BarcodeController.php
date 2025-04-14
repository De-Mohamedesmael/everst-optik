<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Modules\Setting\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Setting\Entities\Color;
use Modules\Setting\Entities\Currency;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerType;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Size;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\System;
use Modules\Setting\Entities\Tax;
use App\Models\Admin;
use Modules\Product\Utils\ProductUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BarcodeController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected Util $commonUtil;
    protected ProductUtil $productUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param ProductUtil $productUtil
     */
    public function __construct(Util $commonUtil, ProductUtil $productUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->productUtil = $productUtil;
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): Factory|View|Application
    {
        $products = Product::orderBy('name', 'asc')->pluck('name', 'id');
        $categories = Category::orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes_array = Tax::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $stores  = Store::getDropdown();
        $admins = Admin::pluck('name', 'id');

        return view('product::back-end.barcode.create')->with(compact(
            'products',
            'categories',
            'brands',
            'colors',
            'sizes',
            'taxes_array',
            'customer_types',
            'stores',
            'admins',
        ));
    }


    /**
     * Returns the html for product row
     *
     * @param Request $request
     * @return Factory|View|Application|string
     */
    public function addProductRow(Request $request): Factory|View|Application|string
    {
        if ($request->ajax()) {
            $product_id = $request->input('product_id');

            if (!empty($product_id)) {
                $index = $request->input('row_count');
                $products = $this->productUtil->getDetailsFromProduct($product_id);

                return view('product::back-end.barcode.partials.show_table_rows')
                    ->with(compact('products', 'index'));
            }
        }
        return '';
    }

    /**
     * print Barcode
     *
     * @param Request $request
     * @return array|string|Translator|Application|null
     */

    public function printBarcode(Request $request): array|string|Translator|Application|null
    {

        try {
            $products = $request->get('products');


            $product_details = [];
            foreach ($products as $value) {
                $details = $this->productUtil->getDetails($value['product_id'],  null, false);
                $product_details[] = ['details' => $details, 'qty' => $this->commonUtil->num_uf($value['quantity'])];
            }

            $page_height = $request->paper_size;


            $print['name'] = !empty($request->product_name) ? 1 : 0;
            $print['price'] = !empty($request->price) ? 1 : 0;
            $print['size'] = !empty($request->size) ? 1 : 0;
            $print['color'] = !empty($request->color) ? 1 : 0;
            $print['site_title'] = !empty($request->site_title) ? System::getProperty('site_title') : null;
            $store = [];
            if (!empty($request->store)) {
                foreach ($request->store as $store_id) {
                    $store[] = !empty($store_id) ? Store::where('id', $store_id)->first()->name . ' ' : null;
                }
            }
            $print['store'] = !empty($store) ? implode(',', $store) : null;
            $print['free_text'] = !empty($request->free_text) ? $request->free_text : null;

            $currency = Currency::find(System::getProperty('currency'));




            if ($request->paper_size === "one") {

                $output = view('product::back-end.barcode.partials.print_barcode1')
                    ->with(compact('print', 'product_details',  'page_height', 'currency'))->render();
            }
            if ($request->paper_size === "two") {

                $output = view('product::back-end.barcode.partials.print_barcode2')
                    ->with(compact('print', 'product_details',  'page_height', 'currency'))->render();
            }
            if ($request->paper_size === "three") {

                $output = view('product::back-end.barcode.partials.print_barcode3')
                    ->with(compact('print', 'product_details',  'page_height', 'currency'))->render();
            }
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = translate('something_went_wrong');
        }

        return $output;
    }
}
