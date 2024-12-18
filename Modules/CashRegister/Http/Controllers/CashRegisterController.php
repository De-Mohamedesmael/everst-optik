<?php

namespace Modules\CashRegister\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Modules\Product\Utils\ProductUtil;
use Modules\CashRegister\Entities\CashRegister;
use Modules\CashRegister\Entities\CashRegisterTransaction;
use Modules\Setting\Entities\MoneySafe;
use Modules\Setting\Entities\MoneySafeTransaction;
use Modules\Setting\Entities\StorePos;
use Modules\Setting\Entities\System;
use App\Models\Admin;
use App\Utils\CashRegisterUtil;
use App\Utils\NotificationUtil;
use App\Utils\TransactionUtil;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CashRegisterController extends Controller
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
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function create()
    {
        //Check if there is a open register, if yes then redirect to POS screen.
        if ($this->cashRegisterUtil->countOpenedRegister() != 0) {
            return redirect()->route('admin.pos.create');
        }

        $is_pos = request()->is_pos;
        $admins = Admin::orderBy('name', 'asc')->pluck('name', 'id');

        return view('cashregister::back-end.cash_register.create')->with(compact('is_pos', 'admins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $initial_amount = 0;
            if (!empty($request->input('amount'))) {
                $initial_amount = $this->cashRegisterUtil->num_uf($request->input('amount'));
            }
            DB::beginTransaction();
            $admin_id = Auth::user()->id;
            $store_pos = StorePos::where('admin_id', $admin_id)->first();

            $register = $this->cashRegisterUtil->getCurrentCashRegister($admin_id);
            if (!empty($register)) {
                return redirect()->route('admin.pos.create');
            }
            $register = CashRegister::create([
                'admin_id' => $admin_id,
                'status' => 'open',
                'store_id' => !empty($store_pos) ? $store_pos->store_id : null,
                'store_pos_id' => !empty($store_pos) ? $store_pos->id : null
            ]);
            $cash_register_transaction = $this->cashRegisterUtil->createCashRegisterTransaction($register, $initial_amount, 'cash_in', 'debit', $request->source_id, $request->notes);

            if (!empty($request->source_id)) {
                if ($request->source_type == 'user') {
                    $register = $this->cashRegisterUtil->getCurrentCashRegisterOrCreate($request->source_id);
                    $cash_register_transaction_out = $this->cashRegisterUtil->createCashRegisterTransaction($register, $initial_amount, 'cash_out', 'credit', $admin_id, $request->notes, $cash_register_transaction->id);
                    $cash_register_transaction->referenced_id = $cash_register_transaction_out->id;
                    $cash_register_transaction->save();
                }
                if ($request->source_type == 'safe') {
                    $default_currency_id = System::getProperty('currency');
                    $money_safe = MoneySafe::find($request->source_id);

                    $money_safe_data['money_safe_id'] = $money_safe->id;
                    $money_safe_data['transaction_date'] = Carbon::now();
                    $money_safe_data['transaction_id'] = null;
                    $money_safe_data['transaction_payment_id'] = null;
                    $money_safe_data['currency_id'] = $default_currency_id;
                    $money_safe_data['type'] = 'debit';
                    $money_safe_data['store_id'] = $register->store_id ?? 0;
                    $money_safe_data['amount'] = $initial_amount;
                    $money_safe_data['created_by'] = Auth::user()->id;
                    $money_safe_data['comments'] = __('lang.cash_in_hand');
                    MoneySafeTransaction::create($money_safe_data);
                }
            }
            if ($request->has('image')) {
                $cash_register_transaction->addMedia($request->image)->toMediaCollection('cash_register');
                $cash_register_transaction_out->addMedia($request->image)->toMediaCollection('cash_register');
            }
            DB::commit();
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];

            return redirect()->back()->with('status', $output);
        }

        return redirect()->route('admin.pos.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * get cash register for user in date time
     *
     * @param int $admin_id
     * @return array
     */
    public function getAvailableCashRegister(int $admin_id): array
    {

        if (!empty(request()->payment_date)) {

            $payment_date = Carbon::createFromTimestamp(strtotime(request()->payment_date));
            $crt = CashRegisterTransaction::where('transaction_id', request()->transaction_id)->first();

            $cash_register = CashRegister::where('admin_id', $admin_id)
                ->where('created_at', '<=', $payment_date)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!empty($cash_register) && !empty($crt)) {
                if ($crt->cash_register_id == $cash_register->id) {
                    return [
                        'success' => true,
                        'msg' => __('lang.success')
                    ];
                }
            }

            if (!empty($cash_register)) {
                return [
                    'success' => true,
                    'msg' => __('lang.success'),
                    'cash_register' => $cash_register
                ];
            }
        }

        return [
            'success' => false,
            'msg' => __('lang.no_session_found_for_this_date_and_time')
        ];
    }
}
