<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $money_safe=MoneySafe::latest()->get();
        $stores=Store::getDropdown();
        $currenciesId=[System::getProperty('currency') ,2];
        $selected_currencies=Currency::whereIn('id',$currenciesId)->orderBy('id','desc')->pluck('currency','id');
        $settings = System::pluck('value', 'key');
        return view('setting::back-end.money_safe.index',compact('settings','money_safe','stores','selected_currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MoneySafeRequest $request)
    {
        try {
            $data = $request->except('_token');
            $data['created_by'] = Auth::admin()->id;
            $money_safe=MoneySafe::create($data);
            $output = [
              'success' => true,
              'msg' => __('lang.success')
          ];
          }catch (\Exception $e) {
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $money_safe=MoneySafe::find($id);
        $stores=Store::getDropdown();
        $currenciesId=[System::getProperty('currency') ,2];
        $selected_currencies=Currency::whereIn('id',$currenciesId)->orderBy('id','desc')->pluck('currency','id');
        return view('setting::back-end.money_safe.edit')->with(compact('money_safe','stores','selected_currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MoneySafeUpdateRequest $request, $id)
    {
        try {
            $data = $request->except('_token');
            $data['edited_by'] = Auth::admin()->id;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $money_safe=MoneySafe::find($id);
            $money_safe->deleted_by=Auth::admin()->id;
            $money_safe->save();
            $money_safe->delete();
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
    public function getAddMoneyToSafe($money_safe_id){
        $jobs = JobType::pluck('title', 'id')->toArray();
        $stores=Store::getDropdown();
        $admins = Admin::Notview()->pluck('name', 'id');
        $safe = MoneySafe::find($money_safe_id);
        $currency_symbol=$safe->currency->symbol;
        return view('setting::back-end.money_safe.add_money')->with(compact('jobs','stores','admins','money_safe_id','currency_symbol'));
    }
    public function postAddMoneyToSafe(Request $request){
        try {
            $data = $request->except('_token');
            $data['created_by'] = Auth::admin()->id;
            $data['type'] = 'add_money';
            $safe = MoneySafe::find($request->money_safe_id);
            $transaction=$safe->transactions()->latest()->first();
            if(!empty($transaction->balance)){
                $data['balance']=$data['amount'] + $transaction->balance;
            }else{
                $data['balance']=$data['amount'];
            }

            $money_safe_transaction=MoneySafeTransaction::create($data);
            $output = [
              'success' => true,
              'msg' => __('lang.success')
          ];
          }catch (\Exception $e) {
              Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
              $output = [
                  'success' => false,
                  'msg' => __('lang.something_went_wrong')
              ];
          }
          return redirect()->back()->with('status', $output);
    }
    ////
    public function getTakeMoneyFromSafe($money_safe_id){
        $jobs = JobType::pluck('title', 'id')->toArray();
        $stores=Store::getDropdown();
        $admins = Admin::Notview()->pluck('name', 'id');
        $safe = MoneySafe::find($money_safe_id);
        $currency_symbol=$safe->currency->symbol;
        return view('setting::back-end.money_safe.take_money')->with(compact('jobs','stores','admins','money_safe_id','currency_symbol'));
    }
    public function postTakeMoneyFromSafe(Request $request){
        try {
            $data = $request->except('_token');
            $data['created_by'] = Auth::admin()->id;
            $data['type'] = 'take_money';
            $safe = MoneySafe::find($request->money_safe_id);
            $transaction=$safe->transactions()->latest()->first();
            if(!empty($transaction->balance)){
                $data['balance']=$transaction->balance-$data['amount'] ;
            }else{
                $data['balance']=0;
            }
            $money_safe_transaction=MoneySafeTransaction::create($data);
            $output = [
              'success' => true,
              'msg' => __('lang.success')
          ];
          }catch (\Exception $e) {
              Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
              $output = [
                  'success' => false,
                  'msg' => __('lang.something_went_wrong')
              ];
          }
          return redirect()->back()->with('status', $output);
    }
    ///
    public function getMoneySafeTransactions($id){
        $moneySafeTransactions = MoneySafe::
            when(request()->start_date != null, function ($query) {
                $query->with(['transactions' => function ($query) {
                    $query->whereBetween('transaction_date', [request()->start_date,request()->end_date])->latest();
                }]);
            })->
            when((request()->end_date == null)&& (request()->start_date == null), function ($query) {
                $query->with(['transactions' => function ($query) {
                    $query->latest();
                }]);
            })
            ->where('id', $id)
            ->first();

            $basic_currency=Currency::find($moneySafeTransactions->currency_id)->symbol;
            $default_currency=$moneySafeTransactions->currency_id=='2'?Currency::find(System::getProperty('currency'))->symbol:Currency::find(2)->symbol;
        return view('setting::back-end.money_safe.money_safe_transactions',
        compact('moneySafeTransactions','basic_currency','default_currency'));
    }
}
