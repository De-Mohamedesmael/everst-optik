<?php

namespace Modules\Hr\Http\Controllers;

use App\Http\Controllers\Controller;
use  App\Models\Admin;
use  Modules\Hr\Entities\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JobController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = JobType::leftjoin('admins', 'job_types.created_by', 'admins.id')->select('job_types.*', 'admins.name as created_by')->get();
// "main_modules" in "permissions table"
        $modulePermissionArray = Admin::modulePermissionArray();
        // "sub_modules of Admin" in "permissions table"
        $subModulePermissionArray = Admin::subModulePermissionArray();
        return view('hr::back-end.jobs.index')->with(compact(
            'modulePermissionArray',
            'subModulePermissionArray',
            'jobs'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hr::back-end.jobs.create');
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

            $data['date_of_creation'] = date('Y-m-d');
            $data['created_by'] = Auth::user()->id;

            JobType::create($data);

            $output = [
                'success' => true,
                'msg' => __('lang.job_added')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong')
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
        $job = JobType::find($id);
        $modulePermissionArray = Admin::modulePermissionArray();
        // "sub_modules of Admin" in "permissions table"
        $subModulePermissionArray = Admin::subModulePermissionArray();
        return view('hr::back-end.jobs.edit')->with(compact(
            'modulePermissionArray',
            'subModulePermissionArray',
            'job'
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
            JobType::where('id', $id)->update($data);

            $output = [
                'success' => true,
                'msg' => __('lang.job_updated')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong')
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

            JobType::where('id', $id)->delete();

            $output = [
                'success' => true,
                'msg' => __('lang.job_deleted')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return $output;
    }
}
