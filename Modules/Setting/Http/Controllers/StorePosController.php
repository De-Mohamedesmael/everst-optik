<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\System;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Setting\Entities\StorePos;
use function PHPUnit\Framework\isFalse;

class StorePosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $store_poses = StorePos::all();
        return view('setting::back-end.store_pos.index')->with(compact('store_poses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): Factory|View|Application
    {
        $stores = Store::getDropdown();
        $admins = Admin::orderBy('name', 'asc')->pluck('name', 'id');
           return view('setting::back-end.store_pos.create')
               ->with(compact('stores', 'admins'));
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
            $store_pos = StorePos::create($request->except('_token'));
            $store_pos->created_by   = Auth::user()->id ;

            System::Create([
                'key' => 'weight_product'.$store_pos->id,
                'value' => 0,
                'date_and_time' => Carbon::now(),
                'created_by' => Auth::user()->id
            ]);

            $output = [
                'success' => true,
                'store_id' => $store_pos->id,
                'msg' => __('lang.success')
            ];
        }

        catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }
        return redirect()->back()->with('status', $output);

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id): Factory|View|Application
    {
        $store_pos = StorePos::find($id);

        $stores = Store::getDropdown();
        $admins = Admin::orderBy('name', 'asc')->pluck('name', 'id');

        return view('setting::back-end.store_pos.edit')
            ->with(compact('store_pos', 'stores', 'admins'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $store_pos = StorePos::where('id', $id)->update($request->except('_token', '_method'));

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
     * @return array
     */
    public function destroy($id)
    {
        try {
            StorePos::find($id)->delete();
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

    /**
     * get store pos details by store id
     *
     * @param int $store_id
     * @return void
     */
    public function getPosDetailsByStore($store_id)
    {
        $store_pos = StorePos::where('store_id', $store_id)
            ->where('admin_id', Auth::user()->id)->first();
        if(empty($store_pos)){
            $store_pos = StorePos::where('store_id', $store_id)->first();
        }
        return $store_pos;
    }
}
