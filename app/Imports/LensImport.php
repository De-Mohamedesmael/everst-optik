<?php

namespace App\Imports;

use Modules\Lens\Entities\BrandLens;
use Modules\Lens\Entities\Focus;
use Modules\Lens\Entities\IndexLens;
use Modules\Product\Entities\ProductStore;
use Modules\Setting\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Setting\Entities\Color;
use Modules\Setting\Entities\Size;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\Tax;
use App\Utils\ProductUtil;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\Product;

class LensImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected ProductUtil $productUtil;
    protected $request;

    /**
     * Constructor
     *
     * @param ProductUtil $productUtil
     * @param $request
     */
    public function __construct(ProductUtil $productUtil, $request)
    {
        $this->productUtil = $productUtil;
        $this->request = $request;
    }


    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if($row['lens_name'] != null ){
                $color = null;
                $tax = null;

                if (!empty($row['color'])) {
                    $color = Color::where('name', $row['color'])->first();
                    if(!$color){
                        $color =   Color::create([
                            'name'=>  $row['color']
                        ]);
                    }
                }


                $brandIds=[];
                if (!empty($row['brands'])) {
                    $brandNames = explode(',,', $row['brands']);
                    foreach ($brandNames as $name) {
                        $name = trim($name);
                        if (!empty($name)) {
                            $brand = BrandLens::where('name', $name)->first();
                            if (!$brand) {
                                $brand = BrandLens::create([
                                    'name' => $name,
                                    'price'=>0,
                                    'color'=>'#000000',
                                ]);
                            }
                            $brandIds[] = $brand->id;

                        }
                    }
                }



                $focusIds=[];
                if (!empty($row['foci'])) {
                    $focusNames = explode(',,', $row['foci']);
                    foreach ($focusNames as $name) {
                        $name = trim($name);
                        if (!empty($name)) {
                            $focus = Focus::where('name', $name)->first();
                            if (!$brand) {
                                $focus = Focus::create([
                                    'name' => $name,
                                ]);
                            }
                            $focusIds[] = $focus->id;

                        }
                    }
                }


                $indexLensIds=[];
                if (!empty($row['index_lenses'])) {
                    $indexLensNames = explode(',,', $row['index_lenses']);
                    foreach ($indexLensNames as $name) {
                        $name = trim($name);
                        if (!empty($name)) {
                            $indexLens = IndexLens::where('name', $name)->first();
                            if (!$indexLens) {
                                $indexLens = IndexLens::create([
                                    'name' => $name,
                                ]);
                            }
                            $indexLensIds[] = $indexLens->id;

                        }
                    }
                }

                $storeIds=[];
                if (!empty($row['stores'])) {
                    $storeNames = explode(',,', $row['stores']);
                    foreach ($storeNames as $name) {
                        $name = trim($name);
                        if (!empty($name)) {
                            $store = Store::where('name', $name)->first();
                            if (!$store) {
                                $store = Store::create([
                                    'name' => $name,
                                    'created_by' => Auth::user()->id,
                                    'deleted_by' => 0,
                                ]);
                            }
                            $storeIds[] = $store->id;

                        }
                    }
                }


                if (!empty($row['tax'])) {
                    $tax = Tax::where('name', $row['tax'])->first();
                    if(!$tax){
                        $tax =   Brand::create([
                            'name'=>  $row['tax'],
                        ]);
                    }
                }




                $product_data = [
                    'name' => $row['lens_name'],
                    'sku' => $row['sku'] ?? $this->productUtil->generateSku($row['lens_name']),
                    'color_id' => !empty($color) ? $color->id: null,
                    'is_lens' => 1,
                    'is_service' => 0,
                    'active' => 1,
                    'barcode_type' => 'C128',
                    'alert_quantity' => $row['alert_quantity'],
                    'tax_id' => !empty($tax) ? $tax->id : null,
                    'tax_method' => $row['tax_method'],
                    'sell_price' => $row['sell_price'],
                    'purchase_price' =>  $row['purchase_price'],
                    'show_to_customer' => 1,
                    'show_to_customer_types' => [],
                    'different_prices_for_stores' => 0,
                    'this_product_have_variant' => 0,
                    'type' => 'single',
                    'created_by' => Auth::user()->id
                ];

                $product = Product::create($product_data);

                if (!empty($brandIds)){
                    $product->brand_lenses()->attach($brandIds);
                }

                if (!empty($focusIds)){
                    $product->foci()->attach($focusIds);
                }
                if (!empty($indexLensIds)){
                    $product->index_lenses()->attach($indexLensIds);
                }

                if (!empty($storeIds)){
                    foreach ($storeIds as $store_id) {
                        ProductStore::create([
                            'product_id' => $product->id,
                            'store_id' => $store_id,
                            'qty_available' => 0
                        ]);
                    }
                }

                if (!empty($row['image'])) {
                    $product->addMediaFromUrl($row['image'])
                        ->toMediaCollection('product');
                }
            }

        }
    }

    public function rules(): array
    {
        return [
            'lens_name' => 'required',
            'sku' => 'sometimes|unique:products',
        ];
    }
}
