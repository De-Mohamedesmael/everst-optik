<?php

namespace App\Utils;

//use PurchaseOrderController;
//use Consumption;
//use ConsumptionDetail;
use Illuminate\Support\Facades\Cache;

use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerBalanceAdjustment;
use Modules\Customer\Entities\CustomerImportantDate;
//use PurchaseOrderLine;
//use RedemptionOfPoint;
//use Referred;
//use RewardSystem;
//use Modules\Setting\Entities\Store;
//use \Supplier;

use Modules\Customer\Entities\Prescription;

use Modules\Setting\Entities\Tax;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\AddStock\Entities\AddStockLine;
use Modules\AddStock\Entities\Transaction;
use Modules\AddStock\Entities\TransactionPayment;
use Modules\Sale\Entities\TransactionSellLine;
use Modules\Hr\Entities\Employee;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\System;

class TransactionUtil extends Util
{
    /**
     * All Utils instance.
     *
     */
    protected $productUtil;

    /**
     * Constructor
     *
     * @param ProductUtils $product
     * @return void
     */
    public function __construct(ProductUtil $productUtil)
    {
        $this->productUtil = $productUtil;
    }

    /**
     * createOrUpdateTransactionPayment
     *
     * @param object $transaction
     * @param array $payment_data
     * @return object
     */
    public function createOrUpdateTransactionPayment($transaction, $payment_data)
    {
        if (!empty($payment_data['transaction_payment_id'])) {
            $transaction_payment = TransactionPayment::find($payment_data['transaction_payment_id']);
            $transaction_payment->amount = $payment_data['amount'];
            $transaction_payment->method = $payment_data['method'];
            $transaction_payment->payment_for = !empty($payment_data['payment_for']) ? $payment_data['payment_for'] : $transaction->customer_id;
            $transaction_payment->ref_number = !empty($payment_data['ref_number']) ? $payment_data['ref_number'] : null;
            $transaction_payment->source_type = !empty($payment_data['source_type']) ? $payment_data['source_type'] : null;
            $transaction_payment->source_id = !empty($payment_data['source_id']) ? $payment_data['source_id'] : null;
            $transaction_payment->bank_deposit_date = !empty($payment_data['bank_deposit_date']) ? $payment_data['bank_deposit_date'] : null;
            $transaction_payment->bank_name = !empty($payment_data['bank_name']) ?  $payment_data['bank_name'] : null;
            $transaction_payment->card_number = !empty($payment_data['card_number']) ?  $payment_data['card_number'] : null;
            $transaction_payment->card_security = !empty($payment_data['card_security']) ?  $payment_data['card_security'] : null;
            $transaction_payment->card_month = !empty($payment_data['card_month']) ?  $payment_data['card_month'] : null;
            $transaction_payment->card_year = !empty($payment_data['card_year']) ?  $payment_data['card_year'] : null;
            $transaction_payment->cheque_number = !empty($payment_data['cheque_number']) ?  $payment_data['cheque_number'] : null;
            $transaction_payment->gift_card_number = !empty($payment_data['gift_card_number']) ?  $payment_data['gift_card_number'] : null;
            $transaction_payment->amount_to_be_used = !empty($payment_data['amount_to_be_used']) ?  $this->num_uf($payment_data['amount_to_be_used']) : 0;
            $transaction_payment->payment_note = !empty($payment_data['payment_note']) ?  $payment_data['payment_note'] : null;
            $transaction_payment->created_by = !empty($payment_data['created_by']) ? $payment_data['created_by'] : Auth::guard('admin')->user()->id;
            $transaction_payment->is_return = !empty($payment_data['is_return']) ? 1 : 0;
            $transaction_payment->paid_on = $payment_data['paid_on'];

            $transaction_payment->save();
        } else {
            $transaction_payment = null;
            if (!empty($payment_data['amount'])) {
                $payment_data['created_by'] = Auth::guard('admin')->user()->id;
                $payment_data['payment_for'] = !empty($payment_data['payment_for']) ? $payment_data['payment_for'] : $transaction->customer_id;
                $transaction_payment = TransactionPayment::create($payment_data);
                //     if($transaction->type == 'sell'){
                //         if($payment_data['method'] != 'deposit'){
                //             if($payment_data['amount'] > $transaction->final_total)
                //             $payment_data['amount'] = $transaction->final_total;
                //         }else{

                //         }
                //     }
            }
        }

        return $transaction_payment;
    }

    /**
     * updateTransactionPaymentStatus function
     *
     * @param integer $transaction_id
     * @return mixed
     */
    public function updateTransactionPaymentStatus($transaction_id)
    {
        $transaction_payments = TransactionPayment::where('transaction_id', $transaction_id)->get();

        $total_paid = $transaction_payments->sum('amount');

        $transaction = Transaction::find($transaction_id);
        $returned_transaction = Transaction::where('return_parent_id',$transaction_id)->sum('final_total');
        if($returned_transaction){
            $final_amount = $transaction->final_total - $transaction->used_deposit_balance -  $returned_transaction;
        }else{
            $final_amount = $transaction->final_total - $transaction->used_deposit_balance;
        }
        // return $total_paid;
        $payment_status = 'pending';
        if ($final_amount <= $total_paid) {
            $payment_status = 'paid';
        } elseif ($total_paid > 0 && $final_amount > $total_paid) {
            $payment_status = 'partial';
        }
        $transaction->payment_status = $payment_status;
        $transaction->save();

        return $transaction;
    }


    /**
     * create sell line for products in sale
     *
     * @param object $transaction
     * @param array $transaction_sell_lines
     * @return boolean
     */
    public function createOrUpdateTransactionSellLine($transaction, $transaction_sell_lines)
    {
        $keep_sell_lines = [];
        $is_lens=0;
        foreach ($transaction_sell_lines as $line) {
            $old_quantity = 0;

            if (!empty($transaction_sell_lines['transaction_sell_line_id'])) {
                $transaction_sell_line = TransactionSellLine::find($transaction_sell_lines['transaction_sell_line_id']);
                $transaction_sell_line->product_id = $line['product_id'];

                $transaction_sell_line->promotion_discount = !empty($line['promotion_discount']) ? $this->num_uf($line['promotion_discount']) : 0;
                $transaction_sell_line->promotion_discount_type = !empty($line['promotion_discount_type']) ? $line['promotion_discount_type'] : null;
                $transaction_sell_line->promotion_discount_amount = !empty($line['promotion_discount_amount']) ? $this->num_uf($line['promotion_discount_amount']) : 0;
                $transaction_sell_line->product_discount_value = !empty($line['product_discount_value']) ? $this->num_uf($line['product_discount_value']) : 0;
                $transaction_sell_line->product_discount_type = !empty($line['product_discount_type']) ? $line['product_discount_type'] : null;
                $transaction_sell_line->product_discount_amount = !empty($line['product_discount_amount']) ? $this->num_uf($line['product_discount_amount']) : 0;
                $transaction_sell_line->discount_category = !empty($line['discount_category']) ? $line['discount_category'] : '';
                $transaction_sell_line->batch_number = !empty($line['batch_number']) ?$line['batch_number']: null;
                $old_quantity = $transaction_sell_line->quantity;
                $transaction_sell_line->quantity =(float) $line['quantity'];
                $transaction_sell_line->sell_price = $this->num_uf($line['sell_price']);
                $transaction_sell_line->purchase_price = $this->num_uf($line['purchase_price']);
                $transaction_sell_line->sub_total = $this->num_uf($line['sub_total']);
                $transaction_sell_line->tax_id = !empty($line['tax_id']) ? $line['tax_id'] : null;
                $transaction_sell_line->tax_method = !empty($line['tax_method']) ? $line['tax_method'] : null;
                $transaction_sell_line->tax_rate = !empty($line['tax_rate']) ? $this->num_uf($line['tax_rate']) : 0;
                $transaction_sell_line->item_tax = !empty($line['item_tax']) ? $this->num_uf($line['item_tax']) : 0;
                $transaction_sell_line->save();
                $keep_sell_lines[] = $line['transaction_sell_line_id'];


            }
            else {
                $transaction_sell_line = new TransactionSellLine();
                $transaction_sell_line->transaction_id = $transaction->id;
                $transaction_sell_line->is_lens = $line['is_lens'];
                $transaction_sell_line->product_id = $line['product_id'];

                $transaction_sell_line->promotion_discount = !empty($line['promotion_discount']) ? $this->num_uf($line['promotion_discount']) : 0;
                $transaction_sell_line->promotion_discount_type = !empty($line['promotion_discount_type']) ? $line['promotion_discount_type'] : null;
                $transaction_sell_line->promotion_discount_amount = !empty($line['promotion_discount_amount']) ? $this->num_uf($line['promotion_discount_amount']) : 0;
                $transaction_sell_line->product_discount_value = !empty($line['product_discount_value']) ? $this->num_uf($line['product_discount_value']) : 0;
                $transaction_sell_line->product_discount_type = !empty($line['product_discount_type']) ? $line['product_discount_type'] : null;
                $transaction_sell_line->product_discount_amount = !empty($line['product_discount_amount']) ? $this->num_uf($line['product_discount_amount']) : 0;
                $transaction_sell_line->discount_category = !empty($line['discount_category']) ? $line['discount_category'] : '';
                $transaction_sell_line->batch_number = !empty($line['batch_number']) ? $line['batch_number'] : null;
                $transaction_sell_line->quantity = (float) $line['quantity'];
                $transaction_sell_line->sell_price = $this->num_uf($line['sell_price']);
                $transaction_sell_line->purchase_price = $this->num_uf($line['purchase_price']);
                $transaction_sell_line->sub_total = $this->num_uf($line['sub_total']);
                $transaction_sell_line->tax_id = !empty($line['tax_id']) ? $line['tax_id'] : null;
                $transaction_sell_line->tax_method = !empty($line['tax_method']) ? $line['tax_method'] : null;
                $transaction_sell_line->tax_rate = !empty($line['tax_rate']) ? $this->num_uf($line['tax_rate']) : 0;
                $transaction_sell_line->item_tax = !empty($line['item_tax']) ? $this->num_uf($line['item_tax']) : 0;
                $transaction_sell_line->save();
                $keep_sell_lines[] = $transaction_sell_line->id;
                if($line['is_lens']){
                    $is_lens=$line['is_lens'];
                    $KeyLens=$line['KeyLens'];
                    $data=Cache::get($KeyLens);
                    $prescription_data=[
                        'customer_id' => $transaction->customer_id,
                        'product_id' => $line['product_id'],
                        'sell_line_id' => $transaction_sell_line->id,
                        'date' => date('Y-m-d'),
                        'data' => json_encode($data),
                    ];

                    Prescription::create($prescription_data);
                }
            }
            $stock_id=$line['stock_id'];
            $this->updateSoldQuantityInAddStockLine($transaction_sell_line->product_id, $transaction->store_id,(float) $line['quantity'], $old_quantity,$stock_id);
        }
        $transaction->is_lens=$is_lens;
        $transaction->save();
        //delete sell lines remove by user
        TransactionSellLine::where('transaction_id', $transaction->id)->whereNotIn('id', $keep_sell_lines)->delete();

        return true;
    }

    /**
     * update the sold quanitty in purchase lines
     *
     * @param int $product_id
     * @param int $store_id
     * @param float $new_quantity
     * @param float $old_quantity
     * @return void
     */
    public function updateSoldQuantityInAddStockLine($product_id, $store_id, $new_quantity, $old_quantity,$stock_id=null)
    {
        $qty_difference = $new_quantity - $old_quantity;
        // $qty_difference =$new_quantity - $old_quantity;
        if ($qty_difference != 0) {
            $add_stock_lines = AddStockLine::leftjoin('transactions', 'add_stock_lines.transaction_id', 'transactions.id')
                ->where('transactions.store_id', $store_id)
                ->where('product_id', $product_id)
                ->select('add_stock_lines.id', DB::raw('SUM(quantity - quantity_sold) as remaining_qty'))
                ->having('remaining_qty', '>', 0)
                ->groupBy('add_stock_lines.id')
                ->get();
            foreach ($add_stock_lines as $line) {
                if ($qty_difference == 0) {
                    return true;
                }

                if ($line->remaining_qty >= $qty_difference) {
                    $line->increment('quantity_sold', $qty_difference);
                    $qty_difference = 0;
                }
                if ($line->remaining_qty < $qty_difference) {
                    $line->increment('quantity_sold', $line->remaining_qty);
                    $qty_difference = $qty_difference - $line->remaining_qty;
                }
            }
        }

        return true;
    }


    /**
     * create or update transaction supplier service
     *
     * @param object $transaction
     * @param object $request
     * @return void
     */
    public function createOrUpdateTransactionSupplierService($transaction, $request)
    {
        $transaction_sell_lines = $transaction->transaction_sell_lines;
        $default_currency = System::getProperty('currency');
        $keep_supplier_service = [];
        $keep_supplier_service_lines = [];
        foreach ($transaction_sell_lines as $sell_line) {
            $product = $sell_line->product;
            if (!empty($product->supplier->id)) {
                $transaction_data = [
                    'store_id' => $transaction->store_id,
                    'supplier_id' => $product->supplier->id,
                    'type' => 'supplier_service',
                    'status' => 'final',
                    'paying_currency_id' => $default_currency,
                    'exchange_rate' => 1,
                    'order_date' => !empty($transaction) ? $transaction->transaction_date : Carbon::now(),
                    'transaction_date' => !empty($transaction->transaction_date) ? $transaction->transaction_date : Carbon::now(),
                    'payment_status' => 'pending',
                    'parent_sale_id' => $transaction->id,
                    'grand_total' => $sell_line->quantity * $sell_line->product->purchase_price,
                    'final_total' => $sell_line->quantity * $sell_line->product->purchase_price,
                    'discount_amount' => 0,
                    'other_payments' => 0,
                    'other_expenses' => 0,
                    'created_by' => Auth::guard('admin')->user()->id,
                ];
                $supplier_service = Transaction::updateOrCreate(['supplier_id' => $product->supplier->id, 'parent_sale_id' => $transaction->id], $transaction_data);
                $keep_supplier_service[] = $supplier_service->id;

                $supplier_service_line = $this->createOrUpdateSupplierAddStockLine($supplier_service, $sell_line->product->purchase_price, $sell_line->product_id, $sell_line->variation_id, $sell_line->quantity, $sell_line->quantity * $sell_line->product->purchase_price);

                $keep_supplier_service_lines[] = $supplier_service_line->id;
            }

            if ($sell_line->product->buy_from_supplier) {
                foreach ($sell_line->variation->raw_materials as $consumption_product) {
                    $product = $consumption_product->raw_material;
                    if (!empty($product->supplier->id)) {
                        $transaction_data = [
                            'store_id' => $transaction->store_id,
                            'supplier_id' => $product->supplier->id,
                            'type' => 'supplier_service',
                            'status' => 'final',
                            'paying_currency_id' => $default_currency,
                            'exchange_rate' => 1,
                            'order_date' => !empty($transaction) ? $transaction->transaction_date : Carbon::now(),
                            'transaction_date' => !empty($transaction->transaction_date) ? $transaction->transaction_date : Carbon::now(),
                            'payment_status' => 'pending',
                            'parent_sale_id' => $transaction->id,
                            'grand_total' => $sell_line->quantity * $consumption_product->amount_used * $sell_line->product->purchase_price,
                            'final_total' => $sell_line->quantity * $consumption_product->amount_used * $sell_line->product->purchase_price,
                            'discount_amount' => 0,
                            'other_payments' => 0,
                            'other_expenses' => 0,
                            'created_by' => Auth::guard('admin')->user()->id,
                        ];
                        $supplier_service = Transaction::updateOrCreate(['supplier_id' => $product->supplier->id, 'parent_sale_id' => $transaction->id], $transaction_data);
                        $keep_supplier_service[] = $supplier_service->id;

                        $sub_total = $sell_line->quantity * $consumption_product->amount_used * $consumption_product->raw_material->purchase_price;
                        $quantity = $sell_line->quantity * $consumption_product->amount_used;
                        $supplier_service_line = $this->createOrUpdateSupplierAddStockLine($supplier_service, $consumption_product->raw_material->purchase_price, $consumption_product->raw_material_id, $consumption_product->variation_id, $quantity, $sub_total);

                        $keep_supplier_service_lines[] = $supplier_service_line->id;
                    }
                }
            }
        }

        $supplier_services = Transaction::where('parent_sale_id', $transaction->id)->where('type', 'supplier_service')->get();
        foreach ($supplier_services as $supplier_service) {
            $final_total = $supplier_service->add_stock_lines->sum('sub_total');
            $supplier_service->final_total = $final_total;
            $supplier_service->grand_total = $final_total;
            $supplier_service->save();
            $this->updateTransactionPaymentStatus($supplier_service->id);
        }

        if (!empty($keep_supplier_service_lines)) {
            AddStockLine::whereIn('transaction_id', $keep_supplier_service)->whereNotIn('id', $keep_supplier_service_lines)->delete();
        }
    }

    /**
     * create or update transaction supplier service
     *
     * @param object $transaction
     * @param double $purchase_price
     * @param int $product_id
     * @param int $variation_id
     * @param double $quanity
     * @param double $sub_total
     * @return object
     */
    public function createOrUpdateSupplierAddStockLine($transaction, $purchase_price, $product_id, $variation_id, $quantity, $sub_total)
    {
        $supplier_service_line = AddStockLine::updateOrCreate(
            [
                'transaction_id' => $transaction->id,
                'product_id' =>  $product_id,
                'variation_id' => $variation_id
            ],
            [
                'product_id' =>  $product_id,
                'variation_id' => $variation_id,
                'quantity' => $quantity,
                'quantity_sold' => $quantity,
                'purchase_price' => $purchase_price,
                'sub_total' => $sub_total
            ]
        );

        return $supplier_service_line;
    }


    /**
     * create or update transaction supplier service
     *
     * @param object $transaction
     * @param object $request
     * @return void
     */
    public function createOrUpdateTransactionCommissionedEmployee($transaction, $request)
    {
        $commissioned_employees = $transaction->commissioned_employees;
        $default_currency = System::getProperty('currency');
        $keep_employee_commission = [];
        $sell_product_ids = $transaction->transaction_sell_lines->pluck('product_id')->toArray();
        $employee_count = count($commissioned_employees);
        foreach ($commissioned_employees as $commissioned_employee) {
            $employee = Employee::find($commissioned_employee);
            $commissioned_products = $employee->commissioned_products;
            $commission_total = 0;
            $commission_valid = $this->checkEmployeeComissionIsValid($transaction, $employee);
            if (!empty($commission_valid)) {
                foreach ($commissioned_products as $commissioned_product) {
                    if (in_array($commissioned_product, $sell_product_ids)) {
                        $sell_line = TransactionSellLine::where('transaction_id', $transaction->id)->where('product_id', $commissioned_product)->first();
                        if ($employee->commission_type == 'sales') {
                            $commission_total += $sell_line->sub_total * ($employee->commission_value / 100);
                        }
                        if ($employee->commission_type == 'profit') {
                            $profit = $sell_line->sub_total - ($sell_line->product->purchase_price * $sell_line->quantity);
                            $commission_total += $profit * ($employee->commission_value / 100);
                        }
                    }
                }

                if ($request->shared_commission) {
                    $commission_total = $commission_total / $employee_count;
                }

                if ($commission_total > 0) {
                    $transaction_data = [
                        'store_id' => $transaction->store_id,
                        'employee_id' => $employee->id,
                        'type' => 'employee_commission',
                        'status' => 'final',
                        'paying_currency_id' => $transaction->received_currency_id,
                        'exchange_rate' => 1,
                        'transaction_date' => !empty($transaction->transaction_date) ? $transaction->transaction_date : Carbon::now(),
                        'payment_status' => 'pending',
                        'parent_sale_id' => $transaction->id,
                        'grand_total' => $commission_total,
                        'final_total' => $commission_total,
                        'created_by' => Auth::guard('admin')->user()->id,
                    ];

                    $employee_commission = Transaction::updateOrCreate(['employee_id' => $employee->id, 'parent_sale_id' => $transaction->id], $transaction_data);
                    $keep_employee_commission[] = $employee_commission->id;
                }
            }

            if (!empty($keep_employee_commission)) {
                Transaction::where('parent_sale_id', $transaction->id)->whereNotIn('id', $keep_employee_commission)->delete();
            }
        }

        return true;
    }

    /**
     * check if employee is valid for comission
     *
     * @param object $transaction
     * @param object $employee_id
     * @return boolean
     */
    public function checkEmployeeComissionIsValid($transaction, $employee)
    {
        $valid = true;
        if (!empty($employee->commission_customer_types)) {
            $customer = $transaction->customer;
            if (!in_array($customer->customer_type_id, $employee->commission_customer_types)) {
                $valid = false;
            }
        }
        if (!empty($employee->commission_stores)) {
            if (!in_array($transaction->store_id, $employee->commission_stores)) {
                $valid = false;
            }
        }
        if (empty($employee->commission) || empty($employee->commission_value)) {
            $valid = false;
        }

        return $valid;
    }


    /**
     * pay employee commission payments
     *
     * @param object $transaction
     * @param object $employee_id
     * @return boolean
     */
    public function payAtOnceEmployeeCommission($parent_payment, $employee_id)
    {
        $due_transactions = Transaction::where('employee_id', $employee_id)
            ->whereIn('type', ['employee_commission'])
            ->whereIn('status', ['final'])
            ->where('payment_status', '!=', 'paid')
            ->orderBy('transaction_date', 'asc')
            ->get();

        $default_currency_id = System::getProperty('currency');
        $total_amount = $parent_payment->amount;
        $tranaction_payments = [];
        if ($due_transactions->count()) {
            foreach ($due_transactions as $transaction) {
                //If transaction check status is final
                if ($transaction->type == 'employee_commission' && $transaction->status != 'final') {
                    continue;
                }

                if ($total_amount > 0) {
                    $total_paid = $this->getTotalPaid($transaction->id);
                    $due = $transaction->final_total - $total_paid;

                    $now = Carbon::now()->toDateTimeString();

                    $array =  [
                        'transaction_id' =>  $transaction->id,
                        'amount' => $this->num_uf($parent_payment->amount),
                        'payment_for' => $transaction->customer_id,
                        'method' => $parent_payment->method,
                        'paid_on' => $parent_payment->paid_on,
                        'ref_number' => $parent_payment->ref_number,
                        'bank_deposit_date' => !empty($data['bank_deposit_date']) ? $this->uf_date($data['bank_deposit_date']) : null,
                        'bank_name' => $parent_payment->bank_name,
                        'card_number' => $parent_payment->card_number,
                        'card_month' => $parent_payment->card_month,
                        'card_year' => $parent_payment->card_year,
                        'parent_id' => $parent_payment->id,
                        'created_by' => Auth::guard('admin')->user()->id,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];

                    $due_base = $due;

                    if (!empty($transaction->paying_currency_id)) {
                        if ($transaction->paying_currency_id != $default_currency_id) {
                            $due_base = $this->convertCurrencyAmount($due, $transaction->paying_currency_id, $default_currency_id);
                        }
                    }
                    if ($due_base <= $total_amount) {
                        $array['amount'] = $due;
                        $tranaction_payments[] = $array;

                        //Update transaction status to paid
                        $transaction->payment_status = 'paid';
                        $transaction->save();

                        $total_amount = $total_amount - $due_base;
                    } else {
                        if (!empty($transaction->paying_currency_id)) {
                            if ($transaction->paying_currency_id != $default_currency_id) {
                                $total_amount = $this->convertCurrencyAmount($total_amount, $default_currency_id, $transaction->paying_currency_id);
                            }
                        }
                        $array['amount'] = $total_amount;
                        $tranaction_payments[] = $array;

                        //Update transaction status to partial
                        $transaction->payment_status = 'partial';
                        $transaction->save();
                        $total_amount = 0;
                        break;
                    }
                }
            }

            //Insert new transaction payments
            if (!empty($tranaction_payments)) {
                TransactionPayment::insert($tranaction_payments);
            }
        }

        return $total_amount;
    }

    /**
     * calculate employee commission
     *
     * @param int $employee_id
     * @return double
     */
    public function calculateEmployeeCommission($employee_id, $start_date = null, $end_date = null)
    {
        $employee = Employee::find($employee_id);
        $query = Transaction::leftjoin('transaction_payments', 'transactions.id', '=', 'transaction_payments.transaction_id');
        if (!empty($start_date)) {
            $query->whereDate('transaction_date', '>=', $this->commonUtil->uf_date($start_date));
        }
        if (!empty($end_date)) {
            $query->whereDate('transaction_date', '<=', $this->commonUtil->uf_date($end_date));
        }
        $query->where('payment_status', '!=', 'paid')
            ->where('type', 'employee_commission')
            ->where('employee_id', $employee_id);
        $sale_transactions = $query->select(
            'transactions.final_total',
            'transactions.id',
            'transactions.paying_currency_id'
        )
            ->get();

        $amount = 0;
        $default_currency_id = System::getProperty('currency');
        if ($employee->commission == 1) {
            if (!empty($sale_transactions) && $sale_transactions->count() > 0) {
                foreach ($sale_transactions as $transaction) {
                    $final_total = $transaction->final_total - $this->getTotalPaid($transaction->id);
                    if (!empty($transaction->paying_currency_id)) {
                        if ($transaction->paying_currency_id != $default_currency_id) {
                            $amount += $this->convertCurrencyAmount($final_total, $transaction->paying_currency_id, $default_currency_id);
                        } else {
                            $amount += $final_total;
                        }
                    } else {
                        $amount += $final_total;
                    }
                }
            }
        }

        return $amount;
    }
    /**
     * calculate employee commission
     *
     * @param int $employee_id
     * @return array
     */
    public function calculateEmployeeCommissionPayments($employee_id, $start_date = null, $end_date = null)
    {
        $employee = Employee::find($employee_id);
        $query = Transaction::leftjoin('transaction_payments', 'transactions.id', '=', 'transaction_payments.transaction_id');
        if (!empty($start_date)) {
            $query->whereDate('transaction_date', '>=', $this->commonUtil->uf_date($start_date));
        }
        if (!empty($end_date)) {
            $query->whereDate('transaction_date', '<=', $this->commonUtil->uf_date($end_date));
        }
        $query->where('payment_status', '!=', 'paid')
            ->where('type', 'employee_commission')
            ->where('employee_id', $employee_id);
        $sale_transactions = $query->select(
            'transactions.final_total',
            'transactions.id',
            'transactions.paying_currency_id'
        )
            ->get();

        $total_amount = 0;
        $total_due = 0;
        $total_paid = 0;
        $default_currency_id = System::getProperty('currency');
        if ($employee->commission == 1) {
            if (!empty($sale_transactions) && $sale_transactions->count() > 0) {
                foreach ($sale_transactions as $transaction) {
                    $final_total = $transaction->final_total;
                    $due = $transaction->final_total - $this->getTotalPaid($transaction->id);
                    $paid = $this->getTotalPaid($transaction->id);
                    if (!empty($transaction->paying_currency_id)) {
                        if ($transaction->paying_currency_id != $default_currency_id) {
                            $total_amount += $this->convertCurrencyAmount($final_total, $transaction->paying_currency_id, $default_currency_id);
                            $total_due += $this->convertCurrencyAmount($due, $transaction->paying_currency_id, $default_currency_id);
                            $total_paid += $this->convertCurrencyAmount($paid, $transaction->paying_currency_id, $default_currency_id);
                        } else {
                            $total_amount += $final_total;
                            $total_due += $due;
                            $total_paid += $paid;
                        }
                    } else {
                        $total_amount += $final_total;
                        $total_due += $due;
                        $total_paid += $paid;
                    }
                }
            }
        }

        return ['commission' => $total_amount, 'total_due' => $total_due, 'total_paid' => $total_paid];
    }
    /**
     * update the employee commission payments
     *
     * @param object $transaction
     * @param object $employee_id
     * @return boolean
     */
    public function updatePayAtOnceEmployeeCommission($parent_payment, $employee_id, $old_amount_paid)
    {
        $due_transactions = Transaction::where('employee_id', $employee_id)
            ->whereIn('type', ['employee_commission'])
            ->whereIn('status', ['final'])
            ->where('payment_status', '!=', 'paid')
            ->orderBy('transaction_date', 'asc')
            ->get();

        if (!empty($old_amount_paid)) {
            $total_amount = $parent_payment->amount - $old_amount_paid;
        } else {
            $total_amount = $parent_payment->amount;
        }
        $default_currency_id = System::getProperty('currency');
        $tranaction_payments = [];
        if ($total_amount > 0) {
            if ($due_transactions->count()) {
                foreach ($due_transactions as $transaction) {
                    //If transaction check status is final
                    if ($transaction->type == 'employee_commission' && $transaction->status != 'final') {
                        continue;
                    }

                    if ($total_amount > 0) {
                        $total_paid = $this->getTotalPaid($transaction->id);
                        $due = $transaction->final_total - $total_paid;

                        $now = Carbon::now()->toDateTimeString();

                        $array =  [
                            'transaction_id' =>  $transaction->id,
                            'amount' => $this->num_uf($parent_payment->amount),
                            'payment_for' => $transaction->customer_id,
                            'method' => $parent_payment->method,
                            'paid_on' => $parent_payment->paid_on,
                            'ref_number' => $parent_payment->ref_number,
                            'bank_deposit_date' => !empty($data['bank_deposit_date']) ? $this->uf_date($data['bank_deposit_date']) : null,
                            'bank_name' => $parent_payment->bank_name,
                            'card_number' => $parent_payment->card_number,
                            'card_month' => $parent_payment->card_month,
                            'card_year' => $parent_payment->card_year,
                            'parent_id' => $parent_payment->id,
                            'created_by' => Auth::guard('admin')->user()->id,
                            'created_at' => $now,
                            'updated_at' => $now
                        ];

                        $due_base = $due;
                        if (!empty($transaction->paying_currency_id)) {
                            if ($transaction->paying_currency_id != $default_currency_id) {
                                $due_base = $this->convertCurrencyAmount($due, $transaction->paying_currency_id, $default_currency_id);
                            }
                        }

                        if ($due_base <= $total_amount) {
                            $array['amount'] = $due;
                            $tranaction_payments[] = $array;

                            //Update transaction status to paid
                            $transaction->payment_status = 'paid';
                            $transaction->save();

                            $total_amount = $total_amount - $due;
                        } else {
                            if (!empty($transaction->paying_currency_id)) {
                                if ($transaction->paying_currency_id != $default_currency_id) {
                                    $total_amount = $this->convertCurrencyAmount($due, $default_currency_id, $transaction->paying_currency_id);
                                }
                            }
                            $array['amount'] = $total_amount;
                            $tranaction_payments[] = $array;

                            //Update transaction status to partial
                            $transaction->payment_status = 'partial';
                            $transaction->save();
                            $total_amount = 0;
                            break;
                        }
                    }
                }

                //Insert new transaction payments
                if (!empty($tranaction_payments)) {
                    TransactionPayment::insert($tranaction_payments);
                }
            }
        }
        if ($total_amount < 0) {
            $total_amount = abs($total_amount);
            $paid_transactions = Transaction::where('employee_id', $employee_id)
                ->whereIn('type', ['employee_commission'])
                ->whereIn('status', ['final'])
                ->where('payment_status', '!=', 'pending')
                ->orderBy('transaction_date', 'asc')
                ->get();

            foreach ($paid_transactions as $transaction) {
                if ($total_amount > 0) {
                    $tp = TransactionPayment::where('transaction_id', $transaction->id)->first();
                    $paid = $tp->amount;

                    $paid_base = $paid;
                    if (!empty($transaction->paying_currency_id)) {
                        if ($transaction->paying_currency_id != $default_currency_id) {
                            $paid_base = $this->convertCurrencyAmount($paid, $transaction->paying_currency_id, $default_currency_id);
                        }
                    }

                    if ($paid_base <= $total_amount) {
                        $tp->amount = 0;
                        $tp->save();

                        //Update transaction status to pending
                        $transaction->payment_status = 'pending';
                        $transaction->save();

                        $total_amount = $total_amount - $paid_base;
                    } else {
                        if (!empty($transaction->paying_currency_id)) {
                            if ($transaction->paying_currency_id != $default_currency_id) {
                                $total_amount = $this->convertCurrencyAmount($total_amount, $default_currency_id, $transaction->paying_currency_id);
                            }
                        }
                        $tp->amount =  $tp->amount - $total_amount;
                        $tp->save();

                        //Update transaction status to partial
                        $transaction->payment_status = 'partial';
                        $transaction->save();
                        $total_amount = 0;
                        break;
                    }
                }
            }
        }

        return $total_amount;
    }



    /**
     * deduct commission for employee
     *
     * @return void
     */
    public function deductCommissionForEmployee($transaction)
    {
        $commissioned_employees = $transaction->commissioned_employees;
        $sell_product_ids = $transaction->transaction_sell_lines->pluck('product_id')->toArray();
        $employee_count = count($commissioned_employees);
        foreach ($commissioned_employees as $commissioned_employee) {
            $employee = Employee::find($commissioned_employee);
            $commissioned_products = $employee->commissioned_products;
            $commission_total = 0;
            if (!empty($employee->commission)) {
                foreach ($commissioned_products as $commissioned_product) {
                    if (in_array($commissioned_product, $sell_product_ids)) {
                        $sell_line = TransactionSellLine::where('transaction_id', $transaction->id)->where('quantity_returned', '>', 0)->where('product_id', $commissioned_product)->first();
                        if (!empty($sell_line)) {
                            if ($employee->commission_type == 'sales') {
                                $total_amount = $sell_line->sub_total * ($employee->commission_value / 100);
                                $commission_total += ($total_amount / $sell_line->quantity) * $sell_line->quantity_returned;
                            }
                            if ($employee->commission_type == 'profit') {
                                $profit = $sell_line->sub_total - ($sell_line->product->purchase_price * $sell_line->quantity);
                                $total_amount = $profit * ($employee->commission_value / 100);
                                $commission_total += ($total_amount / $sell_line->quantity) * $sell_line->quantity_returned;
                            }
                        }
                    }
                }
            }
            if ($transaction->shared_commission) {
                $commission_total = $commission_total / $employee_count;
            }

            if ($commission_total > 0) {
                $employee_commission = Transaction::where('parent_sale_id', $transaction->id)->where('employee_id', $employee->id)->where('type', 'employee_commission')->first();
                $commission_value = $employee_commission->grand_total - $commission_total;  //deduct commission for returned products_ value
                $employee_commission->update(['final_total' => $commission_value, 'grand_total' => $commission_value]);
            }
        }
    }






    public function calculateDiscountAmount($amount, $type, $value)
    {
        if ($type == 'fixed') {
            return $value;
        }
        if ($type == 'percentage') {
            return ($amount * $value) / 100;
        }
    }

    public function calculateTaxAmount($amount, $tax_id)
    {
        $tax = Tax::find($tax_id);

        return ($amount * $tax->rate) / 100;
    }

    /**
     * change filter values by user access
     *
     * @param Request $request
     * @return array
     */
    public function getFilterOptionValues($request)
    {

        $data['store_id'] = null;
        $data['pos_id'] = null;
        if (!empty($request->store_id)) {
            $data['store_id'] = $request->store_id;
        }
        if (!empty($request->pos_id)) {
            $data['pos_id'] = $request->pos_id;
        }
        $employee = Employee::where('admin_id', auth()->user()->id)->first();
        if(in_array($data['store_id'],(array) $employee->store_id)){
            $data['store_id'] =$data['store_id'];
        }


        return $data;
    }

    /**
     * update sell transaction
     *
     * @param Request $request
     * @param int $id
     * @return object
     */
    public function updateSellTransaction($request, $id)
    {
        $transaction_data = [
            'exchange_rate' => !empty($request->exchange_rate) ? $request->exchange_rate : 1,
            'default_currency_id' => $request->default_currency_id,
            'received_currency_id' => $request->received_currency_id,
            'customer_id' => $request->customer_id,
            'final_total' => $this->num_uf($request->final_total),
            'grand_total' => $this->num_uf($request->grand_total),
            'is_direct_sale' => !empty($request->is_direct_sale) ? 1 : 0,
            'status' => $request->status,
            'discount_type' => $request->discount_type,
            'discount_value' => $this->num_uf($request->discount_value),
            'discount_amount' => $this->num_uf($request->discount_amount),
            'total_sp_discount' => $this->num_uf($request->total_sp_discount),
            'block_qty' => 0,
            'block_for_days' => 0,
            'tax_id' => $request->tax_id_hidden ?? null,
            'tax_method' => $request->tax_method ?? null,
            'total_tax' => $this->num_uf($request->total_tax),
            'total_item_tax' => $this->num_uf($request->total_item_tax),
            'sale_note' => $request->sale_note,
            'staff_note' => $request->staff_note,
            'fabric_name' => $request->fabric_name ?? null,
            'fabric_squatch' => $request->fabric_squatch ?? null,
            'prova_datetime' => $request->prova_datetime ?? null,
            'terms_and_condition_id' => !empty($request->terms_and_condition_id) ? $request->terms_and_condition_id : null,
            'service_fee_id' => !empty($request->service_fee_id_hidden) ? $request->service_fee_id_hidden : null,
            'service_fee_rate' => !empty($request->service_fee_rate) ? $this->num_uf($request->service_fee_rate) : null,
            'service_fee_value' => !empty($request->service_fee_value) ? $this->num_uf($request->service_fee_value) : null,
            'commissioned_employees' => !empty($request->commissioned_employees) ? $request->commissioned_employees : [],
            'shared_commission' => !empty($request->shared_commission) ? 1 : 0,
        ];


        if (!empty($request->transaction_date)) {
            $transaction_data['transaction_date'] = Carbon::createFromTimestamp(strtotime($request->transaction_date))->format('Y-m-d H:i:s');
        }

        $transaction = Transaction::find($id);
        if ($transaction->is_quotation && $transaction->status == 'draft') {
            $transaction_data['ref_no'] = $transaction->invoice_no;
        }
        if ($transaction->status == 'final' && empty($transaction->invoice_no)) {
            $transaction_data['invoice_no'] = $this->productUtil->getNumberByType('sell');
        }
        if ($transaction->status == 'draft' && $request->status == 'final') {
            $transaction_data['transaction_date'] = Carbon::now();
            if (empty($transaction->invoice_no)) {
                $transaction_data['invoice_no'] = $this->productUtil->getNumberByType('sell');
            }
            if ($transaction->is_quotation) {
                $transaction_data['invoice_no'] = $this->productUtil->getNumberByType('sell');
            }
        }
        $transaction_status = $transaction->status;
        $is_block_qty = $transaction->block_qty;
        $transaction->update($transaction_data);

        $keep_sell_lines = [];
        foreach ($request->transaction_sell_line as $line) {
            $old_qty = 0;
            if (!empty($line['transaction_sell_line_id'])) {
                $transaction_sell_line = TransactionSellLine::find($line['transaction_sell_line_id']);
                $transaction_sell_line->product_id = $line['product_id'];
                if ($transaction_status == 'draft') {
                    $old_qty = 0;
                } else {
                    $old_qty = $transaction_sell_line->quantity;
                }
                $transaction_sell_line->promotion_discount = !empty($line['promotion_discount']) ? $this->num_uf($line['promotion_discount']) : 0;
                $transaction_sell_line->promotion_discount_type = !empty($line['promotion_discount_type']) ? $line['promotion_discount_type'] : null;
                $transaction_sell_line->promotion_discount_amount = !empty($line['promotion_discount_amount']) ? $this->num_uf($line['promotion_discount_amount']) : 0;
                $transaction_sell_line->product_discount_value = !empty($line['product_discount_value']) ? $this->num_uf($line['product_discount_value']) : 0;
                $transaction_sell_line->product_discount_type = !empty($line['product_discount_type']) ? $line['product_discount_type'] : null;
                $transaction_sell_line->product_discount_amount = !empty($line['product_discount_amount']) ? $this->num_uf($line['product_discount_amount']) : 0;
                $transaction_sell_line->quantity = $this->num_uf($line['quantity']);
                $transaction_sell_line->purchase_price = $this->num_uf($line['purchase_price']);
                $transaction_sell_line->sell_price = $this->num_uf($line['sell_price']);
                $transaction_sell_line->sub_total = $this->num_uf($line['sub_total']);
                $transaction_sell_line->tax_id = !empty($line['tax_id']) ? $line['tax_id'] : null;
                $transaction_sell_line->tax_rate = !empty($line['tax_rate']) ? $this->num_uf($line['tax_rate']) : 0;
                $transaction_sell_line->item_tax = !empty($line['item_tax']) ? $this->num_uf($line['item_tax']) : 0;
                $transaction_sell_line->save();
                $qty =  $this->num_uf($line['quantity']);
                $keep_sell_lines[] = $line['transaction_sell_line_id'];
                $product = Product::find($line['product_id']);
                if (!$product->is_service) {
                    $this->productUtil->decreaseProductQuantity($line['product_id'], $transaction->store_id, $qty, $old_qty);
                }
                if ($is_block_qty && !$product->is_service) {
                    $block_qty = $transaction_sell_line->quantity;
                    $this->productUtil->updateBlockQuantity($line['product_id'], $transaction->store_id, $block_qty, 'subtract');
                }
            } else {
                $transaction_sell_line = new TransactionSellLine();
                $transaction_sell_line->transaction_id = $transaction->id;
                $transaction_sell_line->product_id = $line['product_id'];
                $transaction_sell_line->promotion_discount = !empty($line['promotion_discount']) ? $this->num_uf($line['promotion_discount']) : 0;
                $transaction_sell_line->promotion_discount_type = !empty($line['promotion_discount_type']) ? $line['promotion_discount_type'] : null;
                $transaction_sell_line->promotion_discount_amount = !empty($line['promotion_discount_amount']) ? $this->num_uf($line['promotion_discount_amount']) : 0;
                $transaction_sell_line->product_discount_value = !empty($line['product_discount_value']) ? $this->num_uf($line['product_discount_value']) : 0;
                $transaction_sell_line->product_discount_type = !empty($line['product_discount_type']) ? $line['product_discount_type'] : null;
                $transaction_sell_line->product_discount_amount = !empty($line['product_discount_amount']) ? $this->num_uf($line['product_discount_amount']) : 0;
                $transaction_sell_line->quantity = $this->num_uf($line['quantity']);
                $transaction_sell_line->purchase_price = $this->num_uf($line['purchase_price']);
                $transaction_sell_line->sell_price = $this->num_uf($line['sell_price']);
                $transaction_sell_line->sub_total = $this->num_uf($line['sub_total']);
                $transaction_sell_line->tax_id = !empty($line['tax_id']) ? $line['tax_id'] : null;
                $transaction_sell_line->tax_rate = !empty($line['tax_rate']) ? $this->num_uf($line['tax_rate']) : 0;
                $transaction_sell_line->item_tax = !empty($line['item_tax']) ? $this->num_uf($line['item_tax']) : 0;
                $transaction_sell_line->save();
                $qty =  $this->num_uf($line['quantity']);
                $keep_sell_lines[] = $transaction_sell_line->id;
                $product = Product::find($line['product_id']);
                if (!$product->is_service) {
                    $this->productUtil->decreaseProductQuantity($line['product_id'],  $transaction->store_id, $qty);
                }
                if ($transaction->block_qty && !$product->is_service) {
                    $block_qty = $transaction_sell_line->quantity;
                    $this->productUtil->updateBlockQuantity($line['product_id'],$transaction->store_id, $block_qty, 'subtract');
                }
            }

            $this->updateSoldQuantityInAddStockLine($transaction_sell_line->product_id, $transaction->store_id, $line['quantity'], $old_qty);
        }

        //update stock for deleted lines
        $deleted_lines = TransactionSellLine::where('transaction_id', $transaction->id)->whereNotIn('id', $keep_sell_lines)->get();
        foreach ($deleted_lines as $deleted_line) {
            if ($transaction_status != 'draft') {
                $product = Product::find($deleted_line->product_id);
                if (!$product->is_service) {
                    $this->productUtil->updateProductQuantityStore($deleted_line->product_id, $transaction->store_id, $deleted_line->quantity);
                }
            }
            if($deleted_line->is_lens){
                Prescription::where('sell_line_id',$deleted_line->id)->delete();
            }
            $deleted_line->delete();
        }

        $this->updateTransactionPaymentStatus($transaction->id);

        return $transaction;
    }

    /**
     * get invoice print html
     *
     * @param object $transaction
     * @param array $payment_types
     * @return string
     */
    public function getInvoicePrint($transaction, $payment_types, $transaction_invoice_lang = null,$last_due=null)
    {
        $print_gift_invoice = request()->print_gift_invoice;

        if (!empty($transaction_invoice_lang)) {
            $invoice_lang = $transaction_invoice_lang;
        } else {
            $invoice_lang = System::getProperty('invoice_lang');
            if (empty($invoice_lang)) {
                $invoice_lang = request()->session()->get('language');
            }
        }
       $total_due= $this->getCustomerBalance($transaction->customer_id)['balance'];

       $font='15px';
       $line_height1='20px';
       $line_height2='24px';
       $data_font='12px';
       $font_size_at_invoice=System::getProperty('font_size_at_invoice');
        if (!empty($font_size_at_invoice)){
            if($font_size_at_invoice == 'max'){
                $font='15px';
                $data_font='12px';
                $line_height1='20px';
                $line_height2='24px';
            }else if($font_size_at_invoice == 'min'){
                $font='10px';
                $data_font='7px';
                $line_height1='10px';
                $line_height2='17px';
            }else if($font_size_at_invoice == 'avg'){
                $font='13px';
                $data_font='10px';
                $line_height1='15px';
                $line_height2='21px';

            }else{
                $font='15px';
                $data_font='12px';
                $line_height1='20px';
                $line_height2='24';
            }
        }

        if ($invoice_lang == 'ar_and_en') {
            $html_content = view('sale::back-end.pos.partials.invoice_ar_and_end')->with(compact(
                'transaction',
                'payment_types',
                'print_gift_invoice',
                'total_due',
                'last_due'
            ))->render();
        } else {
            $html_content = view('sale::back-end.pos.partials.invoice')->with(compact(
                'transaction',
                'payment_types',
                'invoice_lang',
                'total_due',
                'print_gift_invoice',
                'font','line_height1','line_height2','data_font','last_due'
            ))->render();
        }

        if ($transaction->is_direct_sale == 1) {
            $sale = $transaction;
            $payment_type_array = $payment_types;
            $html_content = view('sale::back-end.pos.partials.commercial_invoice')->with(compact(
                'sale',
                'payment_type_array',
                'invoice_lang',
                'total_due',
                'print_gift_invoice',
            ))->render();
        }

        if ($transaction->is_quotation == 1 && $transaction->status == 'draft') {
            $sale = $transaction;
            $payment_type_array = $payment_types;
            $html_content = view('sale::back-end.pos.partials.commercial_invoice')->with(compact(
                'sale',
                'payment_type_array',
                'invoice_lang'
            ))->render();
        }

        return $html_content;
    }


    /**
     * calculate the cost of sold products for restaurants
     *
     * @param string $start_date
     * @param string $end_date
     * @param int $store_id
     * @return double
     */
    public function getCostOfSoldProducts($start_date, $end_date, $store_id = [], $store_pos_id = null)
    {
        $query = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', 'transaction_sell_lines.transaction_id')
            ->where('transactions.type', 'sell')
            ->where('transactions.status', 'final');

        if (!empty($start_date)) {
            $query->whereDate('transaction_date', '>=', $start_date);
        }
        if (!empty($end_date)) {
            $query->whereDate('transaction_date',  '<=', $end_date);
        }
        if (!empty(request()->start_time)) {
            $query->where('transaction_date', '>=', request()->start_date . ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
        }
        if (!empty(request()->end_time)) {
            $query->where('transaction_date', '<=', request()->end_date . ' ' . Carbon::parse(request()->end_time)->format('H:i:s'));
        }
        if (!empty($store_id)) {
            $query->whereIn('store_id', $store_id);
        }
        if (!empty($store_pos_id)) {
            $query->where('store_pos_id', $store_pos_id);
        }

        $sales = $query->select(
            DB::raw("SUM(transaction_sell_lines.quantity * transaction_sell_lines.purchase_price) as cost_of_sold_products")
        )->first();

        if (!empty($sales)) {
            return $sales->cost_of_sold_products;
        }
        return 0;
    }
    /**
     * calculate the cost of sold products for restaurants
     *
     * @param string $start_date
     * @param string $end_date
     * @param int $store_id
     * @return double
     */
    public function getCostOfSoldReturnedProducts($start_date, $end_date, $store_id = [], $store_pos_id = null)
    {
        $query = Transaction::leftjoin('transaction_sell_lines', 'transactions.id', 'transaction_sell_lines.transaction_id')
            ->where('transactions.type', 'sell')
            ->where('transactions.status', 'final');

        if (!empty($start_date)) {
            $query->whereDate('transaction_date', '>=', $start_date);
        }
        if (!empty($end_date)) {
            $query->whereDate('transaction_date',  '<=', $end_date);
        }
        if (!empty(request()->start_time)) {
            $query->where('transaction_date', '>=', request()->start_date . ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
        }
        if (!empty(request()->end_time)) {
            $query->where('transaction_date', '<=', request()->end_date . ' ' . Carbon::parse(request()->end_time)->format('H:i:s'));
        }
        if (!empty($store_id)) {
            $query->whereIn('store_id', $store_id);
        }
        if (!empty($store_pos_id)) {
            $query->where('store_pos_id', $store_pos_id);
        }

        $sales = $query->select(
            DB::raw("SUM(transaction_sell_lines.quantity_returned * transaction_sell_lines.purchase_price) as cost_of_sold_returned_products")
        )->first();

        if (!empty($sales)) {
            return $sales->cost_of_sold_returned_products;
        }
        return 0;
    }

    /**
     * calculate the customer balance
     *
     * @param int $customer_id
     * @return void
     */
    public function getCustomerBalance($customer_id)
    {
        $query = Customer::join('transactions as t', 'customers.id', 't.customer_id')
            ->leftjoin('customer_types', 'customers.customer_type_id', 'customer_types.id')
            ->where('customers.id', $customer_id);

        $query->select(
            'customers.total_rp',
            'customers.deposit_balance',
            'customers.added_balance',
            'customers.staff_note',
            DB::raw("SUM(IF(t.type = 'sell_return' AND t.status = 'final', final_total, 0)) as total_return"),
            DB::raw("SUM(IF(t.type = 'sell_return' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as total_return_paid"),
            DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
            DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as total_paid"),
        );
        $customer_details = $query->first();


        $balance_adjustment = CustomerBalanceAdjustment::where('customer_id', $customer_id)->sum('add_new_balance');
        $balance = ($customer_details->total_paid - $customer_details->total_invoice  + $customer_details->total_return - $customer_details->total_return_paid)+ $customer_details->deposit_balance + $customer_details->added_balance + $balance_adjustment;        // print_r( $customer_details->total_return); die();
        return ['balance' => $balance, 'points' => $customer_details->total_rp ,'staff_note'=>$customer_details->staff_note];
    }
    public function getCustomerBalanceExceptTransaction($customer_id,$transaction_id)
    {
        $query = Customer::join('transactions as t', 'customers.id', 't.customer_id')
        ->leftjoin('customer_types', 'customers.customer_type_id', 'customer_types.id')
        ->where('customers.id', $customer_id);
        $query->where('t.id','!=',$transaction_id);
    $query->select(
        'customers.total_rp',
        'customers.deposit_balance',
        'customers.added_balance',
        DB::raw("SUM(IF(t.type = 'sell_return' AND t.status = 'final', final_total, 0)) as total_return"),
        DB::raw("SUM(IF(t.type = 'sell_return' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as total_return_paid"),
        DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
        DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as total_paid"),
    );
    $customer_details = $query->first();


    $balance_adjustment = CustomerBalanceAdjustment::where('customer_id', $customer_id)->sum('add_new_balance');
    $balance = ($customer_details->total_paid - $customer_details->total_invoice  + $customer_details->total_return - $customer_details->total_return_paid)+ $customer_details->deposit_balance + $customer_details->added_balance + $balance_adjustment;        // print_r( $customer_details->total_return); die();
    return ['balance' => $balance, 'points' => $customer_details->total_rp];
    }

    /**
     * pay customer due
     *
     * @param object $request
     * @param boolean $format_data
     * @return void
     */
    public function payCustomer($request, $format_data = true)
    {
        $customer_id = $request->input('customer_id');
        $inputs = $request->only([
            'amount', 'method', 'note', 'card_number', 'card_month', 'card_year',
            'cheque_number', 'bank_name', 'bank_deposit_date', 'ref_number', 'paid_on'
        ]);

        if ($format_data) {
            $inputs['paid_on'] = $this->uf_date($inputs['paid_on']) . ' ' . date('H:i:s');
            $inputs['amount'] = $this->num_uf($inputs['amount']);
        }


        $inputs['payment_for'] = $customer_id;
        $inputs['created_by'] = auth()->user()->id;

        $parent_payment = TransactionPayment::create($inputs);
        $customer = Customer::findOrFail($customer_id);


        //Distribute above payment among unpaid transactions
        $excess_amount = $this->payAtOnce($parent_payment, $customer_id);
        //Update excess amount
        if (!empty($excess_amount)) {
            $this->updateCustomerBalance($customer, $excess_amount);
        }

        if ($request->upload_documents) {
            foreach ($request->file('upload_documents', []) as $key => $doc) {
                $parent_payment->addMedia($doc)->toMediaCollection('transaction_payment');
            }
        }

        return $parent_payment;
    }

    /**
     * Pay Customer due at once
     *
     * @param obj $parent_payment, string $type
     *
     * @return void
     */
    public function payAtOnce($parent_payment, $customer_id)
    {

        $due_transactions = Transaction::where('customer_id', $customer_id)
            ->whereIn('type', ['sell'])
            ->whereIn('status', ['final'])
            ->where('payment_status', '!=', 'paid')
            ->orderBy('transaction_date', 'asc')
            ->get();

        $total_amount = $parent_payment->amount;
        $tranaction_payments = [];
        if ($due_transactions->count()) {
            foreach ($due_transactions as $transaction) {
                //If sell check status is final
                if ($transaction->type == 'sell' && $transaction->status != 'final') {
                    continue;
                }

                if ($total_amount > 0) {
                    $total_paid = $this->getTotalPaid($transaction->id);
                    $due = $transaction->final_total - $total_paid;

                    $now = Carbon::now()->toDateTimeString();

                    $array =  [
                        'transaction_id' =>  $transaction->id,
                        'amount' => $this->num_uf($parent_payment->amount),
                        'payment_for' => $transaction->customer_id,
                        'method' => $parent_payment->method,
                        'paid_on' => $parent_payment->paid_on,
                        'ref_number' => $parent_payment->ref_number,
                        'bank_deposit_date' => !empty($data['bank_deposit_date']) ? $this->uf_date($data['bank_deposit_date']) : null,
                        'bank_name' => $parent_payment->bank_name,
                        'card_number' => $parent_payment->card_number,
                        'card_month' => $parent_payment->card_month,
                        'card_year' => $parent_payment->card_year,
                        'parent_id' => $parent_payment->id,
                        'created_by' => Auth::guard('admin')->user()->id,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];

                    if ($due <= $total_amount) {
                        $array['amount'] = $due;
                        $tranaction_payments[] = $array;

                        //Update transaction status to paid
                        $transaction->payment_status = 'paid';
                        $transaction->save();

                        $total_amount = $total_amount - $due;
                    } else {
                        $array['amount'] = $total_amount;
                        $tranaction_payments[] = $array;

                        //Update transaction status to partial
                        $transaction->payment_status = 'partial';
                        $transaction->save();
                        $total_amount = 0;
                        break;
                    }
                }
            }

            //Insert new transaction payments
            if (!empty($tranaction_payments)) {
                TransactionPayment::insert($tranaction_payments);
            }
        }

        return $total_amount;
    }

    /**
     * Get total paid amount for a transaction
     *
     * @param int $transaction_id
     *
     * @return int
     */
    public function getTotalPaid($transaction_id)
    {
        $total_paid = TransactionPayment::where('transaction_id', $transaction_id)
            ->select(DB::raw('SUM(IF( is_return = 0, amount, amount*-1))as total_paid'))
            ->first()
            ->total_paid;

        return $total_paid;
    }

    /**
     * Updates customer balance
     * @param obj $customer
     * @param float $amount
     * @param string $type [add, deduct]
     *
     * @return obj $recurring_invoice
     */
    function updateCustomerBalance($customer, $amount, $type = 'add')
    {
        if (!is_object($customer)) {
            $customer = Customer::findOrFail($customer);
        }

        if ($type == 'add') {
            $customer->deposit_balance += $amount;
        } elseif ($type == 'deduct') {
            $customer->deposit_balance -= $amount;
        }
        $customer->save();
    }

    /**
     * generate return invoice no from sell transaction invoice number
     *
     * @param string $transaction_invoice_number
     * @return string
     */
    public function createReturnTransactionInvoiceNoFromInvoice($transaction_invoice_number)
    {
        $number_only = substr($transaction_invoice_number, 3);

        return 'Rets' . $number_only;
    }


    /**
     * create of update the transaction customersize
     *
     * @param object $transaction
     * @param array $customer_size_details
     * @return double
     */
    public function createOrUpdateConsumptionDetail($consumption_detail_array,  $consumption_id)
    {
        $consumption = Consumption::find($consumption_id);
        $raw_material = Product::find($consumption->raw_material_id);
        $keep_details = [];
        $total_quantity = 0;
        $old_qty = 0;

        foreach ($consumption_detail_array as $consumption_detail_data) {
            $id = $consumption_detail_data['id'] ?? null;

            $old_qty = 0;
            $consumption_detail_exist = ConsumptionDetail::where(['consumption_id' => $consumption->id, 'variation_id' => $consumption_detail_data['variation_id']])->first();
            if (!empty($consumption_detail_exist)) {
                $old_qty = $consumption_detail_exist->quantity;
            }

            $consumption_detail = ConsumptionDetail::firstOrNew(['consumption_id' => $id]);

            $variation = Variation::find($consumption_detail_data['variation_id']);
            $consumption_detail->consumption_id = $consumption_id;
            $consumption_detail->product_id = $variation->product_id;
            $consumption_detail->variation_id = $consumption_detail_data['variation_id'];
            $consumption_detail->unit_id = $consumption_detail_data['unit_id'];
            $consumption_detail->quantity = $this->num_uf($consumption_detail_data['quantity']);

            $consumption_detail->save();

            $raw_material_unit = Unit::find($raw_material->units->pluck('id')[0]);
            $consumption_unit = Unit::find($consumption_detail_data['unit_id']);
            $base_unit_multiplier = 1;
            if ($raw_material_unit->id != $consumption_unit->id) {
                $base_unit_multiplier = $consumption_unit->base_unit_multiplier;
            }
            $total_quantity += $consumption_detail->quantity * $base_unit_multiplier;
            $old_qty = $old_qty * $base_unit_multiplier;
            $keep_details[] = $consumption_detail->id;
        }

        ConsumptionDetail::where('consumption_id', $consumption_id)->whereNotIn('id', $keep_details)->delete();


        return ['total_quantity' => $total_quantity, 'old_qty' => $old_qty];
    }


    /**
     * create of update the customer important dates
     *
     * @param int $customer_id
     * @param array $customer_important_dates
     * @return void
     */
    public function createOrUpdateCustomerImportantDate($customer_id, $customer_important_dates)
    {
        foreach ($customer_important_dates as $key => $value) {
            $id = !empty($value['id']) ? $value['id'] : null;
            $customer_important_date = CustomerImportantDate::firstOrNew(['customer_id' => $customer_id, 'id' => $id]);
            $customer_important_date->customer_id = $customer_id;
            $customer_important_date->details = $value['details'];
            $customer_important_date->date = !empty($value['date']) ? $this->uf_date($value['date']) : null;
            $customer_important_date->notify_before_days = $value['notify_before_days'] ?? 0;
            $customer_important_date->created_by = Auth::guard('admin')->user()->id;

            $customer_important_date->save();
        }
    }

    /**
     * create referred reward system for customer
     *
     * @param int $customer_id
     * @param Request $request
     * @return void
     */
    public function createReferredRewardSystem($customer_id, $request)
    {
        foreach ($request->ref as $key => $ref) {
            if (!empty($ref['referred_type']) && !empty($ref['referred_by'])) {
                $referred = Referred::create([
                    'customer_id' => $customer_id,
                    'referred_type' => $ref['referred_type'],
                    'referred_by' => $ref['referred_by'] ?? [],
                ]);
                $referred_by = $ref['referred_by'];
                $reward_system = $request->reward_system[$key];
                foreach ($referred_by as $by) {
                    $reward_systems = $request->referred[$key][$by]['reward_system'];
                    $reward_system_data = $request->reward_system[$key][$by];

                    foreach ($reward_systems as $reward_system) {
                        $data = $reward_system_data[$reward_system];
                        $data['type'] = $reward_system;
                        $data['referred_id'] = $referred->id;
                        $data['referred_type'] = $referred->referred_type;
                        $data['referred_by'] = $by;
                        $data['amount'] = !empty($data['amount']) ? $data['amount'] : 0;

                        if (!empty($request->pct[$key][$by]) && $data['type'] == 'discount') {
                            $data['product_ids'] = $this->extractProductIdsfromProductTree($request->pct[$key][$by]);
                        } else {
                            $data['product_ids'] = [];
                        }
                        $data['pct_data'] = $request->pct[$key][$by] ?? [];
                        RewardSystem::create($data);
                        if ($referred->referred_type  == 'customer' && $data['type'] == 'loyalty_point') {
                            $this->updateCustomerRewardPoints($by, $data['loyalty_points'], 0, 0, 0);
                        }
                    }
                }
            }
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

        if (!empty($data_selected['product_selected'])) {
            $p = array_values(Product::whereIn('id', $data_selected['product_selected'])->select('id')->pluck('id')->toArray());
            $product_ids = array_merge($product_ids, $p);
        }

        $product_ids  = array_unique($product_ids);

        $product_ids = !empty($product_ids) ? $product_ids : [];
        return (array)$product_ids;
    }
}
