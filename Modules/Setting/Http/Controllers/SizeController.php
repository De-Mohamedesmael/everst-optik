<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Modules\Setting\Entities\Size;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SizeController extends Controller
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
     * @return Application|Factory|View
     */
    public function index()
    {
        $sizes = Size::get();

        return view('setting::back-end.sizes.index')->with(compact(
            'sizes'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $quick_add = request()->quick_add ?? null;

        $sizes = Size::orderBy('name', 'asc')->pluck('name', 'id');

        return view('setting::back-end.sizes.create')->with(compact(
            'quick_add',
            'sizes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|array
     */
    public function store(Request $request)
    {

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', 'quick_add');

            DB::beginTransaction();
            $size = Size::create($data);

            $size_id = $size->id;

            DB::commit();
            $output = [
                'success' => true,
                'size_id' => $size_id,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __('lang.something_went_wrong')
            ];
        }


        if ($request->quick_add) {
            return $output;
        }

        return redirect()->back()->with('status', $output);
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
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {

        $size = Size::find($id);

        return view('setting::back-end.sizes.edit')->with(compact(
            'size'
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
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', '_method');

            DB::beginTransaction();
            Size::where('id', $id)->update($data);


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
            Size::find($id)->delete();
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
        $size = Size::orderBy('name', 'asc')->pluck('name', 'id');
        return $this->commonUtil->createDropdownHtml($size, 'Please Select');
    }
}
