<?php

namespace Modules\AddStock\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Setting\Entities\Brand;
use Modules\CashRegister\Entities\CashRegister;
use Modules\CashRegister\Entities\CashRegisterTransaction;
use Modules\Product\Entities\Category;
use Modules\Setting\Entities\Color;
use Modules\Setting\Entities\Currency;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerType;
use Modules\Setting\Entities\MoneySafeTransaction;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Size;
use Modules\Setting\Entities\System;
use Modules\Setting\Entities\Tax;
use Modules\AddStock\Entities\Transaction;
use Modules\AddStock\Entities\TransactionPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Modules\AddStock\Entities\AddStockLine;
use App\Models\Admin;
use App\Utils\CashRegisterUtil;
use App\Utils\MoneySafeUtil;
use App\Utils\NotificationUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Modules\Setting\Entities\MoneySafe;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\StorePos;
use Yajra\DataTables\Facades\DataTables;

class AddStockController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected Util $commonUtil;
    protected TransactionUtil $transactionUtil;
    protected ProductUtil $productUtil;
    protected NotificationUtil $notificationUtil;
    protected CashRegisterUtil $cashRegisterUtil;
    protected MoneySafeUtil $moneysafeUtil;

    public function __construct(Util $commonUtil, ProductUtil $productUtil, TransactionUtil $transactionUtil, NotificationUtil $notificationUtil, CashRegisterUtil $cashRegisterUtil, MoneySafeUtil $moneysafeUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
        $this->notificationUtil = $notificationUtil;
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->moneysafeUtil = $moneysafeUtil;

    }


    public function index(Request $request)
    {
        if (request()->ajax()) {
            $default_currency_id = System::getProperty('currency');
            $store_id = $this->transactionUtil->getFilterOptionValues($request)['store_id'];
            $pos_id = $this->transactionUtil->getFilterOptionValues($request)['pos_id'];

            $query=Transaction::leftjoin('add_stock_lines', 'transactions.id', 'add_stock_lines.transaction_id')
                ->leftjoin('admins', 'transactions.created_by', '=', 'admins.id')
                ->leftjoin('currencies as paying_currency', 'transactions.paying_currency_id', 'paying_currency.id')
                ->whereIn('type',['material_manufactured','add_stock'])

                ->where('status', '!=', 'draft');

            if (!empty($store_id)) {
                $query->where('transactions.store_id', $store_id);
            }



            if (!empty(request()->created_by)) {
                $query->where('transactions.created_by', request()->created_by);
            }
            if (!empty(request()->product_id)) {
                $query->where('add_stock_lines.product_id', request()->product_id);
            }
            if (!empty(request()->start_date)) {
                $query->whereDate('transaction_date', '>=', request()->start_date);
            }
            if (!empty(request()->end_date)) {
                $query->whereDate('transaction_date', '<=', request()->end_date);
            }
            if (!empty(request()->start_time)) {
                $query->where('transaction_date', '>=', request()->start_date . ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
            }
            if (!empty(request()->end_time)) {
                $query->where('transaction_date', '<=', request()->end_date . ' ' . Carbon::parse(request()->end_time)->format('H:i:s'));
            }


            $add_stocks = $query->select(
                'transactions.*',
                'admins.name as created_by_name',
                'paying_currency.symbol as paying_currency_symbol'
            )->groupBy('transactions.id');
            return DataTables::of($add_stocks)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('transaction_date', '{{@format_datetime($transaction_date)}}')
                ->editColumn('due_date', '@if(!empty($add_stock->due_date) && $add_stock->payment_status != "paid"){{@format_datetime($due_date)}}@endif')
                ->editColumn('created_by', '{{$created_by_name}}')
                ->editColumn('final_total', function ($row) use ($default_currency_id) {
                    $final_total =  $row->final_total;
                    $paying_currency_id = $row->paying_currency_id ?? $default_currency_id;
                    return '<span data-currency_id="' . $paying_currency_id . '">' . number_format($final_total,2) . '</span>';
                })
                ->addColumn('paid_amount', function ($row) use ($default_currency_id) {
                    $amount_paid =  $row->transaction_payments->sum('amount');
                    $paying_currency_id = $row->paying_currency_id ?? $default_currency_id;
                    return '<span data-currency_id="' . $paying_currency_id . '">' . number_format($amount_paid,2) . '</span>';
                })
                ->addColumn('due', function ($row) use ($default_currency_id) {
                    $due =  $row->final_total - $row->transaction_payments->sum('amount');
                    $paying_currency_id = $row->paying_currency_id ?? $default_currency_id;
                    return '<span data-currency_id="' . $paying_currency_id . '">' . number_format($due,2) . '</span>';
                })
                ->editColumn('paying_currency_symbol', function ($row) use ($default_currency_id) {
                    $default_currency = Currency::find($default_currency_id);
                    return $row->paying_currency_symbol ?? $default_currency->symbol;
                })
                ->addColumn('products', function ($row) {
                    $string = '';

                    foreach ($row->add_stock_products as $product) {
                        if (!empty($product)) {
                           $string .= $product->name . '-' . $product->sku . '<br>';
                        }
                    }

                    return $string;
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = ' <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">' . __('lang.action') . '
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                        if (auth()->user()->can('stock.add_stock.view')) {
                            $html .=
                                '<li>
                                    <a href="' . route('admin.add-stock.show', $row->id) . '" class=""><i
                                    class="fa fa-eye btn"></i> ' . __('lang.view') . '</a>
                                 </li>';
                        }
                        if (auth()->user()->can('stock.add_stock.create_and_edit')) {
                            $html .=
                                '<li>
                                <a href="' . route('admin.add-stock.edit', $row->id) . '"><i
                                        class="dripicons-document-edit btn"></i>' . __('lang.edit') . '</a>
                                </li>';
                        }
                        if (auth()->user()->can('stock.add_stock.delete')) {
                            $html .=
                                '<li>
                                <a data-href="' . route('admin.add-stock.destroy', $row->id) . '"
                                    data-check_password="' .route('admin.check-password', Auth::user()->id) . '"
                                    class="btn text-red delete_item"><i class="dripicons-trash"></i>
                                    ' . __('lang.delete') . '</a>
                                </li>';
                        }
                        if (auth()->user()->can('stock.pay.create_and_edit')) {
                            if ($row->payment_status != 'paid') {
                                $html .=
                                    '<li>
                                    <a data-href="' . '#' . '"
                                        data-container=".view_modal" class="btn btn-modal"><i class="fa fa-money"></i>
                                        ' . __('lang.pay') . '</a>
                                    </li>';
                            }
                        }

                        $html .= '</ul></div>';
                        return $html;
                    }
                )
                ->rawColumns([
                    'po_no',
                    'action',
                    'transaction_date',
                    'created_at',
                    'due_date',
                    'final_total',
                    'paid_amount',
                    'products',
                    'due',
                    'created_by',
                ])
                ->make(true);
        }

        $admins = Admin::orderBy('name', 'asc')->pluck('name', 'id');
        $products = Product::orderBy('name', 'asc')->pluck('name', 'id');
        $stores = Store::getDropdown();
        $status_array = $this->commonUtil->getPurchaseOrderStatusArray();

        return view('addstock::back-end.add_stock.index')->with(compact(
            'admins',
            'products',
            'stores',
            'status_array'
        ));
    }

    public function create()
    {
        $po_nos = Transaction::where('type', 'purchase_order')->where('status', '!=', 'received')->pluck('po_no', 'id');
        $status_array = $this->commonUtil->getPurchaseOrderStatusArray();
        $payment_status_array = $this->commonUtil->getPaymentStatusArray();
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();
        $payment_types = $payment_type_array;
        $taxes = Tax::pluck('name', 'id');
        $product_id = request()->get('product_id');
        $categories = Category::orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes_array = Tax::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = Customer::getCustomerTreeArray();
        $exchange_rate_currencies = $this->commonUtil->getCurrenciesExchangeRateArray(true);

        $stores  = Store::getDropdown();
        $admins = Admin::pluck('name', 'id');

        return view('addstock::back-end.add_stock.create')->with(compact(
            'status_array',
            'payment_status_array',
            'payment_type_array',
            'stores',
            'product_id',
            'po_nos',
            'taxes',
            'payment_types',
            'payment_status_array',
            'categories',
            'brands',
            'colors',
            'sizes',
            'taxes_array',
            'customer_types',
            'exchange_rate_currencies',
            'discount_customer_types',
            'admins',
        ));
    }
    public function store(Request $request)
    {
          try {
                $data = $request->except('_token');

                if (!empty($data['po_no'])) {
                    $ref_transaction_po = Transaction::find($data['po_no']);
                }
                $transaction_data = [
                    'store_id' => $data['store_id'],
                    'type' => 'add_stock',
                    'status' => $data['status'],
                    'paying_currency_id' => $data['paying_currency_id'],
                    'default_currency_id' => $data['default_currency_id'],
                    'exchange_rate' => $this->commonUtil->num_uf($data['exchange_rate']),
                    'order_date' => !empty($ref_transaction_po) ? $ref_transaction_po->transaction_date : Carbon::now(),
                    'transaction_date' => !empty($data['transaction_date']) ? Carbon::createFromTimestamp(strtotime($data['transaction_date']))->format('Y-m-d H:i:s') : Carbon::now(),
                    'payment_status' => $data['payment_status'],
                    'po_no' => !empty($ref_transaction_po) ? $ref_transaction_po->po_no : null,
                    'purchase_order_id' => !empty($data['po_no']) ? $data['po_no'] : null,
                    'grand_total' => $this->productUtil->num_uf($data['grand_total']),
                    'final_total' => $this->productUtil->num_uf($data['final_total']),
                    'discount_amount' => $this->productUtil->num_uf($data['discount_amount']),
                    'other_payments' => $this->productUtil->num_uf($data['other_payments']),
                    'other_expenses' => $this->productUtil->num_uf($data['other_expenses']),
                    'notes' => !empty($data['notes']) ? $data['notes'] : null,
                    'details' => !empty($data['details']) ? $data['details'] : null,
                    'invoice_no' => !empty($data['invoice_no']) ? $data['invoice_no'] : null,
                    'due_date' => !empty($data['due_date']) ? $this->commonUtil->uf_date($data['due_date']) : null,
                    'notify_me' => !empty($data['notify_before_days']) ? 1 : 0,
                    'notify_before_days' => !empty($data['notify_before_days']) ? $data['notify_before_days'] : 0,
                    'created_by' => Auth::user()->id,
                    'source_id' => !empty($data['source_id']) ? $data['source_id'] : null,
                    'source_type' => !empty($data['source_type']) ? $data['source_type'] : null,
                ];

                DB::beginTransaction();
                $transaction = Transaction::create($transaction_data);

                $this->productUtil->createOrUpdateAddStockLines($request->add_stock_lines, $transaction,$request->batch_row);

                if ($request->files) {
                    foreach ($request->file('files', []) as $key => $file) {

                        $transaction->addMedia($file)->toMediaCollection('add_stock');
                    }
                }
                if ($request->payment_status != 'pending') {
                    $payment_data = [
                        'transaction_id' => $transaction->id,
                        'amount' => $this->commonUtil->num_uf($request->amount),
                        'method' => $request["method"],
                        'paid_on' => $this->commonUtil->uf_date($data['paid_on']),
                        'ref_number' => $request->ref_number,
                        'source_type' => $request->source_type,
                        'source_id' => $request->source_id,
                        'bank_deposit_date' => !empty($data['bank_deposit_date']) ? $this->commonUtil->uf_date($data['bank_deposit_date']) : null,
                        'bank_name' => $request->bank_name,
                    ];
                    $transaction_payment = $this->transactionUtil->createOrUpdateTransactionPayment($transaction, $payment_data);

                    if ($payment_data['method'] == 'cash') {
                        $admin_id = null;
                        if (!empty($request->source_id)) {
                            if ($request->source_type == 'pos') {
                                $admin_id = StorePos::where('id', $request->source_id)->first()->admin_id;
                            }
                            if ($request->source_type == 'admin') {
                                $admin_id = $request->source_id;
                            }
                            if ($request->source_type == 'safe') {
                                $money_safe = MoneySafe::find($request->source_id);
                                $payment_data['currency_id'] = $transaction->paying_currency_id;

                                $this->moneysafeUtil->addPayment($transaction, $payment_data, 'debit', $transaction_payment->id, $money_safe);
                            }
                        }
                        if (!empty($admin_id)) {
                            $this->cashRegisterUtil->addPayments($transaction, $payment_data, 'debit', $admin_id);
                        }
                    }

                    if ($request->upload_documents) {
                        foreach ($request->file('upload_documents', []) as $key => $doc) {
                            $transaction_payment->addMedia($doc)->toMediaCollection('transaction_payment');
                        }
                    }
                }

                $this->transactionUtil->updateTransactionPaymentStatus($transaction->id);


                //update purchase order status if selected
                if (!empty($transaction->purchase_order_id)) {
                    Transaction::find($transaction->purchase_order_id)->update(['status' => 'received']);
                }

                //update products status to active if not //added quick products from purchase order
                foreach ($transaction->add_stock_lines as $line) {
                    Product::where('id', $line->product_id)->update(['active' => 1]);
                }
                DB::commit();

                if (isset($data['submit']) &&$data['submit'] == 'print') {
                    $print = 'print';
                    $url = route('admin.add-stock.show', $transaction->id) . '?print=' . $print;

                    return Redirect::to($url);
                }

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

        return redirect()->back()->with('status', $output);
    }

    public function show($id)
    {
        $add_stock = Transaction::find($id);

        $payment_type_array = $this->commonUtil->getPaymentTypeArray();
        $taxes = Tax::pluck('name', 'id');
        $admins = Admin::pluck('name', 'id');

        return view('addstock::back-end.add_stock.show')->with(compact(
            'add_stock',
            'payment_type_array',
            'admins',
            'taxes'
        ));
    }

    public function edit($id)
    {
        $add_stock = Transaction::find($id);

        $po_nos = Transaction::where('type', 'purchase_order')->where('status', '!=', 'received')->pluck('po_no', 'id');
        $status_array = $this->commonUtil->getPurchaseOrderStatusArray();
        $payment_status_array = $this->commonUtil->getPaymentStatusArray();
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();
        $payment_types = $payment_type_array;
        $taxes = Tax::pluck('name', 'id');

        $categories = Category::orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::orderBy('name', 'asc')->pluck('name', 'id');
        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');
        $taxes_array = Tax::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        $discount_customer_types = Customer::getCustomerTreeArray();
        $exchange_rate_currencies = $this->commonUtil->getCurrenciesExchangeRateArray(true);

        $stores  = Store::getDropdown();
        $admins = Admin::pluck('name', 'id');

        return view('addstock::back-end.add_stock.edit')->with(compact(
            'add_stock',
            'status_array',
            'payment_status_array',
            'payment_type_array',
            'stores',
            'taxes',
            'po_nos',
            'payment_types',
            'payment_status_array',
            'categories',
            'brands',
            'colors',
            'sizes',
            'taxes_array',
            'customer_types',
            'exchange_rate_currencies',
            'discount_customer_types',
            'admins',
        ));
    }

    public function update(Request $request, $id)
    {

        // try {
        $data = $request->except('_token');

        if (!empty($data['po_no'])) {
            $ref_transaction_po = Transaction::find($data['po_no']);
        }

        $transaction_data = [
            'store_id' => $data['store_id'],
            'type' => 'add_stock',
            'status' => $data['status'],
            'paying_currency_id' => $data['paying_currency_id'],
            'default_currency_id' => $data['default_currency_id'],
            'exchange_rate' => $this->commonUtil->num_uf($data['exchange_rate']),
            'order_date' => !empty($ref_transaction_po) ? $ref_transaction_po->transaction_date : Carbon::now(),
            'transaction_date' => isset($data['transaction_date'])? $this->commonUtil->uf_date($data['transaction_date']) . ' ' . date('H:i:s'):null,
            'payment_status' =>isset($data['payment_status'])?$data['payment_status']:null,
            'po_no' => !empty($ref_transaction_po) ? $ref_transaction_po->po_no : null,
            'grand_total' => isset($data['grand_total'])?$this->productUtil->num_uf($data['grand_total']):0,
            'final_total' => $this->productUtil->num_uf($data['final_total']),
            'discount_amount' =>isset($data['discount_amount']) ? $this->productUtil->num_uf($data['discount_amount']):0,
            'other_payments' => isset($data['other_payments'])?$this->productUtil->num_uf($data['other_payments']):null,
            'other_expenses' => isset($data['other_expenses'])?$this->productUtil->num_uf($data['other_expenses']):null,
            'notes' => !empty($data['notes']) ? $data['notes'] : null,
            'details' => !empty($data['details']) ? $data['details'] : null,
            'invoice_no' => !empty($data['invoice_no']) ? $data['invoice_no'] : null,
            'due_date' => !empty($data['due_date']) ? $this->commonUtil->uf_date($data['due_date']) : null,
            'notify_me' => !empty($data['notify_before_days']) ? 1 : 0,
            'notify_before_days' => !empty($data['notify_before_days']) ? $data['notify_before_days'] : 0,
//            'created_by' => Auth::user()->id,
            'source_id' => !empty($data['source_id']) ? $data['source_id'] : null,
            'source_type' => !empty($data['source_type']) ? $data['source_type'] : null,
            'updated_by' => Auth::user()->id,
        ];

        if (!empty($data['po_no'])) {
            $transaction_date['purchase_order_id'] = $data['po_no'];
        }

        DB::beginTransaction();
        $transaction = Transaction::where('id', $id)->first();
        $transaction_old_payment_status = $transaction->payment_status;
        $transaction->update($transaction_data);

        $mismtach = $this->productUtil->checkSoldAndPurchaseQtyMismatch($request->add_stock_lines, $transaction);
        if ($mismtach) {
            return $this->productUtil->sendQunatityMismacthResponse($mismtach['product_name'], $mismtach['quantity']);
        }

        $this->productUtil->createOrUpdateAddStockLines($request->add_stock_lines, $transaction,$request->batch_row);

        if ($request->files) {
            foreach ($request->file('files', []) as $file) {
                $transaction->addMedia($file)->toMediaCollection('add_stock');
            }
        }

        if ($request->payment_status != 'pending') {
            $payment_data = [
                'transaction_payment_id' => !empty($request->transaction_payment_id) ? $request->transaction_payment_id : null,
                'transaction_id' => $transaction->id,
                'amount' => $this->commonUtil->num_uf($request->amount),
                'method' => $request["method"],
                'paid_on' => !empty($data['bank_deposit_date']) ? $this->commonUtil->uf_date($data['paid_on']) : null,
                'ref_number' => $request->ref_number,
                'source_type' => $request->source_type,
                'source_id' => $request->source_id,
                'bank_deposit_date' => !empty($data['bank_deposit_date']) ? $this->commonUtil->uf_date($data['bank_deposit_date']) : null,
                'bank_name' => $request->bank_name,
            ];


            $transaction_payment = $this->transactionUtil->createOrUpdateTransactionPayment($transaction, $payment_data);

            if ($payment_data['method'] == 'cash') {
                $admin_id = null;
                if (!empty($request->source_id)) {
                    if ($request->source_type == 'pos') {
                        $admin_id = StorePos::where('id', $request->source_id)->first()->admin_id;
                    }
                    if ($request->source_type == 'admin') {
                        $admin_id = $request->source_id;
                    }
                    if ($request->source_type == 'safe') {
                        $money_safe = MoneySafe::find($request->source_id);
                        $payment_data['currency_id'] = $transaction->paying_currency_id;
                        $this->moneysafeUtil->updatePayment($transaction, $payment_data, 'debit', $transaction_payment->id, null, $money_safe);
                    }
                }

                if (!empty($admin_id)) {
                    $cr_transaction = CashRegisterTransaction::where('transaction_id', $transaction->id)->first();
                    if($cr_transaction){
                        $register = CashRegister::where('id', $cr_transaction->cash_register_id)->first();
                        $this->cashRegisterUtil->updateCashRegisterTransaction($cr_transaction->id, $register, $payment_data['amount'], $transaction->type, 'debit', $admin_id, '');
                    }else{
                        $this->cashRegisterUtil->addPayments($transaction, $payment_data, 'debit', $admin_id);
                    }

                }
            }

            if ($request->upload_documents) {
                foreach ($request->file('upload_documents', []) as $doc) {
                    $transaction_payment->addMedia($doc)->toMediaCollection('transaction_payment');
                }
            }
        }


        if($transaction->payment_status == "pending" && $transaction_old_payment_status == "paid"){
            $TransactionPayment = TransactionPayment::where('transaction_id', $transaction->id)->where('transaction_id', $transaction->id)->first();
            if($TransactionPayment){
                $money_safe_t =  MoneySafeTransaction::where('transaction_payment_id', $TransactionPayment->id)->where('transaction_id', $transaction->id)->first();
                if($money_safe_t){
                    $money_safe_t->delete();
                }
                $cr_transaction = CashRegisterTransaction::where('transaction_id', $transaction->id)->where('transaction_payment_id', $TransactionPayment->id)->first();
                if( $cr_transaction){
                    $register = CashRegister::where('id', $cr_transaction->cash_register_id)->where('status','close')->first();
                    if($register){
                        $register->closing_amount += $TransactionPayment->amount;
                        $register->save();
                    }
                    $cr_transaction->delete();
                }
                $TransactionPayment->delete();
            }




        }else{
            $this->transactionUtil->updateTransactionPaymentStatus($transaction->id);
        }
        //update purchase order status if selected
        if (!empty($transaction->purchase_order_id)) {
            Transaction::find($transaction->purchase_order_id)->update(['status' => 'received']);
        }
        DB::commit();

        if (isset($data['submit']) && $data['submit'] == 'print') {
            $print = 'print';
            $url = route('admin.add-stock.show', $transaction->id) . '?print=' . $print;

            return Redirect::to($url);
        }

        $output = [
            'success' => true,
            'msg' => __('lang.success')
        ];
        // } catch (\Exception $e) {
        //     Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
        //     $output = [
        //         'success' => false,
        //         'msg' => __('lang.something_went_wrong')
        //     ];
        // }

        return redirect()->back()->with('status', $output);
    }

    public function destroy($id)
    {
        try {
            $add_stock = Transaction::find($id);

            $add_stock_lines = $add_stock->add_stock_lines;
            DB::beginTransaction();

            if ($add_stock->status != 'received') {
                $add_stock_lines->delete();
            } else {
                $delete_add_stock_line_ids = [];
                foreach ($add_stock_lines as $line) {
                    $delete_add_stock_line_ids[] = $line->id;
                    $this->productUtil->decreaseProductQuantity($line->product_id,$add_stock->store_id, $line->quantity);
                }

                if (!empty($delete_add_stock_line_ids)) {
                    AddStockLine::where('transaction_id', $id)->whereIn('id', $delete_add_stock_line_ids)->delete();
                }
            }

            $add_stock->delete();
            CashRegisterTransaction::where('transaction_id', $id)->delete();
            MoneySafeTransaction::where('transaction_id', $id)->delete();

            DB::commit();


            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {

            DB::rollback();
            dd($e);
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }

        return $output;
    }

    public function addProductRow(Request $request)
    {
        if ($request->ajax()) {
            $currency_id = $request->currency_id;
            $currency = Currency::find($currency_id);
            $exchange_rate = $this->commonUtil->getExchangeRateByCurrency($currency_id, $request->store_id);

            $product_id = $request->input('product_id');
            $store_id = $request->input('store_id');
            $qty = $request->qty?$request->qty:0;
            $is_batch = $request->is_batch;
            if (!empty($product_id)) {
                $index = $request->input('row_count');
                $products = $this->productUtil->getDetailsFromProduct($product_id, $store_id);

                return view('addstock::back-end.add_stock.partials.product_row')
                    ->with(compact('products', 'index', 'currency', 'exchange_rate','qty','is_batch'));
            }
        }
    }

    public function addMultipleProductRow(Request $request)
    {
        if ($request->ajax()) {
            $currency_id = $request->currency_id;
            $currency = Currency::find($currency_id);
            $exchange_rate = $this->commonUtil->getExchangeRateByCurrency($currency_id, $request->store_id);

            $product_selected = $request->input('product_selected');
            $store_id = $request->input('store_id');
            if (!empty($product_selected)) {
                $index = $request->input('row_count');
                $products = $this->productUtil->getMultipleDetailsFromProduct($product_selected, $store_id);
                return view('addstock::back-end.add_stock.partials.product_row')
                    ->with(compact('products', 'index', 'currency', 'exchange_rate'));
            }
        }
    }

    public function getProducts()
    {
        if (request()->ajax()) {

            $term = request()->term;

            if (empty($term)) {
                return json_encode([]);
            }

            $q = Product::leftjoin('product_stores', 'products.id', 'product_stores.product_id')
                ->where(function ($query) use ($term) {
                    $query->where('products.name', 'like', '%' . $term . '%');
                    $query->orWhere('sku', 'like', '%' . $term . '%');
                })
                ->whereNull('products.deleted_at')
                ->where('is_service', 0)
                ->select(
                    'products.*',
                    'products.id as product_id',
                );

            if (!empty(request()->store_id)) {
                $q->where('product_stores.store_id', request()->store_id);
            }

            $products = $q->groupBy('product_id')->get();

            $products_array = [];
            foreach ($products as $product) {
                $products_array[$product->product_id]['name'] = $product->name;
                $products_array[$product->product_id]['sku'] = $product->sub_sku;
                $products_array[$product->product_id]['type'] = $product->type;
                $products_array[$product->product_id]['image'] = !empty($product->getFirstMediaUrl('products')) ? $product->getFirstMediaUrl('products') : asset('/uploads/' . session('logo'));

            }

            $result = [];
            $i = 1;
            $no_of_records = $products->count();
            if (!empty($products_array)) {
                foreach ($products_array as $key => $value) {

                    $result[] = [
                        'id' => $i,
                        'text' => $value['name'] . ' - ' . $value['sku'],
                        'product_id' => $key,
                        'image' => $value['image'],
                    ];
                    $i++;
                }
            }

            return json_encode($result);
        }
    }


    public function getSourceByTypeDropdown($type = null): string
    {
        if ($type == 'admin') {
            $array = Admin::pluck('name', 'id');
        }
        if ($type == 'pos') {
            $array = StorePos::pluck('name', 'id');
        }
        if ($type == 'store') {
            $array = Store::pluck('name', 'id');
        }
        if ($type == 'safe') {
            $array = MoneySafe::pluck('name', 'id');
        }

        return $this->commonUtil->createDropdownHtml($array, __('lang.please_select'));
    }


    public function addProductBatchRow(Request $request)
    {
        if ($request->ajax()) {
            $currency_id = $request->currency_id;
            $currency = Currency::find($currency_id);
            $exchange_rate = $this->commonUtil->getExchangeRateByCurrency($currency_id, $request->store_id);

            $product_id = $request->input('product_id');
            $store_id = $request->input('store_id');
            $batch_count = $request->input('batch_count');

            // if (!empty($product_id)) {
            $row_count = $request->input('index');
            $products = $this->productUtil->getDetailsFromProduct($product_id, $store_id);
            return view('addstock::back-end.add_stock.partials.batch_row')
                ->with(compact('products','row_count','exchange_rate','batch_count'));

        }
    }
    public function getPurchaseOrderDetails($id)
    {
        $purchase_order = Transaction::find($id);

        return view('addstock::back-end.add_stock.partials.purchase_order_details')->with(compact(
            'purchase_order'
        ));
    }




}
