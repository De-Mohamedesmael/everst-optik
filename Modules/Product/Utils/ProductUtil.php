<?php

namespace Modules\Product\Utils;

use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\AddStock\Entities\AddStockLine;
use Modules\AddStock\Entities\Transaction;
use Modules\Customer\Entities\Customer;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductDiscount;
use Modules\Product\Entities\ProductStore;
use Modules\Setting\Entities\Store;
use Modules\Sale\Entities\SalesPromotion;

//use ConsumptionProduct;
//use EarningOfPoint;
//use PurchaseOrderLine;
//use PurchaseReturnLine;
//use RedemptionOfPoint;
//use RemoveStockLine;
//use TransferLine;

class ProductUtil extends Util
{

    /**
     * Generates products sku
     *
     * @param string $string
     *
     * @return generated sku (string)
     */
    public function generateProductSku($string)
    {
        $sku_prefix = '';

        return $sku_prefix . str_pad($string, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generated SKU based on the barcode type.
     *
     * @param string $sku
     * @param string $c
     * @param string $barcode_type
     *
     * @return void
     */
    public function generateSubSku($sku, $c, $barcode_type = 'C128')
    {
        $sub_sku = $sku . $c;

        if (in_array($barcode_type, ['C128', 'C39'])) {
            $sub_sku = $sku . $c;
            // $sub_sku = $sku . '-' . $c;
        }

        return $sub_sku;
    }

    /**
     * Generated unique ref numbers
     *
     * @param string $type
     *
     * @return void
     */
    public function getNumberByType($type, $store_id = null, $i = 1)
    {
        $number = '';
        $store_string = '';
        $day = Carbon::now()->day;
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        if (!empty($store_id)) {
            $store_string = $this->getStoreNameFirstLetters($store_id);
        }
        if ($type == 'purchase_order') {
            $po_count = Transaction::where('type', $type)->count() + $i;

            $number = 'PO' . $store_string . $po_count;
        }
        if ($type == 'sell') {
            $inv_count = Transaction::where('type', $type)->count() + $i;

            $number = 'Inv' . $year . $month . $inv_count;
        }
        if ($type == 'expense') {
            $inv_count = Transaction::where('type', $type)->count() + $i;

            $number = 'Exp' . $year . $month . $inv_count;
        }
        if ($type == 'sell_return') {
            $count = Transaction::where('type', $type)->whereMonth('transaction_date', $month)->count() + $i;

            $number = 'Rets' . $year . $month . $count;
        }
        if ($type == 'purchase_return') {
            $count = Transaction::where('type', $type)->whereMonth('transaction_date', $month)->count() + $i;

            $number = 'RetP' . $year . $month . $count;
        }
        if ($type == 'remove_stock') {
            $count = Transaction::where('type', $type)->whereMonth('transaction_date', $month)->count() + $i;

            $number = 'RST' . $year . $month . $count;
        }
        if ($type == 'transfer') {
            $count = Transaction::where('type', $type)->whereMonth('transaction_date', $month)->count() + $i;

            $number = 'tras' . $year . $month . $count;
        }
        if ($type == 'quotation') {
            $count = Transaction::where('type', 'sell')->where('is_quotation', 1)->whereMonth('transaction_date', $month)->count() + $i;

            $number = 'Qu' . $year . $month . $count;
        }

        $number_exists = Transaction::where('type', $type)->where('invoice_no', $number)->exists();
        if ($number_exists) {
            return $this->getNumberByType($type, $store_id, $i + 1);
        }

        if ($type == 'internal_stock_request') {
            $count = Transaction::where('type', 'transfer')->where('is_internal_stock_transfer', 1)->distinct('invoice_no')->count() + $i;

            $number = 'ISRQ' . $year . $month . $day .  $count;
        }
        if ($type == 'internal_stock_return') {
            $count = Transaction::where('type', 'internal_stock_return')->where('is_internal_stock_transfer', 1)->where('is_return', 1)->distinct('invoice_no')->count() + $i;

            $number = 'ISRT' . $year . $month . $day .  $count;
        }

        if ($type == 'earning_of_point') {
            $count = EarningOfPoint::count() + $i;

            $number = 'LPE' . $year . $month . $day .  $count;
        }
        if ($type == 'redemption_of_point') {
            $count = RedemptionOfPoint::count() + $i;

            $number = 'LPR' . $year . $month . $day .  $count;
        }

        return $number;
    }

    public function generateSku($name, $number = 1)
    {
        $name_array = explode(" ", $name);
        $sku = '';
        foreach ($name_array as $w) {
            if (!empty($w)) {
                if (!preg_match('/[^A-Za-z0-9]/', $w)) {
                    $sku .= $w[0];
                }
            }
        }
        // $sku = $sku . '-' . $number;
        $sku = $sku . $number;
        $sku_exist = Product::where('sku', $sku)->exists();

        if ($sku_exist) {
            return $this->generateSku($name, $number + 1);
        } else {
            return $sku;
        }
    }
    public function getStoreNameFirstLetters($store_id)
    {
        $string = '';
        $store = Store::find($store_id);

        if (!empty($store)) {
            $name = explode(" ", $store->name);

            foreach ($name as $w) {
                if (!preg_match('/[^A-Za-z0-9]/', $w)) {
                    $string .= $w[0];
                }
            }
        }

        return $string;
    }

    //create or update products stores data
    public function createOrUpdateProductStore($product, $request)
    {
        $stores = Store::all();
        $product_stores = $request->product_stores;
        if($request->has('store_ids')){
            $stores = Store::wherein('id',$request->store_ids)->get();
        }
            foreach ($stores as $store) {
                ProductStore::updateOrcreate(
                    [
                        'product_id' => $product->id,
                        'store_id' => $store->id
                    ],
                    [
                        'product_id' => $product->id,
                        'store_id' => $store->id,
                        'qty' => 0,
                        'price' => !empty($product_stores[$store->id]['price']) ? $product_stores[$store->id]['price'] : $product->sell_price
                    ]
                );
            }

    }



    public function treeConfigString($color)
    {
        $config = 'icon: "fa fa-angle-right",
            selectedIcon: "fa fa-angle-down",
            expandIcon: "fa fa-angle-down",
            color: "' . $color . '",
            showBorder: false,';

        return $config;
    }

    /**
     * Gives list of products_ based on products_ id and variation id
     *
     * @param int $product_id
     * @param int $variation_id = null
     * @param int $store_id = null
     *
     * @return Obj
     */
    public function getDetailsFromProduct($product_id, $store_id = null)
    {
        $product = Product::leftjoin('product_stores', 'products.id', '=', 'product_stores.product_id')
            ->whereNull('products.deleted_at');


        if (!is_null($store_id) && $store_id !== '0') {
            $product->where('product_stores.store_id', $store_id);
        }
        $product->where('products.id', $product_id)->groupBy('products.id');

        $products = $product->select(
            'products.*',
            'products.id as product_id',
            'products.name as product_name',
            'product_stores.qty_available',
        )
            ->get();

        return $products;
    }
    public function getMultipleDetailsFromProduct($product_selected=null, $store_id = null)
    {
        $products=[];
        foreach($product_selected as $p_selected){
            $query = Product::leftjoin('product_stores', 'products.id', '=', 'product_stores.product_id')
            ->whereNull('products.deleted_at');

            if (!is_null($store_id) && $store_id !== '0') {
                $query->where('product_stores.store_id', $store_id);
            }
            $query->where('products.id', $p_selected['product_id'])->groupBy('products.id');

            $product = $query->select(
                'products.*',
                'products.id as product_id',
                'products.name as product_name',
                'product_stores.qty_available',
                'products.purchase_price',
                'products.sell_price',
                'products.sku',
                DB::raw("'{$p_selected['qty']}' as qty")
            )
            ->first();
            // $products = $products->addSelect();
            $products[]=$product;
        }


        return $products;
    }
    /**
     * Gives list of products_ based on products_ id and variation id
     *
     * @param int $sender_store_id
     * @param int $product_id
     * @param int $variation_id = null
     *
     * @return object
     */
    public function getDetailsFromProductTransfer($sender_store_id, $product_id, $variation_id = null)
    {
        $product = Product::leftjoin('variations as v', 'products.id', '=', 'v.product_id')
            ->leftjoin('product_stores', 'v.id', '=', 'product_stores.variation_id')
            ->whereNull('v.deleted_at');

        if (!is_null($sender_store_id) && $sender_store_id !== '0') {
            $product->where('product_stores.store_id', $sender_store_id);
        }

        if (!is_null($variation_id) && $variation_id !== '0') {
            $product->where('v.id', $variation_id);
        }

        $product->where('products.id', $product_id);

        $products = $product->select(
            'products.id as product_id',
            'products.name as product_name',
            'product_stores.qty_available',
            'v.id as variation_id',
            'v.name as variation_name',
            'v.default_purchase_price',
            'v.default_sell_price',
            'v.sub_sku'
        )
            ->get();

        return $products;
    }

    /**
     * Gives list of products_ based on products_ id and variation id
     *
     * @param int $product_id
     * @param int $variation_id = null
     * @param int $store_id = null
     *
     * @return Obj
     */
    public function getDetailsFromProductByStore($product_id, $variation_id = null, $store_id = null,$batch_number_id=null)
    {
        $product = Product::leftjoin('taxes', 'products.tax_id', '=', 'taxes.id')
        ->leftjoin('product_stores', 'products.id', '=', 'product_stores.product_id');
        if (!is_null($batch_number_id) && $batch_number_id !== '0') {
            $product->leftjoin('add_stock_lines', 'products.id', '=', 'add_stock_lines.product_id');
            $product->where('add_stock_lines.id', $batch_number_id);
        }
        $product->whereNull('products.deleted_at');

        if (!is_null($store_id) && $store_id !== '0') {
            $product->where('product_stores.store_id', $store_id);
        }

        $product->where('products.id', $product_id);
        $selectRaws=['products.id as product_id',
        'products.name as product_name',
        'products.is_lens',
        'products.alert_quantity',
        'products.tax_id',
        'products.tax_method',
        'product_stores.qty_available',
        'products.sell_price',
        'taxes.rate as tax_rate',
        'products.purchase_price',
        'products.sell_price',
        'products.sku'];
        if (!is_null($batch_number_id) && $batch_number_id !== '0') {
            array_push($selectRaws,'add_stock_lines.batch_number');
            array_push($selectRaws,'add_stock_lines.id as stock_id');
        }
        return $product->select($selectRaws)->first();
}

    /**
     * get the products discount details for products if exist
     *
     * @param int $product_id
     * @param int $customer_id
     * @return mix
     */
    public function getProductDiscountDetails($product_id, $customer_id)
    {
        $customer = Customer::find($customer_id);
        $customer_type_id = null;
        if (!empty($customer)) {
            $customer_type_id = (string) $customer->customer_type_id;
        }
        if (!empty($customer_type_id)) {

            $product = Product::where('id', $product_id)
                ->first();
            if(!$product){
                $product = ProductDiscount::where('product_id', $product_id)
                    ->select(
                        'id',
                        'discount_type',
                        'discount',
                        'discount_category',
                        'discount_start_date',
                        'discount_end_date',
                    )
                    ->first();
            }

            if (!empty($product)) {
                if (!empty($product->discount_start_date) && !empty($product->discount_end_date)) {
                    //if end date set then check for expiry
                    if (($product->discount_start_date <= date('Y-m-d') && $product->discount_end_date >= date('Y-m-d'))||$product->is_discount_permenant=='1') {
                        return $product;
                    } else {
                        return false;
                    }
                }
                return $product;
            }
        }
        return null;
    }

    public function getProductAllDiscountCategories($product_id)
    {
            // $products = Product::where('id', $product_id)
            //     ->where('discount', '>',0)
            //     ->select(
            //         'products.discount_type',
            //         'products.discount',
            //         'products.discount_start_date',
            //         'products.discount_end_date',
            //     )
            //     ->first();
            // if(!$products){
                $product = ProductDiscount::where('product_id', $product_id)
                ->where(function($query){
                    $query->where('discount_start_date','<=',date('Y-m-d'));
                    $query->where('discount_end_date','>=',date('Y-m-d'));
                    $query->orWhere('is_discount_permenant',"1");
                }) ->select(
                        'id',
                        'discount_type',
                        'discount',
                        'discount_category',
                        'discount_start_date',
                        'discount_end_date',
                    )
                    ->get();
            // }

            if (!empty($product)) {
                // if (!empty($products->discount_start_date) && !empty($products->discount_end_date)) {
                    //if end date set then check for expiry
                    // if (($products->discount_start_date <= date('Y-m-d') && $products->discount_end_date >= date('Y-m-d')) ) {
                        // return $products;
                    // } else {
                        // return false;
                    // }
                // }
                return $product;
            }
        // }
        return null;
    }
    /**
     * get the sales promotion details for products if exist
     *
     * @param int $product_id
     * @param int $store_id
     * @param int $customer_id
     * @return object
     */
    public function getSalesPromotionDetail($product_id, $store_id, $customer_id, $added_products = [])
    {
        $customer = Customer::find($customer_id);
        $store_id = (string) $store_id;
        $product_id = (int) $product_id;
        $added_products = (array)$added_products;

        $customer_type_id = (string) $customer->customer_type_id;
        if (!empty($customer_type_id)) {
            $sales_promotions = SalesPromotion::whereJsonContains('customer_type_ids', $customer_type_id)
                ->whereJsonContains('store_ids', $store_id)
                ->whereJsonContains('product_ids', $product_id)
                ->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))
                ->orWhere('is_discount_permanent','1')
                ->get();
            foreach ($sales_promotions as $sales_promotion) {
                if ($sales_promotion->type == 'item_discount') {
                    if (!$sales_promotion->product_condition) {
                        return $sales_promotion;
                    } else {
                        $com = $this->compareArray($sales_promotion->condition_product_ids, $added_products);
                        if ($com) {
                            return $sales_promotion;
                        }
                    }
                }
            }
        }
    }

    /**
     * get the sales promotion details for products if valid for this sale
     *
     * @param int $store_id
     * @param int $customer_id
     * @param array $added_products
     * @param array $qty_array
     * @return object|array
     */
    public function getSalePromotionDetailsIfValidForThisSale($store_id, $customer_id, $added_products = [], $qty_array = []): object|array
    {
        $customer = Customer::find($customer_id);
        $store_id = (string) $store_id;
        $added_products = (array)$added_products;
        if(!$customer){
            return [];
        }
        $customer_type_id = (string) $customer->customer_type_id;
        $array_sales_promotions=[];

        if (!empty($customer_type_id)) {
            $sales_promotions = SalesPromotion::
                    whereJsonContains('customer_type_ids', $customer_type_id)
                ->whereJsonContains('store_ids', $store_id)
                ->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))
                ->orWhere('is_discount_permanent','1')
                ->get();
            foreach ($sales_promotions as $sales_promotion) {
                $v_sales_promotion = $this->getSalePromotionDetailsIfValidForThisSaleArray($sales_promotion, $added_products, $qty_array);
                if($v_sales_promotion['vale_return'] != null ){
                    array_push($array_sales_promotions,$v_sales_promotion['vale_return']);
                }
                if(empty($v_sales_promotion['qty_array'])){
                    break;

                }
            }
        }

        return $array_sales_promotions;
    }
    public function getSalePromotionDetailsIfValidForThisSaleArray($sales_promotion,$added_products,$qty_array)
    {
        $data['vale_return']=null;
        $data['qty_array']=$qty_array;

        if ($sales_promotion->type == 'item_discount') {
            if (!$sales_promotion->product_condition) {
                $data['vale_return']= $sales_promotion;
            } else {
                $is_valid = $this->compareArray($sales_promotion->condition_product_ids, $added_products);
                if ($is_valid) {
                    $data['vale_return']= $sales_promotion;
                }
            }
        }
        if ($sales_promotion->type == 'package_promotion') {
            $package_promotion_qty = $sales_promotion->package_promotion_qty;

            $is_valid = $this->comparePackagePromotionData($package_promotion_qty, $qty_array);
            if ($is_valid > 0) {
                foreach ($package_promotion_qty as $v_id=>$item_qty){
                   $All_item_qty= $item_qty*$is_valid;
                   if(in_array($v_id,array_keys($qty_array))){
                       $new_qty=$qty_array[$v_id]-$All_item_qty;
                       if($new_qty<=0){
                           unset($qty_array[$v_id]);
                       }else{
                           $qty_array[$v_id]=$new_qty;
                       }
                    }

                }
                $sales_promotion->count_discount_number=$is_valid;
                $data['vale_return']=$sales_promotion;
                $data['qty_array']=$qty_array;
            }


        }

        return $data;
    }
    /**
     * compare package promotion data with add products data
     *
     * @param array $package_promotion_qty
     * @param array $qty_array
     * @return boolean
     */
    public function comparePackagePromotionData($package_promotion_qty, $qty_array)
    {
        $count_discount_array=[];
        foreach ($package_promotion_qty as $product_id => $qty) {
            if (!isset($qty_array[$product_id])) {
                return 0;
            }
            if ($qty_array[$product_id] < $qty) {
                return 0;
            }

            $count_discount_for=(int)($qty_array[$product_id]/(float)$qty );
            array_push($count_discount_array,$count_discount_for);
        }
        return min($count_discount_array);
    }

    //function to compare two array have equal value or not
    public function compareArray($array1, $array2)
    {
        foreach ($array1 as $value) {
            if (!in_array($value, $array2)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get all details for a products from its variation id
     *
     * @param int $variation_id
     * @param int $store_id
     * @param bool $check_qty (If false qty_available is not checked)
     *
     * @return object
     */
    public function getDetails($prodeuct_id,  $store_id = null, $check_qty = true)
    {
        $query = Product::leftjoin('product_stores AS ps', 'products.id', '=', 'ps.product_id')
            ->leftjoin('sizes', 'products.size_id', '=', 'sizes.id')
            ->leftjoin('colors', 'products.color_id', '=', 'colors.id')
            ->where('products.id', $prodeuct_id);


        if (!empty($store_id) && $check_qty) {
            //Check for enable stock, if enabled check for store id.
            $query->where(function ($query) use ($store_id) {
                $query->where('ps.store_id', $store_id);
            });
        }

        $product = $query->select(
            DB::raw("products.name AS product_name"),
            'products.id as product_id',
            'products.sell_price',
            'products.sku',
            'products.barcode_type',
            'ps.qty_available',
            'sizes.name as size_name',
            'colors.name as color_name'
        )
            ->first();

        return $product;
    }

    /**
     * createOrUpdatePurchaseOrderLines
     *
     * @param [mix] $purchase_order_lines
     * @param [mix] $transaction
     * @return void
     */
    public function createOrUpdatePurchaseOrderLines($purchase_order_lines, $transaction)
    {

        $keep_lines_ids = [];

        foreach ($purchase_order_lines as $line) {
            if (!empty($line['purchase_order_line_id'])) {
                $purchase_order_line = PurchaseOrderLine::find($line['purchase_order_line_id']);

                $purchase_order_line->product_id = $line['product_id'];
                $purchase_order_line->variation_id = $line['variation_id'];
                $purchase_order_line->quantity = $this->num_uf($line['quantity']);
                $purchase_order_line->purchase_price = $this->num_uf($line['purchase_price']);
                $purchase_order_line->sub_total = $this->num_uf($line['sub_total']);

                $purchase_order_line->save();
                $keep_lines_ids[] = $line['purchase_order_line_id'];
            } else {
                $purchase_order_line_data = [
                    'transaction_id' => $transaction->id,
                    'product_id' => $line['product_id'],
                    'variation_id' => $line['variation_id'],
                    'quantity' => $this->num_uf($line['quantity']),
                    'purchase_price' => $this->num_uf($line['purchase_price']),
                    'sub_total' => $this->num_uf($line['sub_total']),
                ];

                $purchase_order_line = PurchaseOrderLine::create($purchase_order_line_data);

                $keep_lines_ids[] = $purchase_order_line->id;
            }
        }

        if (!empty($keep_lines_ids)) {
            PurchaseOrderLine::where('transaction_id', $transaction->id)->whereNotIn('id', $keep_lines_ids)->delete();
        }

        return true;
    }

    /**
     * create or update remove stock lines
     *
     * @param [mix] $remove_stock_lines
     * @param [mix] $transaction
     * @return void
     */
    public function createOrUpdateRemoveStockLines($remove_stock_lines, $transaction)
    {

        $keep_lines_ids = [];

        foreach ($remove_stock_lines as $line) {
            if (!empty($line['remove_stock_line_id'])) {
                $remove_stock_line = RemoveStockLine::find($line['remove_stock_line_id']);

                $remove_stock_line->product_id = $line['product_id'];
                $remove_stock_line->variation_id = $line['variation_id'];
                $remove_stock_line->quantity = $this->num_uf($line['quantity']);
                $remove_stock_line->purchase_price = $this->num_uf($line['purchase_price']);
                $remove_stock_line->sub_total = $this->num_uf($line['sub_total']);
                $remove_stock_line->notes = !empty($line['notes']) ? $line['notes'] : null;

                $remove_stock_line->save();
                if ($line['quantity'] > 0) {
                    $this->decreaseProductQuantity($line['product_id'], $line['variation_id'], $transaction->store_id, $line['quantity'], $remove_stock_line->quantity);
                }

                $keep_lines_ids[] = $line['remove_stock_line_id'];
            } else {
                $remove_stock_line_data = [
                    'transaction_id' => $transaction->id,
                    'product_id' => $line['product_id'],
                    'variation_id' => $line['variation_id'],
                    'quantity' => $this->num_uf($line['quantity']),
                    'purchase_price' => $this->num_uf($line['purchase_price']),
                    'sub_total' => $this->num_uf($line['sub_total']),
                    'notes' => !empty($line['notes']) ? $line['notes'] : null,
                ];

                if ($line['quantity'] > 0) {
                    $this->decreaseProductQuantity($line['product_id'], $line['variation_id'], $transaction->store_id, $line['quantity'], 0);
                }

                $remove_stock_line = RemoveStockLine::create($remove_stock_line_data);

                $keep_lines_ids[] = $remove_stock_line->id;
            }
        }

        if (!empty($keep_lines_ids)) {
            $deleted_lines = RemoveStockLine::where('transaction_id', $transaction->id)->whereNotIn('id', $keep_lines_ids)->get();
            foreach ($deleted_lines as $deleted_line) {
                if ($deleted_line->quantity > 0) {
                    $this->updateProductQuantityStore($deleted_line->product_id, $deleted_line->variation_id, $transaction->store_id, $deleted_line->quantity, 0);
                }
                $deleted_line->delete();
            }
        }

        return true;
    }
    /**
     * createOrUpdateAddStockLines
     *
     * @param [mix] $add_stocks
     * @param [mix] $transaction
     * @return void
     */
    public function createOrUpdateAddStockLines($add_stocks, $transaction,$batch_row=null)
    {

        $keep_lines_ids = [];
        $batch_numbers=[];
        $qty=0;
        $all_cost_ratio = 0;
        $number_vs_base_unit=1;
        // return $add_stocks;
        foreach ($add_stocks as $line) {
            if( $transaction->discount_amount || $transaction->other_payments || $transaction->other_expenses){
                $all_cost_percentage = ((($line['quantity'] * $line['purchase_price'])*100) / $transaction->grand_total); //percentage

                $discount_amount_per_line =  !empty($transaction->discount_amount) ? ($transaction->discount_amount * $all_cost_percentage /100 ): 0;
                $other_payments_per_line = !empty($transaction->other_payments) ? ($transaction->other_payments * $all_cost_percentage /100) : 0;
                $other_expenses_per_line = !empty($transaction->other_expenses) ? ($transaction->other_expenses * $all_cost_percentage /100) : 0;
                $all_cost_ratio = $this->num_uf( $other_payments_per_line +$other_expenses_per_line - $discount_amount_per_line );
            }
            if(isset($line['product_id'] )){
            if (!empty($line['add_stock_line_id'])) {
                $add_stock = AddStockLine::find($line['add_stock_line_id']);
                $add_stock->product_id = $line['product_id'];
                $old_qty = $add_stock->quantity;
                $add_stock->quantity = $this->num_uf($line['quantity']);
                $add_stock->purchase_price = $this->num_uf($line['purchase_price']);
                $add_stock->final_cost = $this->num_uf($line['final_cost']);
                $add_stock->sub_total = $this->num_uf($line['sub_total']);
                $add_stock->batch_number = $line['batch_number'];
                $add_stock->manufacturing_date = !empty($line['manufacturing_date']) ? $this->uf_date($line['manufacturing_date']) : null;
                $add_stock->sell_price = $line['selling_price'];
                $add_stock->bounce_qty = $line['bounce_qty'];
                $add_stock->profit_bounce = $line['bounce_profit'];
                $add_stock->bounce_purchase_price = $line['bounce_purchase_price'];
                $add_stock->bounce_convert_status_expire = $line['bounce_convert_status_expire'];
                $add_stock->bounce_expiry_warning = $line['bounce_expiry_warning'];
                $add_stock->bounce_expiry_date = $line['bounce_expiry_date'];
                $add_stock->bounce_manufacturing_date = $line['bounce_manufacturing_date'];
                $add_stock->bounce_batch_number = $line['bounce_batch_number'];
                $add_stock->cost_ratio_per_one = $this->num_uf($all_cost_ratio / $this->num_uf($line['quantity'])) ?? 0;
                $add_stock->updated_by = Auth::guard('admin')->user()->id;
                $add_stock->save();
                $keep_lines_ids[] = $line['add_stock_line_id'];
                $batch_numbers[]=$line['batch_number'];
                $qty =  $number_vs_base_unit * $this->num_uf($line['quantity']);
                $this->updateProductQuantityStore($line['product_id'], $line['variation_id'], $transaction->store_id,  $qty, $old_qty);
            } else {
                $add_stock_data = [
                    'transaction_id' => $transaction->id,
                    'product_id' => $line['product_id'],
                    'quantity' => $this->num_uf($line['quantity']),
                    'purchase_price' => $this->num_uf($line['purchase_price']),
                    'final_cost' => isset($line['final_cost'])?$this->num_uf($line['final_cost']):0,
                    'sub_total' => isset($line['sub_total'])?$this->num_uf($line['sub_total']):0,
                    'batch_number' => isset($line['batch_number'])?$line['batch_number']:null,
                    'manufacturing_date' => !empty($line['manufacturing_date']) ? $this->uf_date($line['manufacturing_date']) : null,
                    'expiry_date' => !empty($line['expiry_date']) ? $this->uf_date($line['expiry_date']) : null,
                    'expiry_warning' => isset($line['expiry_warning'])?$line['expiry_warning']:null,
                    'convert_status_expire' => isset($line['convert_status_expire'])?$line['convert_status_expire']:null,
                    'sell_price' => isset($line['selling_price'])?$line['selling_price']:null,
                    'bounce_qty' => isset($line['bounce_qty'])?$line['bounce_qty']:null,
                    'profit_bounce' => isset($line['bounce_profit'])?$line['bounce_profit']:null,
                    'bounce_purchase_price' => isset($line['bounce_purchase_price'])?$line['bounce_purchase_price']:null,
                    'bounce_convert_status_expire' =>isset($line['bounce_convert_status_expire'])? $line['bounce_convert_status_expire']:null,
                    'bounce_expiry_warning' =>isset($line['bounce_expiry_warning'])? $line['bounce_expiry_warning']:null,
                    'bounce_expiry_date' => isset($line['bounce_expiry_date'])?$line['bounce_expiry_date']:null,
                    'bounce_manufacturing_date' => isset($line['bounce_manufacturing_date'])?$line['bounce_manufacturing_date']:null,
                    'bounce_batch_number' => isset($line['bounce_batch_number'])?$line['bounce_batch_number']:null,
                    'cost_ratio_per_one' => $this->num_uf($all_cost_ratio / $line['quantity']) ?? 0,
                ];

                $add_stock = AddStockLine::create($add_stock_data);
                // $qty =  $this->num_uf($line['quantity']);
                $batch_qty=0;
                if($add_stock){
                    if(!empty($batch_row)){
                        // return $batch_row;

                        foreach($batch_row as $batch){
                            if($batch['product_id']==$line['product_id']){
                                $add_stock_batch_data = [
                                    'transaction_id' => $transaction->id,
                                    'product_id' => $line['product_id'],
                                    'quantity' => $this->num_uf($batch['batch_quantity']),
                                    'purchase_price' =>  $this->num_uf($batch['batch_purchase_price']),
                                    'final_cost' => $this->num_uf($batch['batch_final_cost']),
                                    'sub_total' => $this->num_uf($line['sub_total']),
                                    'batch_number' => $batch['new_batch_number'],
                                    'manufacturing_date' => !empty($batch['batch_manufacturing_date']) ? $this->uf_date($batch['batch_manufacturing_date']) : null,
                                    'expiry_date' => !empty($batch['batch_expiry_date']) ? $this->uf_date($batch['batch_expiry_date']) : null,
                                    'expiry_warning' => $batch['batch_expiry_warning'],
                                    'convert_status_expire' => $line['convert_status_expire'],
                                    'sell_price' => $batch['batch_selling_price'],
                                    'bounce_qty' => $line['bounce_qty'],
                                    'profit_bounce' => $line['bounce_profit'],
                                    'bounce_purchase_price' => $line['bounce_purchase_price'],
                                    'bounce_convert_status_expire' => $line['bounce_convert_status_expire'],
                                    'bounce_expiry_warning' => $line['bounce_expiry_warning'],
                                    'bounce_expiry_date' => $line['bounce_expiry_date'],
                                    'bounce_manufacturing_date' => $line['bounce_manufacturing_date'],
                                    'bounce_batch_number' => $line['bounce_batch_number'],
                                    'cost_ratio_per_one' => $this->num_uf($all_cost_ratio / ($number_vs_base_unit *$line['quantity'])) ?? 0,
                                ];
                                // $batch_number=$add_stock->batch_number;
                                $add_stock_batch = AddStockLine::create($add_stock_batch_data);
                                $batch_numbers[]=$add_stock_batch->batch_number;
                                $batch_qty+= $number_vs_base_unit * $this->num_uf($batch['batch_quantity']);

                                // return $add_stock_batch;
                        }

                        }

                    }
                    $this->updateProductQuantityStore($line['product_id'], $transaction->store_id,  $batch_qty, 0);
                    $batch_qty=0;
                }
                if(isset($line['bounce_purchase_price'])){
                    $product = Product::where('id',$line['product_id'])->update(['purchase_price' =>$line['bounce_purchase_price'] ,'purchase_price_depends' => $line['bounce_purchase_price']]);
                }
                $keep_lines_ids[] = $add_stock->id;
                $batch_numbers[]=$add_stock->batch_number;

                $qty =  $number_vs_base_unit *$this->num_uf($line['quantity']);
                $this->updateProductQuantityStore($line['product_id'], $transaction->store_id,  $qty, 0);
            }
            if(!empty($line['stock_pricechange']) && $line['selling_price']>0){
                AddStockLine::where('product_id',$line['product_id'])
                    ->whereColumn('quantity',">",'quantity_sold')->update([
                        'sell_price' => $line['selling_price'],
                        'updated_by'=>Auth::guard('admin')->user()->id,
                    ]);
            }
            }
        }
        // return $keep_lines_ids;
        if (!empty($keep_lines_ids)) {
            $deleted_lines = AddStockLine::where('transaction_id', $transaction->id)
            ->where(function ($query) use ($batch_numbers, $keep_lines_ids) {
                $query->whereNotIn('id', $keep_lines_ids)
                ->orWhereNotIn('batch_number', $batch_numbers);
                // if(!empty($batch_numbers)){
                //     $query->whereNotIn('batch_number', $batch_numbers);
                // }
            })
            ->get();
            foreach ($deleted_lines as $deleted_line) {
                if ($deleted_line->quantity_sold != 0) {
                    $product_name = Product::find($deleted_line->product_id)->name ?? '';
                    return ['mismatch' => true, 'product_name' => $product_name, 'quantity' => 0];
                }
                $this->decreaseProductQuantity($deleted_line['product_id'], $transaction->store_id, $deleted_line['quantity'], 0);
                $deleted_line->delete();
            }
        }
        return true;
    }
    /**
     * check if there is any quantity mismatch in sold and purchase quantity
     *
     * @param array $add_stock_lines
     * @return mixed
     */
    public function checkSoldAndPurchaseQtyMismatch($add_stock_lines, $transaction)
    {
        $keep_lines_ids = [];

        foreach ($add_stock_lines as $line) {
            if (!empty($line['add_stock_line_id'])) {
                $add_stock_line = AddStockLine::find($line['add_stock_line_id']);
                $keep_lines_ids[] = $add_stock_line->id;
                if (!empty($add_stock_line)) {
                    if ($line['quantity'] < $add_stock_line->quantity_sold) {
                        $product_name = Product::find($add_stock_line->product_id)->name ?? '';
                        return ['mismatch' => true, 'product_name' => $product_name, 'quantity' => $line['quantity']];
                    }
                }
            }
        }

        $deleted_lines = AddStockLine::where('transaction_id', $transaction->id)->whereNotIn('id', $keep_lines_ids)->get();
        foreach ($deleted_lines as $deleted_line) {
            if ($deleted_line->quantity_sold != 0) {
                $product_name = Product::find($deleted_line->product_id)->name ?? '';
                return ['mismatch' => true, 'product_name' => $product_name, 'quantity' => 0];
            }
        }


        return false;
    }

    public function sendQunatityMismacthResponse($product_name, $qty)
    {
        return redirect()->back()->with('status', ['success' => false, 'msg' => __('lang.sold_qty_mismatch_purchase_qty') . '\n Porduct: ' . $product_name . '\n Quantity: ' . $qty]);
    }

    /**
     * createOrUpdateInternalStockRequestLines
     *
     * @param [mix] $transfer_lines
     * @param [mix] $transaction
     * @return float
     */
    public function createOrUpdateInternalStockRequestLines($transfer_lines, $transaction)
    {
        $keep_lines_ids = [];
        $final_total = 0;
        foreach ($transfer_lines as $line) {
            if (!empty($line['transfer_line_id'])) {
                $transfer_line = TransferLine::find($line['transfer_line_id']);

                $transfer_line->product_id = $line['product_id'];
                $transfer_line->variation_id = $line['variation_id'];
                $transfer_line->quantity = $this->num_uf($line['quantity']);
                $transfer_line->purchase_price = $this->num_uf($line['purchase_price']);
                $transfer_line->sub_total = $this->num_uf($line['sub_total']);
                $transfer_line->save();
                $final_total += $line['sub_total'];
                $keep_lines_ids[] = $line['transfer_line_id'];
            } else {
                $transfer_line_data = [
                    'transaction_id' => $transaction->id,
                    'product_id' => $line['product_id'],
                    'variation_id' => $line['variation_id'],
                    'quantity' => $this->num_uf($line['quantity']),
                    'purchase_price' => $this->num_uf($line['purchase_price']),
                    'sub_total' => $this->num_uf($line['sub_total']),
                ];
                $final_total += $line['sub_total'];
                $transfer_line = TransferLine::create($transfer_line_data);
                $keep_lines_ids[] = $transfer_line->id;
            }
        }

        if (!empty($keep_lines_ids)) {
            $deleted_lines = TransferLine::where('transaction_id', $transaction->id)->whereNotIn('id', $keep_lines_ids)->get();
            foreach ($deleted_lines as $deleted_line) {
                $deleted_line->delete();
            }
        }


        return $final_total;
    }
    /**
     * createOrUpdateAddStockLines
     *
     * @param [mix] $transfer_lines
     * @param [mix] $transaction
     * @return void
     */
    public function createOrUpdateTransferLines($transfer_lines, $transaction)
    {

        $keep_lines_ids = [];

        foreach ($transfer_lines as $line) {
            if (!empty($line['transfer_line_id'])) {
                $transfer_line = TransferLine::find($line['transfer_line_id']);

                $transfer_line->product_id = $line['product_id'];
                $transfer_line->variation_id = $line['variation_id'];
                $old_qty = $transfer_line->quantity;
                $transfer_line->quantity = $this->num_uf($line['quantity']);
                $transfer_line->purchase_price = $this->num_uf($line['purchase_price']);
                $transfer_line->sub_total = $this->num_uf($line['sub_total']);
                $transfer_line->save();
                $keep_lines_ids[] = $line['transfer_line_id'];
                $this->decreaseProductQuantity($line['product_id'], $line['variation_id'], $transaction->sender_store_id, $line['quantity'], $old_qty);
                $this->updateProductQuantityStore($line['product_id'], $line['variation_id'], $transaction->receiver_store_id,  $line['quantity'], $old_qty);
            } else {
                $transfer_line_data = [
                    'transaction_id' => $transaction->id,
                    'product_id' => $line['product_id'],
                    'variation_id' => $line['variation_id'],
                    'quantity' => $this->num_uf($line['quantity']),
                    'purchase_price' => $this->num_uf($line['purchase_price']),
                    'sub_total' => $this->num_uf($line['sub_total']),
                ];

                $transfer_line = TransferLine::create($transfer_line_data);

                $keep_lines_ids[] = $transfer_line->id;
                $this->decreaseProductQuantity($line['product_id'], $line['variation_id'], $transaction->sender_store_id, $line['quantity'], 0);
                $this->updateProductQuantityStore($line['product_id'], $line['variation_id'], $transaction->receiver_store_id,  $line['quantity'], 0);
            }
        }

        if (!empty($keep_lines_ids)) {
            $deleted_lines = TransferLine::where('transaction_id', $transaction->id)->whereNotIn('id', $keep_lines_ids)->get();
            foreach ($deleted_lines as $deleted_line) {
                $this->decreaseProductQuantity($deleted_line['product_id'], $deleted_line['variation_id'], $transaction->receiver_store_id, $deleted_line['quantity'], 0);
                $this->updateProductQuantityStore($deleted_line['product_id'], $deleted_line['variation_id'], $transaction->sender_store_id,  $deleted_line['quantity'], 0);
                $deleted_line->delete();
            }
        }


        return true;
    }

    /**
     * createOrUpdatePurchaseReturnLine
     *
     * @param [mix] $purchase_return_lines
     * @param [mix] $transaction
     * @return void
     */
    public function createOrUpdatePurchaseReturnLine($purchase_return_lines, $transaction)
    {

        $keep_lines_ids = [];

        foreach ($purchase_return_lines as $line) {
            if (!empty($line['add_stock_line_id'])) {
                $purchase_return_line = PurchaseReturnLine::find($line['purchase_return_line_id']);

                $purchase_return_line->product_id = $line['product_id'];
                $purchase_return_line->variation_id = $line['variation_id'];
                $purchase_return_line->quantity = $this->num_uf($line['quantity']);
                $purchase_return_line->purchase_price = $this->num_uf($line['purchase_price']);
                $purchase_return_line->sub_total = $this->num_uf($line['sub_total']);

                $purchase_return_line->save();
                if ($transaction->status != 'pending') {
                    $this->decreaseProductQuantity($line['product_id'], $line['variation_id'], $transaction->store_id, $line['quantity'], $purchase_return_line->quantity);
                }
                $keep_lines_ids[] = $line['purchase_return_line_id'];
            } else {
                $purchase_return_line_data = [
                    'transaction_id' => $transaction->id,
                    'product_id' => $line['product_id'],
                    'variation_id' => $line['variation_id'],
                    'quantity' => $this->num_uf($line['quantity']),
                    'purchase_price' => $this->num_uf($line['purchase_price']),
                    'sub_total' => $this->num_uf($line['sub_total']),
                ];

                if ($transaction->status != 'pending') {
                    $this->decreaseProductQuantity($line['product_id'], $line['variation_id'], $transaction->store_id, $line['quantity'], 0);
                }
                $purchase_return_line = PurchaseReturnLine::create($purchase_return_line_data);

                $keep_lines_ids[] = $purchase_return_line->id;
            }
        }

        if (!empty($keep_lines_ids)) {
            $deleted_lines = PurchaseReturnLine::where('transaction_id', $transaction->id)->whereNotIn('id', $keep_lines_ids)->get();
            foreach ($deleted_lines as $deleted_line) {
                $this->updateProductQuantityStore($deleted_line->product_id, $deleted_line->variation_id, $transaction->store_id, $deleted_line->quantity, 0);
                $deleted_line->delete();
            }
        }

        return true;
    }

    /**
     * Checks if products_ has manage stock enabled then Updates quantity for products and its
     * variations
     *
     * @param $product_id
     * @param $variation_id
     * @param $store_id
     * @param $new_quantity
     * @param $old_quantity = 0
     *
     * @return boolean
     */
    public function updateProductQuantityStore($product_id, $store_id, $new_quantity, $old_quantity = 0)
    {
        $qty_difference = $new_quantity - $old_quantity;

        if ($qty_difference != 0) {
            $product_store = ProductStore::where('product_id', $product_id)
                ->where('store_id', $store_id)
                ->first();

            if (empty($product_store)) {
                $product_store = new ProductStore();
                $product_store->product_id = $product_id;
                $product_store->store_id = $store_id;
                $product_store->qty_available = 0;
            }

            $product_store->qty_available += $qty_difference;
            $product_store->save();
        }

        return true;
    }


    /**
     * Checks if products_ has manage stock enabled then Decrease quantity for products and its variations
     *
     * @param $product_id
     * @param $variation_id
     * @param $store_id
     * @param $new_quantity
     * @param $old_quantity = 0
     *
     * @return boolean
     */
    public function decreaseProductQuantity($product_id, $store_id, $new_quantity, $old_quantity = 0)
    {
        $qty_difference = $new_quantity- $old_quantity;
        $product = Product::find($product_id);

        //Check if stock is enabled or not.
        if ($product->is_service != 1) {
            //Decrement Quantity in variations store table
            $details = ProductStore::where('product_id', $product_id)
                ->where('store_id', $store_id)
                ->first();

            //If store details not exists create new one
            if (empty($details)) {
                $details = ProductStore::create([
                    'product_id' => $product_id,
                    'store_id' => $store_id,
                    'qty_available' => 0
                ]);
            }

            $details->decrement('qty_available', $qty_difference);
        }

        return true;
    }

    /**
     * update the block quantity for quotations
     *
     * @param int $product_id
     * @param int $variation_id
     * @param int $store_id
     * @param int $new_quantity
     * @param string $old_quantity
     * @return void
     */
    public function updateBlockQuantity($product_id, $store_id, $qty, $type = 'add')
    {
        if ($type == 'add') {
            ProductStore::where('product_id', $product_id)->where('store_id', $store_id)->increment('block_qty', $qty);
        }
        if ($type == 'subtract') {
            ProductStore::where('product_id', $product_id)->where('store_id', $store_id)->decrement('block_qty', $qty);
        }
    }

    /**
     * extract products_ using products tree selection
     *
     * @param array $data_selected
     * @return array
     */
    public function extractProductIdsfromProductTree($data_selected)
    {
        $product_ids = [];

        // if (!empty($data_selected['product_class_selected'])) {
        //     $pcp = Product::whereIn('product_class_id', $data_selected['product_class_selected'])->select('id')->pluck('id')->toArray();
        //     $product_ids = array_merge($product_ids, $pcp);
        // }
        // if (!empty($data_selected['category_selected'])) {
        //     $cp = array_values(Product::whereIn('category_id', $data_selected['category_selected'])->select('id')->pluck('id')->toArray());
        //     $product_ids = array_merge($product_ids, $cp);
        // }
        // if (!empty($data_selected['sub_category_selected'])) {
        //     $scp = array_values(Product::whereIn('sub_category_id', $data_selected['sub_category_selected'])->select('id')->pluck('id')->toArray());
        //     $product_ids = array_merge($product_ids, $scp);
        // }
        // if (!empty($data_selected['brand_selected'])) {
        //     $bp = array_values(Product::whereIn('brand_id', $data_selected['brand_selected'])->select('id')->pluck('id')->toArray());
        //     $product_ids = array_merge($product_ids, $bp);
        // }
        if (!empty($data_selected['product_selected'])) {
            $p = array_values(Product::whereIn('id', $data_selected['product_selected'])->select('id')->pluck('id')->toArray());
            $product_ids = array_merge($product_ids, $p);
        }

        $product_ids  = array_unique($product_ids);

        return (array)$product_ids;
    }
    /**
     * extract products_ using products tree selection
     *
     * @param array $data_selected
     * @return array
     */
    public function extractProductVariationIdsfromProductTree($data_selected)
    {
        $product_ids = [];
        if (!empty($data_selected['product_selected'])) {
            $p = array_values(Variation::whereIn('id', $data_selected['product_selected'])->select('id')->pluck('id')->toArray());
            $product_ids = array_merge($product_ids, $p);
        }

        $product_ids  = array_unique($product_ids);

        return (array)$product_ids;
    }
    public function getProductDetailsUsingArrayIds($array, $store_ids = null,$variations= null)
    {
        $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
            ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id');

        if (!empty($store_ids)) {
            $query->whereIn('product_stores.store_id', $store_ids);
        }
        if (!empty($variations)) {
            $query->whereIn('variations.id', $array);
        }else{
            $query->whereIn('products.id', $array);
        }

        $query->select(
                'products.*',
                'variations.id as variations_id',
                'variations.name as variations_name',
                'variations.sub_sku as variations_sku',
                'variations.default_sell_price as variations_sell_price',
                'variations.default_purchase_price as variations_purchase_price',
                DB::raw('SUM(product_stores.qty_available) as current_stock'),
                DB::raw("(SELECT transaction_date FROM transactions LEFT JOIN add_stock_lines ON transactions.id=add_stock_lines.transaction_id WHERE add_stock_lines.product_id=products.id ORDER BY transaction_date DESC LIMIT 1) as date_of_purchase")
            );
        if(!empty($variations)){
            $query->groupBy('variations.id');
        }else{
            $query ->groupBy('products.id');
        }

        $products = $query->get();
        return $products;
    }
    public function getVariationDetailsUsingArrayIds($array, $store_ids = null,$variations= null)
    {
        $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
            ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id');

        if (!empty($store_ids)) {
            $query->whereIn('product_stores.store_id', $store_ids);
        }
        if (!empty($variations)) {
            $query->whereIn('variations.id', $array);
        }else{
            $query->whereIn('products.id', $array);
        }

        $query->select(
            'products.*',
            'variations.id as variations_id',
            'variations.name as variations_name',
            'variations.sub_sku as variations_sku',
            'variations.default_sell_price as variations_sell_price',
            'variations.default_purchase_price as variations_purchase_price',
            DB::raw('SUM(product_stores.qty_available) as current_stock'),
            DB::raw("(SELECT transaction_date FROM transactions LEFT JOIN add_stock_lines ON transactions.id=add_stock_lines.transaction_id WHERE add_stock_lines.product_id=products.id ORDER BY transaction_date DESC LIMIT 1) as date_of_purchase")
        );
        if(!empty($variations)){
            $query->groupBy('variations.id');
        }else{
            $query ->groupBy('products.id');
        }


        $products = $query->get();
        return $products;
    }

    /**
     * get the stock value by store id
     *
     * @param int $store_id
     * @return void
     */
    // public function getCurrentStockValueByStore($store_id = null)
    // {
    //     if(!$store_id){
    //         $stores = Store::get();
    //         $current_stock_value = 0;
    //         foreach($stores as $store){
    //             $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
    //             ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
    //             ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')

    //             ->where('is_service', 0)
    //             ->where('product_stores.store_id', $store->id)
    //             ->where('add_stock_lines.purchase_price','>',0)
    //             ;
    //             $query->groupBy('variations.id')->select(
    //                 DB::raw('(product_stores.qty_available ) * add_stock_lines.purchase_price as current_stock_value'),
    //             );

    //             $current_stock_value += $query->get()->sum('current_stock_value');
    //         }
    //         return $current_stock_value;
    //     }

    //     $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
    //         ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
    //         ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')
    //         ->where('product_stores.store_id', $store_id)
    //         ->where('is_service', 0)
    //         ->where('add_stock_lines.purchase_price','>',0)
    //         ;

    //     $query->groupBy('variations.id')->select(
    //         DB::raw('(product_stores.qty_available ) * add_stock_lines.purchase_price as current_stock_value'),
    //     );

    //     $current_stock_value = $query->get()->sum('current_stock_value');
    //     // dd($current_stock_value);
    //     return $current_stock_value ;
    // }
    public function getCurrentStockValueByStore($store_id = null)
    {
        $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
            ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
            ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')
            ->where('is_service', 0);
        if (!empty($store_id)) {
            $query->where('product_stores.store_id', $store_id);
        }
        $query->select(
            DB::raw('SUM((add_stock_lines.quantity - add_stock_lines.quantity_sold ) * add_stock_lines.purchase_price) as current_stock_value'),
        );

        $current_stock_value = $query->first();

        return $current_stock_value ? $current_stock_value->current_stock_value : 0;
    }
    public function getCurrentStockValueProductByStore($store_id = null)
    {
        if(!$store_id){
            $stores = Store::get();
            $current_stock_value_product = 0;
            foreach($stores as $store){
                $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
                ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
                ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')

                ->where('is_service', 0)
                ->where('is_raw_material',0)
                ->where('product_stores.store_id', $store->id)
                ->where('add_stock_lines.purchase_price','>',0)
                ;
                $query->groupBy('variations.id')->select(
                    DB::raw('SUM((add_stock_lines.quantity - add_stock_lines.quantity_sold ) * add_stock_lines.purchase_price) as current_stock_value'),
                );

                $current_stock_value_product += $query->get()->sum('current_stock_value');
            }
            return $current_stock_value_product;
        }

        $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
            ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
            ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')
            ->where('product_stores.store_id', $store_id)
            ->where('is_service', 0)
            ->where('is_raw_material',0)
            ->where('add_stock_lines.purchase_price','>',0)
            ;

        $query->groupBy('variations.id')->select(
            DB::raw('SUM((add_stock_lines.quantity - add_stock_lines.quantity_sold ) * add_stock_lines.purchase_price) as current_stock_value'),
        );

        $current_stock_value_product = $query->get()->sum('current_stock_value');
        return $current_stock_value_product ;
    }

    public function getCurrentStockValueMaterialByStore($store_id = null)
    {
         if(!$store_id){
            $stores = Store::get();
            $current_stock_value_material = 0;
            foreach($stores as $store){
                $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
                ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
                ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')

                ->where('is_service', 0)
                ->where('is_raw_material',1)
                ->where('product_stores.store_id', $store->id)
                ->where('add_stock_lines.purchase_price','>',0)
                ;
                $query->groupBy('variations.id')->select(
                    DB::raw('SUM((add_stock_lines.quantity - add_stock_lines.quantity_sold ) * add_stock_lines.purchase_price) as current_stock_value'),
                );

                $current_stock_value_material += $query->get()->sum('current_stock_value');
            }
            return $current_stock_value_material;
        }

        $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
            ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
            ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')
            ->where('product_stores.store_id', $store_id)
            ->where('is_service', 0)
            ->where('is_raw_material',0)
            ->where('add_stock_lines.purchase_price','>',0)
            ;

        $query->groupBy('variations.id')->select(
            DB::raw('SUM((add_stock_lines.quantity - add_stock_lines.quantity_sold ) * add_stock_lines.purchase_price) as current_stock_value'),
        );

        $current_stock_value_material = $query->get()->sum('current_stock_value');
        return $current_stock_value_material ;
    }

    public function getCurrentSellStockValueByStore($store_id = null)
    {
        $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
            ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
            ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')
            ->where('is_service', 0);
        if (!empty($store_id)) {
            $query->where('product_stores.store_id', $store_id);
        }
        $query->select(
            DB::raw('SUM((add_stock_lines.quantity - add_stock_lines.quantity_sold ) * add_stock_lines.sell_price) as current_stock_value'),
        );

        $current_stock_value = $query->first();

        return $current_stock_value ? $current_stock_value->current_stock_value : 0;
    }
    public function getCurrentSellStockValueProductByStore($store_id = null)
    {
        if(!$store_id){
            $stores = Store::get();
            $current_stock_value_product = 0;
            foreach($stores as $store){
                $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
                ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
                ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')

                ->where('is_service', 0)
                ->where('is_raw_material',0)
                ->where('product_stores.store_id', $store->id)
                ->where('add_stock_lines.sell_price','>',0)
                ;
                $query->groupBy('variations.id')->select(
                    DB::raw('SUM((add_stock_lines.quantity - add_stock_lines.quantity_sold ) * add_stock_lines.sell_price) as current_stock_value'),
                );

                $current_stock_value_product += $query->get()->sum('current_stock_value');
            }
            return $current_stock_value_product;
        }

        $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
            ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
            ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')
            ->where('product_stores.store_id', $store_id)
            ->where('is_service', 0)
            ->where('is_raw_material',0)
            ->where('add_stock_lines.sell_price','>',0)
            ;

        $query->groupBy('variations.id')->select(
            DB::raw('SUM((add_stock_lines.quantity - add_stock_lines.quantity_sold ) * add_stock_lines.sell_price) as current_stock_value'),
        );

        $current_stock_value_product = $query->get()->sum('current_stock_value');
        return $current_stock_value_product ;
    }

    public function getCurrentSellStockValueMaterialByStore($store_id = null)
    {
         if(!$store_id){
            $stores = Store::get();
            $current_stock_value_material = 0;
            foreach($stores as $store){
                $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
                ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
                ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')

                ->where('is_service', 0)
                ->where('is_raw_material',1)
                ->where('product_stores.store_id', $store->id)
                ->where('add_stock_lines.sell_price','>',0)
                ;
                $query->groupBy('variations.id')->select(
                    DB::raw('SUM((add_stock_lines.quantity - add_stock_lines.quantity_sold ) * add_stock_lines.sell_price) as current_stock_value'),
                );

                $current_stock_value_material += $query->get()->sum('current_stock_value');
            }
            return $current_stock_value_material;
        }

        $query = Product::leftjoin('variations', 'products.id', 'variations.product_id')
            ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
            ->leftjoin('add_stock_lines', 'variations.id', 'add_stock_lines.variation_id')
            ->where('product_stores.store_id', $store_id)
            ->where('is_service', 0)
            ->where('is_raw_material',0)
            ->where('add_stock_lines.sell_price','>',0)
            ;

        $query->groupBy('variations.id')->select(
            DB::raw('SUM((add_stock_lines.quantity - add_stock_lines.quantity_sold ) * add_stock_lines.sell_price) as current_stock_value'),
        );

        $current_stock_value_material = $query->get()->sum('current_stock_value');
        return $current_stock_value_material ;
    }
    /**
     * get products list for products tree
     *
     * @return void
     */
    public function getProductList($store_array = [], $sender_store_id = null)
    {
        $products = Product::leftjoin('variations', 'products.id', 'variations.product_id')
            ->leftjoin('product_stores', 'variations.id', 'product_stores.variation_id')
            ->leftjoin('stores', 'product_stores.store_id', 'stores.id');

        if (!empty($store_array)) {
            $products->whereIn('product_stores.store_id', $store_array);
        }
        if (!empty(request()->store_id)) {
            $products->where('product_stores.store_id', request()->store_id);
        }

        if (!empty(request()->product_id)) {
            $products->where('products.id', request()->product_id);
        }

        if (!empty(request()->product_class_id)) {
            $products->where('product_class_id', request()->product_class_id);
        }

        if (!empty(request()->category_id)) {
            $products->where('category_id', request()->category_id);
        }

        if (!empty(request()->sub_category_id)) {
            $products->where('sub_category_id', request()->sub_category_id);
        }

        if (!empty(request()->tax_id)) {
            $products->where('tax_id', request()->tax_id);
        }

        if (!empty(request()->brand_id)) {
            $products->where('brand_id', request()->brand_id);
        }

        if (!empty(request()->unit_id)) {
            $products->whereJsonContains('multiple_units', request()->unit_id);
        }

        if (!empty(request()->color_id)) {
            $products->whereJsonContains('color_id', request()->color_id);
        }

        if (!empty(request()->size_id)) {
            $products->whereJsonContains('size_id', request()->size_id);
        }

        if (!empty(request()->grade_id)) {
            $products->whereJsonContains('multiple_grades', request()->grade_id);
        }

        if (!empty(request()->customer_type_id)) {
            $products->whereJsonContains('show_to_customer_types', request()->customer_type_id);
        }

        if (!empty(request()->customer_type_id)) {
            $products->whereJsonContains('show_to_customer_types', request()->customer_type_id);
        }
        if (!empty(request()->is_raw_material)) {
            $products->where('is_raw_material', 1);
        } else {
            $products->where('is_raw_material', 0);
        }

        $products->where('is_service', 0);

        if (empty($sender_store_id)) {
            $products = $products->select(
                'products.*',
                'variations.id as variation_id',
                'stores.name as store_name',
                'stores.id as store_id',
                DB::raw('SUM(product_stores.qty_available) as current_stock'),
            )->having('current_stock', '>', 0)
                ->groupBy('products.id', 'product_stores.id')
                ->get();
        } else {
            $products = $products->select(
                'products.*',
                'variations.id as variation_id',
                'stores.name as store_name',
                'stores.id as store_id',
                // DB::raw('SUM(product_stores.qty_available) as current_stock'),
                DB::raw('(SELECT SUM(product_stores.qty_available) FROM product_stores WHERE product_stores.product_id=products.id AND product_stores.store_id=' . $sender_store_id . ') as current_stock')
            )->having('current_stock', '>', 0)
                ->groupBy('products.id', 'product_stores.id')
                ->get();
        }

        return $products;
    }

    /**
     * Filters products as per the given inputs and return the details.
     *
     * @param string $search_type (like or exact)
     *
     * @return object
     */
    public function filterProduct($search_term, $search_fields = [], $search_type = 'like')
    {

        $query = Product::join('variations', 'products.id', '=', 'variations.product_id')
            ->active()
            ->whereNull('variations.deleted_at');


        if (!empty($product_types)) {
            $query->whereIn('products.type', $product_types);
        }

        //Include search
        if (!empty($search_term)) {

            //Search with like condition
            if ($search_type == 'like') {
                $query->where(function ($query) use ($search_term, $search_fields) {

                    if (in_array('name', $search_fields)) {
                        $query->where('products.name', 'like', '%' . $search_term . '%');
                    }

                    if (in_array('sku', $search_fields)) {
                        $query->orWhere('sku', 'like', '%' . $search_term . '%');
                    }

                    if (in_array('sub_sku', $search_fields)) {
                        $query->orWhere('sub_sku', 'like', '%' . $search_term . '%');
                    }

                    if (in_array('lot', $search_fields)) {
                        $query->orWhere('pl.lot_number', 'like', '%' . $search_term . '%');
                    }
                });
            }

            //Search with exact condition
            if ($search_type == 'exact') {
                $query->where(function ($query) use ($search_term, $search_fields) {

                    if (in_array('name', $search_fields)) {
                        $query->where('products.name', $search_term);
                    }

                    if (in_array('sku', $search_fields)) {
                        $query->orWhere('sku', $search_term);
                    }

                    if (in_array('sub_sku', $search_fields)) {
                        $query->orWhere('sub_sku', $search_term);
                    }

                    if (in_array('lot', $search_fields)) {
                        $query->orWhere('pl.lot_number', $search_term);
                    }
                });
            }
        }

        $query->select(
            'products.id as product_id',
            'products.name',
            'products.type',
            'variations.id as variation_id',
            'variations.name as variation',
            'variations.default_sell_price',
            'variations.sub_sku',
            'products.weighing_scale_barcode'
        );

        $query->groupBy('variations.id');
//        dd($query->get());
        return $query
            ->get();
    }

    public function getNonIdentifiableProductDetails($name, $sell_price, $purchase_price, $request)
    {
        $product_exist = Product::where('name', $name)->first();

        if (!empty($product_exist)) {
            $product_exist->purchase_price = $purchase_price;
            $product_exist->sell_price = $sell_price;

            $product_exist->save();
            $variation = Variation::where('product_id', $product_exist->id)->first();
            $variation->default_purchase_price = $purchase_price;
            $variation->default_sell_price = $sell_price;
            $variation->save();
        } else {
            $product_data = [
                'name' => $name,
                'sku' => !empty($sku) ? $sku : $this->generateSku($name),
                'multiple_units' => [],
                'color_id' => [],
                'size_id' => [],
                'multiple_grades' => [],
                'is_service' => 1,
                'product_details' => null,
                'barcode_type' => 'C128',
                'alert_quantity' => 0,
                'purchase_price' => $purchase_price,
                'sell_price' => $sell_price,
                'tax_id' => null,
                'tax_method' => null,
                'discount_type' => 'fixed',
                'discount_customer_types' => [],
                'discount_customers' => [],
                'discount' => 0,
                'discount_start_date' => null,
                'discount_end_date' => null,
                'show_to_customer' => 0,
                'show_to_customer_types' => 0,
                'different_prices_for_stores' => 0,
                'this_product_have_variant' => 0,
                'type' => 'single',
                'active' => 0,
                'created_by' => Auth::guard('admin')->user()->id
            ];
            $product = Product::create($product_data);

            $this->createOrUpdateVariations($product, $request);
        }

        $query = Product::join('variations', 'products.id', '=', 'variations.product_id')
            ->whereNull('variations.deleted_at')
            ->where('products.name', $name);

        $query->select(
            'products.id as product_id',
            'products.name',
            'products.type',
            'variations.id as variation_id',
            'variations.name as variation',
            'variations.default_sell_price',
            'variations.sub_sku',
        );

        $query->groupBy('variations.id');
        return $query
            ->first();
    }

    public function getCurrentStockDataByProduct($product_id, $store_id = null)
    {
        $query = ProductStore::where('product_id', $product_id);

        if (!empty($store_id)) {
            $query->where('store_id', $store_id);
        }

        $current_Stock = $query->sum('qty_available');
        return ['current_stock' => $current_Stock];
    }

    public function createProductStoreForThisStoreIfNotExist($store_id)
    {
        $variations = Variation::whereNull('deleted_at')->get();

        foreach ($variations as $variation) {
            $product_store = ProductStore::where('product_id', $variation->product_id)->where('variation_id', $variation->id)->where('store_id', $store_id)->first();

            if (empty($product_store)) {
                $product_store = new ProductStore();
                $product_store->product_id = $variation->product_id;
                $product_store->variation_id = $variation->id;
                $product_store->store_id = $store_id;
                $product_store->qty_available = 0;
                $product_store->save();
            }
        }
    }
}
