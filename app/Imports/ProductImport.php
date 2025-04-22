<?php

namespace App\Imports;

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

class ProductImport implements ToCollection, WithHeadingRow, WithValidation
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
            if($row['product_name'] != null ){
                $color = null;
                $size = null;
                $brand = null;
                $tax = null;

                if (!empty($row['color'])) {
                    $color = Color::where('name', $row['color'])->first();
                    if(!$color){
                        $color =   Color::create([
                            'name'=>  $row['color']
                        ]);
                    }
                }
                if (!empty($row['size'])) {
                    $size = Size::where('name', $row['size'])->first();
                    if(!$size){
                        $size =   Size::create([
                            'name'=>  $row['size']
                        ]);
                    }
                }


                $categoryIds=[];
                if (!empty($row['categories'])) {
                    $categoryNames = explode(',,', $row['categories']);
                    foreach ($categoryNames as $name) {
                        $name = trim($name);
                        if (!empty($name)) {
                            $category = Category::where('name', $name)->first();
                            if (!$category) {
                                $category = Category::create([
                                    'name' => $name,
                                ]);
                            }
                            $categoryIds[] = $category->id;

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

                if (!empty($row['brand'])) {
                    $brand = Brand::where('name', $row['brand'])->first();
                    if(!$brand){
                        $brand =   Brand::create([
                            'name'=>  $row['brand'],
                        ]);
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
                    'name' => $row['product_name'],
                    'brand_id' => !empty($brand) ? $brand->id : null,
                    'sku' => $row['sku'] ?? $this->productUtil->generateSku($row['product_name']),
                    'color_id' => !empty($color) ? $color->id: null,
                    'size_id' => !empty($size) ? $size->id: null,
                    'is_lens' => !empty($row['is_lens']) ? 1 : 0,
                    'is_service' => 0,
                    'active' => 1,
                    'barcode_type' => 'C128',
                    'alert_quantity' => $row['alert_quantity'],
                    'tax_id' => !empty($tax) ? $tax->id : null,
                    'tax_method' => $row['tax_method'],
                    'show_to_customer' => 1,
                    'show_to_customer_types' => [],
                    'different_prices_for_stores' => 0,
                    'this_product_have_variant' => 0,
                    'type' => 'single',
                    'created_by' => Auth::user()->id
                ];

                $product = Product::create($product_data);

                if (!empty($categoryIds)){
                    $product->categories()->attach($categoryIds);
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
            'product_name' => 'required',
            'sku' => 'sometimes|unique:products',
        ];
    }
}
