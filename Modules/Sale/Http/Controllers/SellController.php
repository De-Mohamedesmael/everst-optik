<?php

namespace Modules\Sale\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Utils\CashRegisterUtil;
use App\Utils\NotificationUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Modules\AddStock\Entities\Transaction;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerType;
use Modules\Customer\Entities\Prescription;
use Modules\Hr\Entities\Employee;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Brand;
use Modules\Setting\Entities\Currency;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\System;
use Modules\Setting\Entities\Tax;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Yajra\DataTables\Facades\DataTables;

class SellController extends Controller
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

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param ProductUtil $productUtil
     * @param TransactionUtil $transactionUtil
     * @param NotificationUtil $notificationUtil
     * @param CashRegisterUtil $cashRegisterUtil
     */
    public function __construct(Util $commonUtil, ProductUtil $productUtil, TransactionUtil $transactionUtil, NotificationUtil $notificationUtil, CashRegisterUtil $cashRegisterUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->productUtil = $productUtil;
        $this->transactionUtil = $transactionUtil;
        $this->notificationUtil = $notificationUtil;
        $this->cashRegisterUtil = $cashRegisterUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(Request $request)
    {
        $payment_types = $this->commonUtil->getPaymentTypeArrayForPos();
        $default_currency_id = System::getProperty('currency');

        if (request()->ajax()) {
            $store_id = request()->store_id;
            $query = Transaction::leftjoin('transaction_payments', 'transactions.id', 'transaction_payments.transaction_id')
                ->leftjoin('stores', 'transactions.store_id', 'stores.id')
                ->leftjoin('customers', 'transactions.customer_id', 'customers.id')
                ->leftjoin('customer_types', 'customers.customer_type_id', 'customer_types.id')
                ->leftjoin('transaction_sell_lines', 'transactions.id', 'transaction_sell_lines.transaction_id')
                ->leftjoin('products', 'transaction_sell_lines.product_id', 'products.id')
                ->leftjoin('admins', 'transactions.created_by', 'admins.id')
                ->leftjoin('currencies as received_currency', 'transactions.received_currency_id', 'received_currency.id')
                ->where('transactions.type', 'sell')
                ->whereIn('status', ['final', 'canceled']);

            $product_ids = 'all';
            if ((!empty(request()->category_id) && !empty(array_filter(request()->category_id)))
                || (!empty(request()->brand_id) && !empty(array_filter(request()->brand_id))  || request()->type_trans )
            ) {
                $products = Product::whereNotNull('id');

                if (
                    !empty(request()->category_id) &&
                    !empty(array_filter(request()->category_id))
                ) {
                    $products->whereIn('category_id', array_filter(request()->category_id));

                    $query->whereIn('products.category_id', array_filter(request()->category_id));
                }

                if (!empty(request()->brand_id) && !empty(array_filter(request()->brand_id))) {
                    $products->whereIn('brand_id', array_filter(request()->brand_id));
                    $query->whereIn('products.brand_id', array_filter(request()->brand_id));
                }

                if (request()->type_trans == "Lens") {
                    $query->where('products.is_lens',true);
                }else if (request()->type_trans == "Product") {

                    $query->where('products.is_lens',false);

                }
                $product_ids = $products->pluck('id');
            }


            if (!empty(request()->tax_id) && !empty(array_filter(request()->tax_id))) {
                $query->whereIn('transactions.tax_id', array_filter(request()->tax_id));
            }
            if (!empty(request()->customer_id)) {
                $query->where('customer_id', request()->customer_id);
            }
            if (!empty(request()->customer_type_id)) {

                    $query->where('customer_type_id', request()->customer_type_id);

            }

            if (!empty(request()->status)) {
                $query->where('status', request()->status);
            }
            if (!empty($store_id)) {
                $query->where('store_id', $store_id);
            }

            if (!empty(request()->payment_status)) {
                $query->where('payment_status', request()->payment_status);
            }
            if (!empty(request()->created_by)) {
                $query->where('transactions.created_by', request()->created_by);
            }
            if (!empty(request()->get('method'))) {
                $query->where('transaction_payments.method', request()->get('method'));
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
            if (!empty(request()->payment_start_date)) {
                $query->whereDate('paid_on', '>=', request()->payment_start_date);
            }
            if (!empty(request()->payment_end_date)) {
                $query->whereDate('paid_on', '<=', request()->payment_end_date);
            }
            if (!empty(request()->payment_start_time)) {
                $query->where('paid_on', '>=', request()->payment_start_date . ' ' . Carbon::parse(request()->payment_start_time)->format('H:i:s'));
            }
            if (!empty(request()->payment_end_time)) {
                $query->where('paid_on', '<=', request()->payment_end_date . ' ' . Carbon::parse(request()->payment_end_time)->format('H:i:s'));
            }

            if (strtolower(Session::get('user.job_title')) == 'cashier') {
                $query->where('transactions.created_by', Auth::user()->id);
            }
            $sales = $query->select(
                'transactions.final_total',
                'transactions.payment_status',
                'transactions.status',
                'transactions.id',
                'transactions.sale_note',
                'transactions.transaction_date',
                'transactions.created_at as created_at',
                'transactions.service_fee_value',
                'transactions.invoice_no',
                'transaction_payments.paid_on',
                'stores.name as store_name',
                'admins.name as created_by_name',
                'customers.name as customer_name',
                'customers.mobile_number',
                'received_currency.symbol as received_currency_symbol',
                'received_currency_id'
            )->with([
                'return_parent',
                'customer',
                'sell_products',
                'transaction_payments',
                'canceled_by_admin',
                'transaction_sell_lines' => function ($q) use ($product_ids) {
                    if ($product_ids != 'all') {
                        $q->wherein('transaction_sell_lines.product_id', $product_ids);
                    }
                },
                'sell_products' => function ($q) use ($product_ids) {
                    if ($product_ids != 'all') {
                        $q->wherein('products.id', $product_ids);
                    }
                }
            ])
                ->groupBy('transactions.id');


            return DataTables::of($sales)
                ->editColumn('transaction_date', '{{@format_datetime($created_at)}}')
                ->editColumn('invoice_no', function ($row) {
                    $string = $row->invoice_no . ' ';
                    if (!empty($row->return_parent)) {
                        $string .= '<a
                        data-href="' . '##' . '" data-container=".view_modal"
                        class="btn btn-modal" style="color: #007bff;">R</a>';
                    }
                    if ($row->payment_status == 'pending') {
                        $string .= '<a
                            data-href="' . '##' .  '" data-container=".view_modal"
                            class="btn btn-modal" style="color: #007bff;">P</a>';
                    }

                    return $string;
                })
                ->editColumn('final_total', function ($row) use ($default_currency_id, $product_ids) {
                    if ($product_ids == 'all') {
                        if (!empty($row->return_parent)) {
                            $final_total = number_format($row->final_total - $row->return_parent->final_total, 2, '.', ',');
                        } else {
                            $final_total = number_format($row->final_total, 2, '.', ',');
                        }
                    } else {
                        $final_total = 0;
                        foreach ($row->transaction_sell_lines as $transaction_sell_line) {
                            $final_total += ($transaction_sell_line->quantity - $transaction_sell_line->quantity_returned) * $transaction_sell_line->sell_price;
                        }
                    }

                    $received_currency_id = $row->received_currency_id ?? $default_currency_id;
                    return '<span data-currency_id="' . $received_currency_id . '">' . $final_total . '</span>';
                })
                ->editColumn('sale_note', function ($row) {
                    return $row->sale_note;
                })
                ->addColumn('paid', function ($row) use ($request, $default_currency_id, $product_ids) {
                    if ($product_ids == 'all') {
                        $amount_paid = 0;
                        if (!empty($request->get('method'))) {
                            $payments = $row->transaction_payments->where('method', $request->get('method'));
                        } else {
                            $payments = $row->transaction_payments;
                        }
                        foreach ($payments as $payment) {
                            $amount_paid += $payment->amount;
                        }
                    } else {
                        $amount_paid = 0;
                        foreach ($row->transaction_sell_lines as $transaction_sell_line) {
                            $amount_paid += ($transaction_sell_line->quantity - $transaction_sell_line->quantity_returned) * $transaction_sell_line->sell_price;
                        }
                    }
                    $received_currency_id = $row->received_currency_id ?? $default_currency_id;

                    return '<span data-currency_id="' . $received_currency_id . '">' . number_format($amount_paid, 2, '.', ',') . '</span>';
                })
                ->addColumn('due', function ($row) use ($default_currency_id) {
                    $paid = $row->transaction_payments->sum('amount');
                    $due = $row->final_total - $paid;
                    $received_currency_id = $row->received_currency_id ?? $default_currency_id;

                    return '<span data-currency_id="' . $received_currency_id . '">' . number_format($due, 2, '.', ',') . '</span>';
                })
                ->addColumn('customer_type', function ($row) {
                    if (!empty($row->customer->customer_type)) {
                        return $row->customer->customer_type->name;
                    } else {
                        return '';
                    }
                })
                ->addColumn('commissions', function ($row) {
                    $commissions = Transaction::where('parent_sale_id', $row->id)->where('type', 'employee_commission')->get();
                    $total = 0;
                    foreach ($commissions as $commission) {
                        $total += $commission->final_total;
                    }
                    return number_format($total, 2, '.', ',');
                })
                ->editColumn('received_currency_symbol', function ($row) use ($default_currency_id) {
                    $default_currency = Currency::find($default_currency_id);
                    return $row->received_currency_symbol ?? $default_currency->symbol;
                })
                ->editColumn('paid_on', '@if(!empty($paid_on)){{@format_datetime($paid_on)}}@endif')
                ->addColumn('method', function ($row) use ($payment_types, $request) {
                    $methods = '';
                    if (!empty($request->get('method'))) {
                        $payments = $row->transaction_payments->where('method', $request->get('method'));
                    } else {
                        $payments = $row->transaction_payments;
                    }
                    foreach ($payments as $payment) {
                        if (!empty($payment->method)) {
                            $methods .= $payment_types[$payment->method] . '<br>';
                        }
                    }
                    return $methods;
                })

                ->addColumn('store_name', '{{$store_name}}')
                ->addColumn('ref_number', function ($row) use ($request) {
                    $ref_numbers = '';
                    if (!empty($request->get('method'))) {
                        $payments = $row->transaction_payments->where('method', $request->get('method'));
                    } else {
                        $payments = $row->transaction_payments;
                    }
                    foreach ($payments as $payment) {
                        if (!empty($payment->ref_number)) {
                            $ref_numbers .= $payment->ref_number . '<br>';
                        }
                    }
                    return $ref_numbers;
                })
                ->editColumn('payment_status', function ($row) {
                    if ($row->payment_status == 'pending') {
                        return '<span class="label label-success">' . __('lang.pay_later') . '</span>';
                    } else {
                        return '<span class="label label-danger">' . ucfirst($row->payment_status) . '</span>';
                    }
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'canceled') {
                        return '<span class="badge badge-danger">' . __('lang.cancel') . '</span>';
                    } else {
                        return '<span class="badge badge-success">' . ucfirst($row->status) . '</span>';
                    }
                })
                ->addColumn('products', function ($row) {
                    $string = '';
                    foreach ($row->sell_products as $sell_product) {

                                $string .= $sell_product->name . ' ' . $sell_product->sku . '<br>';

                    }

                    return $string;
                })
                ->addColumn('sku', function ($row) {
                    $string = '';
                    foreach ($row->sell_products as $sell_product) {
                                $string .= $sell_product->sku . '<br>';
                    }

                    return $string;
                })

                ->editColumn('service_fee_value', '{{@num_format($service_fee_value)}}')
                ->editColumn('created_by', '{{$created_by_name}}')
                ->editColumn('canceled_by', function ($row) {
                    return !empty($row->canceled_by_admin) ? $row->canceled_by_admin->name : '';
                })
                ->addColumn('files', function ($row) {
                    return ' <a data-href="' . route('admin.view-uploaded-files', ['model_name' => '\Modules\AddStock\Entities\Transaction', 'model_id' => $row->id, 'collection_name' => 'sell']) . '"
                    data-container=".view_modal"
                    class="btn btn-default btn-modal">' . __('lang.view') . '</a>';
                })
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">' . __('lang.action') . '
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                        if (auth()->user()->can('sale.pos.create_and_edit')) {
                            $html .=
                                '<li>
                                <a data-href="' . route('admin.sale.print', $row->id) . '"
                                    class="btn print-invoice"><i class="dripicons-print"></i>
                                    ' . __('lang.generate_invoice') . '</a>
                            </li>';
                        }

                        if (auth()->user()->can('sale.pos.view')) {
                            $html .=
                                '<li>
                                <a data-href="' . route('admin.sale.show', $row->id) . '" data-container=".view_modal"
                                    class="btn btn-modal"><i class="fa fa-eye"></i> ' . __('lang.view') . '</a>
                            </li>';
                        }
                        if (auth()->user()->can('sale.pos.create_and_edit')) {
                            $html .=
                                '<li>
                                <a href="' . route('admin.pos.edit', $row->id) . '" class="btn"><i
                                        class="dripicons-document-edit"></i> ' . __('lang.edit') . '</a>
                            </li>';
                        }
//                        $html .= '<li class="divider"></li>';
//                        if (auth()->user()->can('return.sell_return.create_and_edit')) {
//                            //                            if (empty($row->return_parent)) {
//                            $html .=
//                                '<li>
//                                    <a href="' . action('SellReturnController@add', $row->id) . '" class="btn"><i
//                                        class="fa fa-undo"></i> ' . __('lang.sale_return') . '</a>
//                                    </li>';
//                            //                            }
//                        }
//                        $html .= '<li class="divider"></li>';
//                        if (auth()->user()->can('sale.pay.create_and_edit')) {
//                            if ($row->status != 'draft' && $row->payment_status != 'paid' && $row->status != 'canceled') {
//                                $final_total = $row->final_total;
//                                if (!empty($row->return_parent)) {
//                                    $final_total = $this->commonUtil->num_f($row->final_total - $row->return_parent->final_total);
//                                }
//                                if ($final_total > 0) {
//                                    $html .=
//                                        ' <li>
//                                    <a data-href="' . action('TransactionPaymentController@addPayment', $row->id) . '"
//                                        data-container=".view_modal" class="btn btn-modal"><i class="fa fa-plus"></i>
//                                        ' . __('lang.add_payment') . '</a>
//                                    </li>';
//                                }
//                            }
//                        }
//                        $html .= '<li class="divider"></li>';
//                        if (auth()->user()->can('sale.pay.view')) {
//                            $html .=
//                                '<li>
//                                <a data-href="' . action('TransactionPaymentController@show', $row->id) . '"
//                                    data-container=".view_modal" class="btn btn-modal"><i class="fa fa-money"></i>
//                                    ' . __('lang.view_payments') . '</a>
//                                </li>';
//                        }
                        if (auth()->user()->can('sale.pay.delete')) {
                            $html .=
                                '<li>
                                <a data-href="' . route('admin.sale.destroy', $row->id) . '"
                                    data-check_password="' . route('admin.check-password', Auth::user()->id) . '"
                                    class="btn text-red delete_item"><i class="fa fa-trash"></i>
                                    ' . __('lang.delete') . '</a>
                                </li>';
                        }
                        $html .= '</div>';
                        return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'method',
                    'invoice_no',
                    'ref_number',
                    'payment_status',
                    'transaction_date',
                    'final_total',
                    'paid',
                    'due',
                    'status',
                    'store_name',
                    'products',
                    'sku',
                    'files',
                    'created_by',
                ])
                ->make(true);
        }

        $categories = Category::orderBy('name', 'asc')->pluck('name', 'id');
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');
        $customers = Customer::getCustomerArrayWithMobile();
        $customer_types = CustomerType::pluck('name', 'id');
        $stores = Store::getDropdown();
        $payment_status_array = $this->commonUtil->getPaymentStatusArray();
        $cashiers = Employee::getDropdownByJobType('Cashier', true, true);
        $taxes = Tax::pluck('name', 'id');
        return view('sale::back-end.sale.index')->with(compact(
            'categories',
            'brands',
            'payment_types',
            'cashiers',
            'customers',
            'customer_types',
            'stores',
            'payment_status_array',
            'taxes',
        ));
    }

    public function getTotalDetails(Request $request)
    {
        if (request()->ajax()) {
            // $store_id = $this->transactionUtil->getFilterOptionValues($request)['store_id'];
            $store_id = request()->store_id;
            $query = Transaction::leftjoin('transaction_payments', 'transactions.id', 'transaction_payments.transaction_id')
                ->leftjoin('stores', 'transactions.store_id', 'stores.id')
                ->leftjoin('customers', 'transactions.customer_id', 'customers.id')
                ->leftjoin('customer_types', 'customers.customer_type_id', 'customer_types.id')
                ->leftjoin('transaction_sell_lines', 'transactions.id', 'transaction_sell_lines.transaction_id')
                ->leftjoin('products', 'transaction_sell_lines.product_id', 'products.id')
                ->leftjoin('admins', 'transactions.created_by', 'admins.id')
                ->leftjoin('currencies as received_currency', 'transactions.received_currency_id', 'received_currency.id')
                ->where('transactions.type', 'sell')->whereIn('status', ['final', 'canceled']);



            if (!empty(request()->category_id) && !empty(array_filter(request()->category_id))) {
                $query->whereIn('products.category_id', array_filter(request()->category_id));
            }



            if (!empty(request()->brand_id) && !empty(array_filter(request()->brand_id))) {
                $query->whereIn('products.brand_id', array_filter(request()->brand_id));
            }
            if (!empty(request()->tax_id) && !empty(array_filter(request()->tax_id))) {
                $query->whereIn('transactions.tax_id', array_filter(request()->tax_id));
            }
            if (!empty(request()->customer_id)) {
                $query->where('customer_id', request()->customer_id);
            }
            if (!empty(request()->customer_type_id)) {
                if (request()->customer_type_id == 'dining_in') {
                    $query->whereNotNull('dining_table_id');
                } else {
                    $query->where('customer_type_id', request()->customer_type_id);
                }
            }

            if (!empty(request()->status_)) {
                $query->where('status', request()->status_);
            }
            if (!empty($store_id)) {
                $query->where('store_id', $store_id);
            }

            if (!empty(request()->payment_status)) {
                $query->where('payment_status', request()->payment_status);
            }
            if (!empty(request()->created_by)) {
                $query->where('transactions.created_by', request()->created_by);
            }
            if (!empty(request()->get('method'))) {
                $query->where('transaction_payments.method', request()->get('method'));
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
            if (!empty(request()->payment_start_date)) {
                $query->whereDate('paid_on', '>=', request()->payment_start_date);
            }
            if (!empty(request()->payment_end_date)) {
                $query->whereDate('paid_on', '<=', request()->payment_end_date);
            }
            if (!empty(request()->payment_start_time)) {
                $query->where('paid_on', '>=', request()->payment_start_date . ' ' . Carbon::parse(request()->payment_start_time)->format('H:i:s'));
            }
            if (!empty(request()->payment_end_time)) {
                $query->where('paid_on', '<=', request()->payment_end_date . ' ' . Carbon::parse(request()->payment_end_time)->format('H:i:s'));
            }
            if (strtolower($request->session()->get('user.job_title')) == 'cashier') {
                $query->where('transactions.created_by', \auth()->id());
            }

            $query->select(
                DB::raw('COUNT(DISTINCT(transactions.customer_id)) as customer_count'),
                DB::raw('COUNT(DISTINCT(transactions.id)) as sales_count'),
            );
            $sales = $query->first();

            $sales_count = $sales->sales_count ?? 0;
            $customer_count = $sales->customer_count ?? 0;

            return ['customer_count' => $customer_count, 'sales_count' => $sales_count];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Factory|\Illuminate\Contracts\View\View|Application
     */
    public function show(int $id): Factory|\Illuminate\Contracts\View\View|Application
    {
        $sale = Transaction::find($id);
        $payment_type_array = $this->commonUtil->getPaymentTypeArrayForPos();

        return view('sale::back-end.sale.show')->with(compact(
            'sale',
            'payment_type_array',
        ));
    }

    /**
     * print the transaction
     *
     * @param int $id
     * @return array
     */
    public function print($id)
    {
        try {
            $transaction = Transaction::find($id);

            $payment_types = $this->commonUtil->getPaymentTypeArrayForPos();

            $html_content = $this->transactionUtil->getInvoicePrint($transaction, $payment_types);

            $output = [
                'success' => true,
                'html_content' => $html_content,
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
     * Get Prescription Details by ID
     *
     * @return array
     */
    public function getPrescriptionDetails(): array
    {
        try {
            $prescription = Prescription::find(request()->prescription_id);
            if($prescription){
                $output = [
                    'success' => true,
                    'data' => [
                        'lens_id'=>$prescription->product_id,
                        'prescription'=>json_decode($prescription->data)
                    ],
                    'msg' => __('lang.success')
                ];
            }else{

                $output = [
                    'success' => true,
                    'data' => [],
                    'msg' => __('lang.success')
                ];
            }


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
