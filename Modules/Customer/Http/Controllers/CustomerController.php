<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\CashRegister\Entities\CashRegisterTransaction;
use Modules\Customer\Entities\Prescription;
use Modules\Setting\Entities\Currency;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerType;
use Modules\Customer\Entities\DebtPayment;
use Modules\Customer\Entities\DebtTransactionPayment;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\System;
use Modules\AddStock\Entities\Transaction;
use Modules\AddStock\Entities\TransactionPayment;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Setting\Entities\TaxLocation;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\CashRegisterUtil;

class CustomerController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected Util $commonUtil;
    protected TransactionUtil $transactionUtil;
    protected ProductUtil $productUtil;
    protected CashRegisterUtil $cashRegisterUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param TransactionUtil $transactionUtil
     * @param CashRegisterUtil $cashRegisterUtil
     * @param ProductUtil $productUtil
     * @return void
     */
    public function __construct(Util $commonUtil, TransactionUtil $transactionUtil, CashRegisterUtil $cashRegisterUtil, ProductUtil $productUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->transactionUtil = $transactionUtil;
        $this->productUtil = $productUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View|Response|Application|JsonResponse
     * @throws Exception
     */
    public function index(): Factory|View|Response|Application|JsonResponse
    {

        if (request()->ajax()) {

         $query = Customer::
                leftjoin('transactions', 'customers.id', 'transactions.customer_id')
                ->leftjoin('admins', 'customers.created_by', 'admins.id')
                ->leftjoin('customer_types', 'customers.customer_type_id', 'customer_types.id');

        if (!empty(request()->startdate)) {
            $query->where('transactions.transaction_date','>=', request()->startdate. ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
        }
        if (!empty(request()->enddate)) {
            $query->where('transactions.transaction_date','<=', request()->enddate. ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
        }
        if (!empty(request()->customer_type_id)) {
            $query->where('customer_types.id',request()->customer_type_id);
        }

        if (!empty(request()->gender)) {
            $query->where('customers.gender',request()->gender);
        }

        $query->select(
            'customers.*',
            'admins.name as created_by_name',
            'customer_types.name as customer_type_name',
            DB::raw('SUM(IF(transactions.type="sell_return", final_total, 0)) as total_return'),
            DB::raw('SUM(IF(transactions.type="sell", final_total, 0)) as total_purchase'),
            DB::raw('SUM(IF(transactions.type="sell", total_sp_discount, 0)) as total_sp_discount'),
            DB::raw('SUM(IF(transactions.type="sell", total_product_discount, 0)) as total_product_discount'),

        );

        $query->addSelect(DB::raw('(SUM(IF(transactions.type="sell", final_total, 0)) - SUM(IF(transactions.type="sell_return", final_total, 0))) as purchases'));

        // Check if there is a request to sort by the "purchases" column
        if (!empty(request()->input('order'))) {
            $order = request()->input('order')[0];
            $columnIndex = $order['column'];
            $columnName = request()->input('columns')[$columnIndex]['data'];
            $columnDirection = $order['dir'];

            if ($columnName == 'purchases') {
                // Use the alias "purchases" for sorting
                $query->orderBy('purchases', $columnDirection);
            }
        }

         $customers = $query->groupBy('customers.id');
        return DataTables::of($customers)
        ->addColumn('customer_type', function ($row) {
            if(!empty($row->customer_type)){
                return $row->customer_type->name;
            }else{
                return '';
            }
        })
        ->addColumn('image', function ($row) {
            $image = $row->getFirstMediaUrl('customer_photo');
            if (!empty($image)) {
                return '<img src="' . $image . '" height="50px" width="50px">';
            } else {
                return '<img src="' . asset('/uploads/' . \Modules\Setting\Entities\System::getProperty('logo')) . '" height="50px" width="50px">';
            }
        })
        ->editColumn('customer_name','{{$name}}')
        ->addColumn('mobile_number', function ($row) {
           return $row->mobile_number;
        })

        ->addColumn('address', function ($row) {
            return $row->address;
         })
        ->addColumn('gender', function ($row) {
            return $row->gender_name;
        })
         ->addColumn('balance', function ($row){
            $balance = $this->transactionUtil->getCustomerBalance($row->id)['balance'];

            if (isset($balance)) {
                $class='';
                if ($balance < 0) {
                    $class= "text-red";
                }
                $balance=ceil($balance* 100) / 100;
                return "<span class='".$class."'>".number_format($balance,2,',','.')."</span>";
            }else{
                return $balance;
            }
         })
         ->addColumn('purchases', function ($row) {
            $purchase=number_format(($row->total_purchase - $row->total_return) ,2,',','.');
            return $purchase;

         })
         ->addColumn('discount', function ($row) {
            $discount=ceil($row->total_sp_discount + $row->total_product_discount ) / 100;
            return $discount;
         })
         ->addColumn('joining_date', function ($row) {
            return $row->created_at->format('Y-m-d');
         })
            ->addColumn('tax_location', function ($row) {
            return $row->tax_location?->name;
         })


        ->editColumn('created_by', '{{$created_by_name}}')

        ->addColumn(
            'action',
            function ($row) {
                $html = '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">' . __('lang.action') . '
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                if (auth()->user()->can('customer_module.customer.view')) {
                    $html .=
                        '<li>
                        <a href="' . route('admin.customers.show', $row->id) . '">
                        <i class="dripicons-document"></i>
                            ' .__('lang.view') . '</a>
                            </li>';
                }

                if (auth()->user()->can('customer_module.customer.create_and_edit')) {
                    $html .=
                    '<li>
                    <a href="' . route('admin.customers.edit', $row->id) . '"
                        ><i class="dripicons-document-edit"></i>
                        ' .__('lang.edit') . '</a>
                        </li>';
                }
                $balance = $this->transactionUtil->getCustomerBalance($row->id)['balance'];

                if (auth()->user()->can('customer_module.add_payment.create_and_edit')) {
                    if (isset($balance) && $balance < 0){
                    $html .=
                    '<li>
                    <a data-href="' . route('admin.transaction.getCustomerDue', $row->id) . '"


                    class="btn-modal" data-container=".view_modal"style="cursor: pointer"><i class="fa fa-money "></i>
                        ' .__('lang.pay_customer_due') . '</a>
                        </li>';
                    }
                }
                if (auth()->user()->can('customer_module.add_payment.create_and_edit')) {
                    if (isset($balance) && $balance > 0){
                    $html .=
                    '<li>
                    <a data-href="' . route('admin.transaction.getCustomerDue', ['customer_id'=>$row->id,'extract_due'=>'true']) . '"
                    class="btn-modal" data-container=".view_modal" style="cursor: pointer"><i class="fa fa-money"></i>
                        ' .__('lang.extract_customer_due') . '</a>
                        </li>';
                    }
                }
                if (auth()->user()->can('adjustment.customer_balance_adjustment.create_and_edit')) {
                    $html .=
                    '<li>
                    <a href="#"
                        ><i class="fa fa-adjust"></i>
                        ' .__('lang.adjust_customer_balance') . '</a>
                        </li>';
                }
                if ($row->is_default == 0){
                    if (auth()->user()->can('customer_module.customer.delete')) {
                        $html .=
                        '<li>
                        <a data-href="' .route('admin.customers.destroy', $row->id). '"
                        data-check_password="' .route('admin.check-password', auth('admin')->user()->id). '"
                        class="btn text-red delete_customer"><i class="fa fa-trash"></i>
                            ' .__('lang.delete') . '</a>
                            </li>';
                    }
                }
                $html .="</ul>";
                return $html;
            }
        )
          ->rawColumns([
            'customer_type',
            'image',
            'gender',
            'created_by',
            'mobile_number',
            'tax_location',
            'address',
            'balance','purchases','discount','joining_date','action'
        ])
        ->make(true);
        }
        $customer_types = CustomerType::orderBy('name', 'asc')->pluck('name', 'id');
        return view('customer::back-end.customers.index')->with(compact('customer_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): Factory|View|Application
    {

        $customer_types = CustomerType::pluck('name', 'id');

        $quick_add = request()->quick_add ?? null;
        $customers = Customer::getCustomerArrayWithMobile();
        $genders = Customer::getDropdownGender();
        $tax_locations=TaxLocation::pluck('name', 'id');
        if ($quick_add) {
            return view('customer::back-end.customers.quick_add')->with(compact(
                'customer_types',
                'customers',
                'tax_locations',
                'genders',
                'quick_add'
            ));
        }

        return view('customer::back-end.customers.create')->with(compact(
            'customer_types',
            'customers',
            'tax_locations',

            'genders',
            'quick_add'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|array
     */
    public function store(Request $request): RedirectResponse|array
    {
        $this->validate(
            $request,
            ['mobile_number' => ['required', 'max:255']],
            ['customer_type_id' => ['required', 'max:255']]
        );

         try {
            $data = $request->except('_token', 'quick_add', 'reward_system');
            $data['created_by'] = auth('admin')->user()->id;

            DB::beginTransaction();
            $customer = Customer::create($data);
            if ($request->has('image')) {
                $customer->addMedia($request->image)->toMediaCollection('customer_photo');
            }
            $customer_id = $customer->id;
            DB::commit();
            $output = [
                'success' => true,
                'customer_id' => $customer_id,
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


        if ($request->quick_add) {
            return $output;
        }

        return redirect()->route('admin.customers.index')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show(int $id)
    {
        $customer_id = $id;
        $customer = Customer::find($id);

        if (request()->ajax()) {
            $payment_types = $this->commonUtil->getPaymentTypeArrayForPos();
            $default_currency_id = System::getProperty('currency');
            $request = request();

            $query = Transaction::leftjoin('transaction_payments', 'transactions.id', 'transaction_payments.transaction_id')
                ->leftjoin('stores', 'transactions.store_id', 'stores.id')
                ->leftjoin('customers', 'transactions.customer_id', 'customers.id')
                ->leftjoin('customer_types', 'customers.customer_type_id', 'customer_types.id')
                ->leftjoin('transaction_sell_lines', 'transactions.id', 'transaction_sell_lines.transaction_id')
                ->leftjoin('products', 'transaction_sell_lines.product_id', 'products.id')
                ->leftjoin('admins', 'transactions.created_by', 'admins.id')
                ->leftjoin('currencies as received_currency', 'transactions.received_currency_id', 'received_currency.id')
                ->where('transactions.type', 'sell')->whereIn('status', ['final', 'canceled']);

            $query->where('customer_id', $id);
            if (!empty(request()->start_date)) {
                $query->where('transaction_date', '>=', request()->start_date);
            }
            if (!empty(request()->end_date)) {
                $query->where('transaction_date', '<=', request()->end_date);
            }

            $sales = $query->select(
                'transactions.final_total',
                'transactions.payment_status',
                'transactions.status',
                'transactions.id',
                'transactions.transaction_date',
                'transactions.service_fee_value',
                'transactions.invoice_no',
                'transactions.discount_amount',
                'transactions.rp_earned',
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
                'transaction_payments',
                'deliveryman',
                'canceled_by_admin',
                'sell_products'
            ])
                ->groupBy('transactions.id');

            return DataTables::of($sales)
                ->editColumn('transaction_date', '{{@format_date($transaction_date)}}')
                ->editColumn('invoice_no', function ($row) {
                    $string = $row->invoice_no . ' ';
                    if (!empty($row->return_parent)) {
                        $string .= '<a
                        data-href="' . route('admin.sale-return.show', $row->id) . '" data-container=".view_modal"
                        class="btn btn-modal" style="color: #007bff;">R</a>';
                    }
                    if ($row->payment_status == 'pending') {
                        $string .= '<a
                            data-href="' . route('admin.sale.show', $row->id) . '" data-container=".view_modal"
                            class="btn btn-modal" style="color: #007bff;">P</a>';
                    }

                    return $string;
                })
                ->editColumn('final_total', function ($row) use ($default_currency_id) {
                    if (!empty($row->return_parent)) {
                        $final_total = $this->commonUtil->num_f($row->final_total - $row->return_parent->final_total);
                    } else {
                        $final_total = $this->commonUtil->num_f($row->final_total);
                    }

                    return  $final_total ;
                })
                ->addColumn('paid', function ($row) use ($request, $default_currency_id) {
                    $amount_paid = 0;
                    if (!empty($request->get('method'))) {
                        $payments = $row->transaction_payments->where('method', $request->get('method'));
                    } else {
                        $payments = $row->transaction_payments;
                    }
                    foreach ($payments as $payment) {
                        $amount_paid += $payment->amount;
                    }

                    return  $this->commonUtil->num_f($amount_paid) ;
                })
                ->addColumn('due', function ($row) use ($default_currency_id) {
                    $paid = $row->transaction_payments->sum('amount');
                    $due = $row->final_total - $paid;

                    return $this->commonUtil->num_f($due);
                })
                ->addColumn('customer_type', function ($row) {
                    if (!empty($row->customer->customer_type)) {
                        return $row->customer->customer_type->name;
                    } else {
                        return '';
                    }
                })
                ->editColumn('discount_amount', function ($row) {
                    return num_format($row->discount_amount);
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
                ->addColumn('deliveryman', function ($row) {
                    if (!empty($row->deliveryman)) {
                        return $row->deliveryman->employee_name;
                    } else {
                        return '';
                    }
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
                    } elseif ($row->status == 'final' && $row->payment_status == 'pending') {
                        return '<span class="badge badge-warning">' . __('lang.pay_later') . '</span>';
                    } else {
                        return '<span class="badge badge-success">' . ucfirst($row->status) . '</span>';
                    }
                })
                ->addColumn('products', function ($row) {
                    $string = '';
                    foreach ($row->transaction_sell_lines as $line) {
                        $string .= '(' . $this->commonUtil->num_f($line->quantity) . ')';
                        if (!empty($line->product)) {
                        }
                        $string .= $line->product->name;
                        $string .= '<br>';
                    }


                    return $string;
                })
                ->editColumn('service_fee_value', '{{@num_format($service_fee_value)}}')
                ->editColumn('created_by', '{{$created_by_name}}')
                ->editColumn('canceled_by', function ($row) {
                    return !empty($row->canceled_by_user) ? $row->canceled_by_user->name : '';
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
//                        if (auth()->user()->can('sale.pos.create_and_edit')) {
//                            $html .=
//                                '<li>
//                                <a data-href="' . route('admin.sale.print', $row->id) . '"
//                                    class="btn print-invoice"><i class="dripicons-print"></i>
//                                    ' . __('lang.generate_invoice') . '</a>
//                            </li>';
//                        }
//
//                        if (auth()->user()->can('sale.pos.view')) {
//                            $html .=
//                                '<li>
//                                <a data-href="' . route('admin.sale.show', $row->id) . '" data-container=".view_modal"
//                                    class="btn btn-modal"><i class="fa fa-eye"></i> ' . __('lang.view') . '</a>
//                            </li>';
//                        }
//                        if (auth()->user()->can('sale.pos.create_and_edit')) {
//                            $html .=
//                                '<li>
//                                <a href="' . route('admin.pos.edit', $row->id) . '" class="btn"><i
//                                        class="dripicons-document-edit"></i> ' . __('lang.edit') . '</a>
//                            </li>';
//                        }
//                        if (auth()->user()->can('sale.pos.create_and_edit')) {
//                            $html .=
//                                '<li>
//                                <a data-href="' . route('admin.sell.print', $row->id) . '"
//                                    class="btn print-invoice"><i class="dripicons-print"></i>
//                                    ' . __('lang.generate_invoice') . '</a>
//                            </li>';
//                        }
//                        if (auth()->user()->can('sale.pos.create_and_edit')) {
//                            $html .=
//                                '<li>
//                                <a data-href="' . route('admin.sell.print', $row->id) . '?print_gift_invoice=true"
//                                    class="btn print-invoice"><i class="fa fa-gift"></i>
//                                    ' . __('lang.print_gift_invoice') . '</a>
//                            </li>';
//                        }
//                        if (auth()->user()->can('sale.pos.view')) {
//                            $html .=
//                                '<li>
//                                <a data-href="' . route('admin.sell.show', $row->id) . '" data-container=".view_modal"
//                                    class="btn btn-modal"><i class="fa fa-eye"></i> ' . __('lang.view') . '</a>
//                            </li>';
//                        }
//                        if (auth()->user()->can('sale.pos.create_and_edit')) {
//                            $html .=
//                                '<li>
//                                <a href="' . route('admin.sell.edit', $row->id) . '" class="btn"><i
//                                        class="dripicons-document-edit"></i> ' . __('lang.edit') . '</a>
//                            </li>';
//                        }
////                        if (auth()->user()->can('return.sell_return.create_and_edit')) {
////                            if (empty($row->return_parent)) {
////                                $html .=
////                                    '<li>
////                                    <a href="' . action('SellReturnController@add', $row->id) . '" class="btn"><i
////                                        class="fa fa-undo"></i> ' . __('lang.sale_return') . '</a>
////                                    </li>';
////                            }
////                        }
////                        if (auth()->user()->can('sale.pay.create_and_edit')) {
////                            if ($row->status != 'draft' && $row->payment_status != 'paid' && $row->status != 'canceled') {
////                                $html .=
////                                    ' <li>
////                                    <a data-href="' . action('TransactionPaymentController@addPayment', $row->id) . '"
////                                        data-container=".view_modal" class="btn btn-modal"><i class="fa fa-plus"></i>
////                                        ' . __('lang.add_payment') . '</a>
////                                    </li>';
////                            }
////                        }
////                        if (auth()->user()->can('sale.pay.view')) {
////                            $html .=
////                                '<li>
////                                <a data-href="' . action('TransactionPaymentController@show', $row->id) . '"
////                                    data-container=".view_modal" class="btn btn-modal"><i class="fa fa-money"></i>
////                                    ' . __('lang.view_payments') . '</a>
////                                </li>';
////                        }
//                        if (auth()->user()->can('sale.pay.delete')) {
//                            $html .=
//                                '<li>
//                                <a data-href="' . route('admin.sale.destroy', $row->id) . '"
//                                    data-check_password="' . route('admin.check-password', Auth::user()->id) . '"
//                                    class="btn text-red delete_item"><i class="fa fa-trash"></i>
//                                    ' . __('lang.delete') . '</a>
//                                </li>';
//                        }


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
                        if (auth()->user()->can('return.sell_return.create_and_edit')) {
                            //                            if (empty($row->return_parent)) {
                            $html .=
                                '<li>
                                    <a href="' . route('admin.saleReturn.add', $row->id) . '" class="btn"><i
                                        class="fa fa-undo"></i> ' . __('lang.sale_return') . '</a>
                                    </li>';
                            //                            }
                        }
                        if (auth()->user()->can('sale.pay.create_and_edit')) {
                            if ($row->status != 'draft' && $row->payment_status != 'paid' && $row->status != 'canceled') {
                                $final_total = $row->final_total;
                                if (!empty($row->return_parent)) {
                                    $final_total = $this->commonUtil->num_f($row->final_total - $row->return_parent->final_total);
                                }
                                if ($final_total > 0) {
                                    $html .=
                                        ' <li>
                                    <a data-href="' . route('admin.transaction.addPayment', $row->id) . '"
                                        data-container=".view_modal" class="btn btn-modal"><i class="fa fa-plus"></i>
                                        ' . __('lang.add_payment') . '</a>
                                    </li>';
                                }
                            }
                        }

                        if (auth()->user()->can('sale.pay.view')) {
                            $html .=
                                '<li>
                                <a data-href="' . route('admin.transaction-payment.show', $row->id) . '"
                                    data-container=".view_modal" class="btn btn-modal"><i class="fa fa-money"></i>
                                    ' . __('lang.view_payments') . '</a>
                                </li>';
                        }
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
                    'files',
                    'created_by',
                ])
                ->make(true);
        }
        $sale_return_query = Transaction::whereIn('transactions.type', ['sell_return'])
            ->whereIn('transactions.status', ['final']);

        if (!empty(request()->start_date)) {
            $sale_return_query->where('transaction_date', '>=', request()->start_date);
        }
        if (!empty(request()->end_date)) {
            $sale_return_query->where('transaction_date', '<=', request()->end_date);
        }
        if (!empty($customer_id)) {
            $sale_return_query->where('transactions.customer_id', $customer_id);
        }
        $sale_returns = $sale_return_query->select(
            'transactions.*'
        )->groupBy('transactions.id')->orderBy('transactions.id', 'desc')->get();


        $discount_query = Transaction::whereIn('transactions.type', ['sell'])
            ->whereIn('transactions.status', ['final'])
            ->where(function ($q) {
                $q->where('total_sp_discount', '>', 0);
                $q->orWhere('total_product_discount', '>', 0);
            });

        if (!empty(request()->start_date)) {
            $discount_query->where('transaction_date', '>=', request()->start_date);
        }
        if (!empty(request()->end_date)) {
            $discount_query->where('transaction_date', '<=', request()->end_date);
        }
        if (!empty($customer_id)) {
            $discount_query->where('transactions.customer_id', $customer_id);
        }
        $discounts = $discount_query->select(
            'transactions.*'
        )->groupBy('transactions.id')->get();


        $balance = $this->transactionUtil->getCustomerBalance($customer->id)['balance'];

        $transactions_ids = Transaction::
        where('transactions.type', 'sell')->whereIn('status', ['final', 'canceled'])
            ->where('customer_id', $id)->pluck('id');
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();

        $payments= TransactionPayment::wherein('transaction_id',$transactions_ids)->get();

        return view('customer::back-end.customers.show')->with(compact(
            'sale_returns',
            'discounts',
            'customer',
            'payment_type_array',
            'payments',
            'balance',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id): Factory|View|Application
    {
        $customer = Customer::find($id);
        $customer_types = CustomerType::pluck('name', 'id');
        $genders = Customer::getDropdownGender();
        $tax_locations=TaxLocation::pluck('name', 'id');

        return view('customer::back-end.customers.edit')->with(compact(
            'customer',
            'genders',
            'tax_locations',
            'customer_types',
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->validate(
            $request,
            ['mobile_number' => ['required', 'max:255']],
            ['customer_type_id' => ['required', 'max:255']]
        );

        try {
            $data = $request->except('_token', '_method');

            DB::beginTransaction();
            $customer = Customer::find($id);
            $customer->update($data);
            if ($request->has('image')) {
                if ($customer->getFirstMedia('customer_photo')) {
                    $customer->getFirstMedia('customer_photo')->delete();
                }
                $customer->addMedia($request->image)->toMediaCollection('customer_photo');
            }

            if (!empty($request->important_dates)) {
                $this->transactionUtil->createOrUpdateCustomerImportantDate($id, $request->important_dates);
            }

            DB::commit();
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

        return redirect()->route('admin.customers.index')->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    public function destroy(int $id): array
    {
        try {
            $customer = Customer::find($id);
            $customer_transactions = Transaction::where('customer_id', $id)->where('type', 'sell')->where('status', 'final')->get();

            foreach ($customer_transactions as $transaction) {
                $transaction_sell_lines = $transaction->transaction_sell_lines;
                foreach ($transaction_sell_lines as $transaction_sell_line) {
                    if ($transaction->status == 'final') {
                        $product = Product::find($transaction_sell_line->product_id);
                        if (!$product->is_service) {
                            $this->productUtil->updateProductQuantityStore($transaction_sell_line->product_id, $transaction_sell_line->variation_id, $transaction->store_id, $transaction_sell_line->quantity - $transaction_sell_line->quantity_returned);
                        }
                    }
                    $transaction_sell_line->delete();
                }
                Transaction::where('return_parent_id', $transaction->id)->delete();
                Transaction::where('parent_sale_id', $transaction->id)->delete();
                CashRegisterTransaction::where('transaction_id' , $transaction->id)->delete();

                $transaction->delete();
            }
            $customer->delete();


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

    public function getDropdown(): string
    {
        $customer = Customer::get();
        $html = '';
        if (!empty($append_text)) {
            $html = '<option value="">Please Select</option>';
        }
        foreach ($customer as $value) {
            $html .= '<option value="' . $value->id . '">' . $value->id_number . ' ' . $value->name . '</option>';
        }

        return $html;
    }

    public function getDetailsByTransactionType($customer_id, $type)
    {
        $query = Customer::leftjoin('transactions as t', 'customers.id', 't.customer_id')
            ->leftjoin('customer_types', 'customers.customer_type_id', 'customer_types.id')
            ->where('customers.id', $customer_id);
        if ($type == 'sell') {
            $query->select(
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as total_paid"),
                'customers.name',
                'customers.address',
                'customers.age',
                'customers.gender',
                'customers.deposit_balance',
                'customers.id as customer_id',
                'customer_types.name as customer_type'
            );
        }

        $customer_details = $query->first();
        $customer_details->due = $this->getCustomerBalance($customer_id)['balance'];
        $customer_details->gender = $customer_details->gender_name;

        return $customer_details;
    }

    /**
     * get customer balance
     *
     * @param int $customer_id
     * @return array|null
     */
    public function getCustomerBalance(int $customer_id): ?array
    {
        return $this->transactionUtil->getCustomerBalance($customer_id);
    }

    /**
     * Shows contact's payment due modal
     *
     * @param int $customer_id
     * @return Application|Factory|View|RedirectResponse
     */
    public function getPayContactDue(int $customer_id): Application|Factory|View|RedirectResponse
    {
        if (request()->ajax()) {

            $due_payment_type = request()->input('type');
            $query = Customer::where('customers.id', $customer_id)
                ->join('transactions AS t', 'customers.id', '=', 't.customer_id');
            $query->select(
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as total_paid"),
                'customers.name',
                'customers.mobile_number',
                'customers.id as customer_id'
            );


            $customer_details = $query->first();
            $payment_type_array = $this->commonUtil->getPaymentTypeArray();

            return view('customer::back-end.customers.partial.pay_customer_due')
                ->with(compact('customer_details', 'payment_type_array',));
        }
        return redirect()->back();
    }

    /**
     * Adds Payments for Contact due
     *
     * @param Request $request
     * @param int $customer_id
     * @return RedirectResponse
     */
    public function postPayContactDue(int $customer_id , Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $this->transactionUtil->payCustomer($request);

            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage()
            ];
        }

        return redirect()->back()->with(['status' => $output]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $transaction_payment_id
     * @return Application|Factory|View
     */
    public function paymentDuetEdit(int $transaction_payment_id): Factory|View|Application
    {
        $payment = DebtPayment::find($transaction_payment_id);
        $payment_type_array = $this->commonUtil->getPaymentTypeArray();
        return view('customer::back-end.customers.partial.edit_pay_customer_due')->with(compact(
            'payment',
            'payment_type_array'
        ));
    }

    /**
     * Adds Payments for Contact due
     *
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function UpdatePayContactDue($id,Request  $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $amount = $this->commonUtil->num_uf($request->amount);
            /**   delete  TransactionPayment and update  DebtPayment **/
            $debt_payment=  DebtPayment::whereId($id)->first();
            $debt_payment->amount=$amount;
            $debt_payment->method=$request->get('method');
            $debt_payment->ref_number=$request->ref_number;
            $debt_payment->paid_on=$this->commonUtil->uf_date($request->paid_on);
            $debt_payment->bank_deposit_date=!empty($request->bank_deposit_date) ? $this->commonUtil->uf_date($request->bank_deposit_date) : null;
            $debt_payment->bank_name=$request->bank_name;
            $debt_payment->created_by=auth()->id();
            $debt_payment->save();

            $customer_id=$debt_payment->customer_id;
            if ($request->upload_documents) {
                foreach ($request->file('upload_documents', []) as $key => $doc) {
                    $debt_payment->addMedia($doc)->toMediaCollection('transaction_payment');
                }
            }
            $old_DebtTransactionPayments = DebtTransactionPayment::where('debt_payment_id',$id)->get();

            foreach ($old_DebtTransactionPayments as $old_DebtTransactionPayments){
                $transaction_payment = TransactionPayment::find($old_DebtTransactionPayments->transaction_payment_id);
                $transaction_id = $transaction_payment->transaction_id;
                $transaction_payment->delete();
                $this->transactionUtil->updateTransactionPaymentStatus($transaction_id);
            }

            DebtTransactionPayment::where('debt_payment_id',$id)->delete();
            $transactions = Transaction::where('customer_id', $customer_id)
                ->where('type', 'sell')->whereIn('payment_status', ['pending', 'partial'])
                ->orderBy('transaction_date', 'asc')->get();

            $debt_payment_transactions=[];
            foreach ($transactions as $transaction) {
                $due_for_transaction = $this->getDueForTransaction($transaction->id);
                $paid_amount = 0;
                if ($amount > 0) {
                    if ($amount >= $due_for_transaction) {
                        $paid_amount = $due_for_transaction;
                        $amount -= $due_for_transaction;
                    } else if ($amount < $due_for_transaction) {
                        $paid_amount = $amount;
                        $amount = 0;
                    }

                    $payment_data = [
                        'transaction_payment_id' =>  null,
                        'transaction_id' =>  $transaction->id,
                        'amount' => $paid_amount,
                        'method' => $request->get('method'),
                        'paid_on' => $this->commonUtil->uf_date($request->paid_on),
                        'ref_number' => $request->ref_number,
                        'bank_deposit_date' => !empty($request->bank_deposit_date) ? $this->commonUtil->uf_date($request->bank_deposit_date) : null,
                        'bank_name' => $request->bank_name,
                    ];

                    $transaction_payment = $this->transactionUtil->createOrUpdateTransactionPayment($transaction, $payment_data);
                    DebtTransactionPayment::create([
                        'debt_payment_id'=>$debt_payment->id,
                        'transaction_payment_id'=>$transaction_payment->id,
                        'amount'=>$paid_amount,
                    ]);
                    $debt_payment_transactions[$transaction_payment->id]=$paid_amount;
                    $this->transactionUtil->updateTransactionPaymentStatus($transaction->id);
                    $this->cashRegisterUtil->addPayments($transaction, $payment_data, 'credit');

                    if ($request->upload_documents) {
                        foreach ($request->file('upload_documents', []) as $key => $doc) {
                            $transaction_payment->addMedia($doc)->toMediaCollection('transaction_payment');
                        }
                    }
                }
            }
            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage()
            ];
        }

        return redirect()->back()->with(['status' => $output]);
    }

    /**
     * Adds Payments for Contact due
     *
     * @param int $id
     * @return array
     */
    public function destroyPayContactDue(int $id): array
    {
        try {
            DB::beginTransaction();
            /**   delete  TransactionPayment and update  DebtPayment **/

            $old_DebtTransactionPayments = DebtTransactionPayment::where('debt_payment_id',$id)->get();
            foreach ($old_DebtTransactionPayments as $old_DebtTransactionPayment){
                $transaction_payment = TransactionPayment::find($old_DebtTransactionPayment->transaction_payment_id);
                $transaction_id = $transaction_payment->transaction_id;
                $transaction_payment->delete();
                $this->transactionUtil->updateTransactionPaymentStatus($transaction_id);
            }
            DebtTransactionPayment::where('debt_payment_id',$id)->delete();
            DebtPayment::whereId($id)->delete();
            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage()
            ];
        }

        return $output;
    }


    public function getImportantDateRow(): Factory|View|Application
    {
        $index = request()->index ?? 0;

        return view('customer::back-end.customers.partial.important_date_row')->with(compact(
            'index'
        ));
    }

    /**
     *  update customer address
     * @param int $id
     * @return array
     */
    public function updateAddress(int $id): array
    {
        try {
            $customer = Customer::find($id);
            $customer->address = request()->address;
            $customer->save();
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

    public function getDueForTransaction($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);
        $total_paid = Transaction::leftjoin('transaction_payments', 'transactions.id', 'transaction_payments.transaction_id')
            ->where('transactions.id', $transaction_id)
            ->sum('amount');

        return $transaction->final_total - $total_paid;
    }

    /**
     *  get Prescriptions To Customer By ID
     * @param int $id
     *
     */
    public function getPrescriptions(int $id)
    {
        if (request()->ajax()) {

            $query = Prescription::
            leftjoin('transaction_sell_lines', 'prescriptions.sell_line_id', 'transaction_sell_lines.id')
            ->leftjoin('transactions', 'transaction_sell_lines.transaction_id', 'transactions.id')
            ->leftjoin('admins', 'transactions.created_by', 'admins.id')
            ->leftjoin('products', 'prescriptions.product_id', 'products.id')
            ->where('prescriptions.customer_id', $id);

            if (!empty(request()->startdate)) {
                $query->where('prescriptions.date','>=', request()->startdate. ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
            }
            if (!empty(request()->enddate)) {
                $query->where('prescriptions.transaction_date','<=', request()->enddate. ' ' . Carbon::parse(request()->start_time)->format('H:i:s'));
            }

            $query->select(
                'prescriptions.*',
                'transactions.invoice_no',
                'transaction_sell_lines.sell_price as product_price_sell',
                'products.name as product_name',
                'products.sku as product_sku',
                'admins.name as created_by_name'
            );
            $prescriptions = $query->groupBy('prescriptions.id');
            return DataTables::of($prescriptions)
                ->addColumn('lens', function ($row) {
                    return $row->product_name.'|'.$row->product_sku;
                })
                ->addColumn('amount', function ($row) {
                    $data_len=json_decode($row->data);
                        if($data_len && $data_len != 'null'){
                            return num_format($row->product_price_sell);
                        }
                    return num_format(0);
                })
                ->addColumn('VA_amount', function ($row) {
                    $data_len=json_decode($row->data);
                    if($data_len && $data_len != 'null'){
                        return num_format($data_len->VA_amount->total);
                    }
                    return 0;
                })
                ->editColumn('created_by', '{{$created_by_name}}')

                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '';
                            if (auth()->user()->can('sale.pos.view')) {
                                $html .= ' <a data-href="' . route('admin.customers.getPrescriptionShow', [ 'prescription_id' => $row->id]) . '"
                                data-container=".view_modal"
                                class="btn btn-default btn-modal"><i class="fa fa-eye"></i>' . __('lang.view') . '</a>';
                            }
                        return $html;
                    }
                )
                ->rawColumns([
                    'lens',
                    'date',
                    'amount',
                    'VA_amount',
                    'created_by',
                    'action'
                ])
                ->make(true);
        }
        return null;
    }
    /**
     *  show Prescriptions   By ID
     * @param int $Prescription_id
     *
     */
    public function getPrescriptionShow(int $Prescription_id)
    {
            $prescription = Prescription::
            leftjoin('transaction_sell_lines', 'prescriptions.sell_line_id', 'transaction_sell_lines.id')
                ->leftjoin('transactions', 'transaction_sell_lines.transaction_id', 'transactions.id')
                ->leftjoin('admins', 'transactions.created_by', 'admins.id')
                ->leftjoin('products', 'prescriptions.product_id', 'products.id')
                ->where('prescriptions.id', $Prescription_id)
                ->select(
                'prescriptions.*',
                'transactions.invoice_no',
                'transaction_sell_lines.sell_price as product_price_sell',
                'products.name as product_name',
                'products.sku as product_sku',
                'admins.name as created_by_name'
            )->first();


        return view('customer::back-end.customers.partial.view_prescription')->with(compact(
            'prescription'
        ));
    }
}
