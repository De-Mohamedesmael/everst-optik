<?php

namespace Modules\Setting\Http\Controllers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Setting\Http\Requests\MoneySafeRequest;
use Modules\Setting\Http\Requests\MoneySafeUpdateRequest;
use Modules\Setting\Entities\Currency;
use Modules\Hr\Entities\JobType;
use Modules\Setting\Entities\MoneySafe;
use Modules\Setting\Entities\MoneySafeTransaction;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\System;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Utils\Util;
class MoneySafeController extends Controller
{
    protected Util $Util;

    /**
     * Constructor
     *
     * @param Util $Util
     */
    public function __construct(Util $Util)
    {
        $this->Util = $Util;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $money_safe=MoneySafe::latest()->get();
        $stores=Store::getDropdown();
        $selected_currencies=Currency::orderBy('id','desc')->pluck('currency','id');
        $settings = System::pluck('value', 'key');
        return view('setting::back-end.money_safe.index',compact('settings','money_safe','stores','selected_currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MoneySafeRequest $request
     * @return RedirectResponse
     */
    public function store(MoneySafeRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token');
            $data['created_by'] = Auth::guard('admin')->id();
            MoneySafe::create($data);
            DB::commit();
            $output = [
              'success' => true,
              'msg' => __('lang.success')
          ];
          }catch (\Exception $e) {
            DB::rollBack();
              Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
              $output = [
                  'success' => false,
                  'msg' => __('lang.something_went_wrong')
              ];
          }
          return redirect()->back()->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id): Factory|View|Application
    {
        $money_safe=MoneySafe::find($id);
        $stores=Store::getDropdown();
        $selected_currencies=Currency::orderBy('id','desc')->pluck('currency','id');
        return view('setting::back-end.money_safe.edit')->with(compact('money_safe','stores','selected_currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MoneySafeUpdateRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(MoneySafeUpdateRequest $request, int $id): RedirectResponse
    {
        try {
            $data = $request->except('_token');
            $data['edited_by'] = Auth::guard('admin')->id();
            MoneySafe::find($id)->update($data);
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

        return redirect()->back()->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response|array
     */
    public function destroy(int $id): Response|array
    {
        try{
            $money_safe=MoneySafe::find($id);
            $money_safe->deleted_by=Auth::guard('admin')->id();
            $money_safe->save();
            $money_safe->delete();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            dd($e);
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }
        return $output;
    }
    public function getAddMoneyToSafe($money_safe_id): Factory|View|Application
    {
        $jobs = JobType::pluck('job_title', 'id')->toArray();
        $stores=Store::getDropdown();
        $admins = Admin::pluck('name', 'id');
        $safe = MoneySafe::find($money_safe_id);
        $currency_symbol=$safe->currency->symbol;

        return view('setting::back-end.money_safe.add_money')->with(compact('jobs','stores','admins','money_safe_id','currency_symbol'));
    }
    public function postAddMoneyToSafe(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token');
            $data['created_by'] = Auth::guard('admin')->id();
            $data['type'] = 'add_money';
            $safe = MoneySafe::find($request->money_safe_id);
            $transaction=$safe->transactions()->latest()->first();
            if(!empty($transaction->balance)){
                $data['balance']=$data['amount'] + $transaction->balance;
            }else{
                $data['balance']=$data['amount'];
            }

            $money_safe_transaction=MoneySafeTransaction::create($data);
            DB::commit();
            $output = [
              'success' => true,
              'msg' => __('lang.success')
          ];
          }catch (\Exception $e) {
            DB::rollBack();
              Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
              $output = [
                  'success' => false,
                  'msg' => __('lang.something_went_wrong')
              ];
          }
          return redirect()->back()->with('status', $output);
    }
    ////
    public function getTakeMoneyFromSafe($money_safe_id): Factory|View|Application
    {
        $jobs = JobType::pluck('job_title', 'id')->toArray();
        $stores=Store::getDropdown();
        $admins = Admin::pluck('name', 'id');
        $safe = MoneySafe::find($money_safe_id);
        $currency_symbol=$safe->currency->symbol;
        return view('setting::back-end.money_safe.take_money')->with(compact('jobs','stores','admins','money_safe_id','currency_symbol'));
    }
    public function postTakeMoneyFromSafe(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token');
            $data['created_by'] = Auth::guard('admin')->id();
            $data['type'] = 'take_money';
            $safe = MoneySafe::find($request->money_safe_id);
            $transaction=$safe->transactions()->latest()->first();
            if(!empty($transaction->balance)){
                $data['balance']=$transaction->balance-$data['amount'] ;
            }else{
                $data['balance']=0;
            }
            MoneySafeTransaction::create($data);
            DB::commit();
            $output = [
              'success' => true,
              'msg' => __('lang.success')
          ];
          }catch (\Exception $e) {
            DB::rollBack();
              Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
              $output = [
                  'success' => false,
                  'msg' => __('lang.something_went_wrong')
              ];
          }
          return redirect()->back()->with('status', $output);
    }
    ///
    public function getMoneySafeTransactions($id): Factory|View|Application
    {
        $moneySafeTransactions = MoneySafe::with(['transactions' => function ($query) {
            if (request()->start_date) {
                $query->where('transaction_date', '>=', request()->start_date);
            }

            if (request()->end_date) {
                $query->where('transaction_date', '<=', request()->end_date);
            }
            $query->latest();
        }])->where('id', $id)->first();

        $basic_currency=Currency::find($moneySafeTransactions->currency_id)->symbol;
            $default_currency=$moneySafeTransactions->currency_id=='2'?Currency::find(System::getProperty('currency'))->symbol:Currency::find(2)->symbol;
        return view('setting::back-end.money_safe.money_safe_transactions',
        compact('moneySafeTransactions','basic_currency','default_currency'));
    }
}
