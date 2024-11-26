<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerBalanceAdjustment;
use Modules\Setting\Entities\Store;
use App\Models\Admin;
use App\Utils\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerBalanceAdjustmentController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected Util $commonUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @return void
     */
    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer_balance_adjustments = CustomerBalanceAdjustment::get();

        return view('customer_balance_adjustment.index')->with(compact(
            'customer_balance_adjustments'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stores = Store::getDropdown();
        $admins = Admin::pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');

        return view('customer_balance_adjustment.create')->with(compact(
            'stores',
            'admins',
            'customers',
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->except('_token');
            $data['current_balance'] = $this->commonUtil->num_uf($data['current_balance']);
            $data['add_new_balance'] = $this->commonUtil->num_uf($data['add_new_balance']);
            $data['new_balance'] = $this->commonUtil->num_uf($data['new_balance']);
            $data['date_and_time'] = Carbon::now();
            $data['created_by'] = $request->admin_id;

            CustomerBalanceAdjustment::create($data);
            //TODO::total_rp need to update or not based on client response
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
        $stores = Store::getDropdown();
        $admins = Admin::pluck('name', 'id');
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        $customer_balance_adjustment = CustomerBalanceAdjustment::find($id);

        return view('customer_balance_adjustment.edit')->with(compact(
            'stores',
            'admins',
            'customers',
            'customer_balance_adjustment'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->except('_token', '_method');
            $data['current_balance'] = $this->commonUtil->num_uf($data['current_balance']);
            $data['add_new_balance'] = $this->commonUtil->num_uf($data['add_new_balance']);
            $data['new_balance'] = $this->commonUtil->num_uf($data['new_balance']);
            $data['created_by'] = $request->admin_id;
            CustomerBalanceAdjustment::where('id', $id)->update($data);

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
        try {
            CustomerBalanceAdjustment::find($id)->delete();
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
}
