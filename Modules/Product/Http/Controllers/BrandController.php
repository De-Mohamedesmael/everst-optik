<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Setting\Entities\Brand;

class BrandController extends Controller
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $brands = Brand::get();

        return view('product::back-end.brands.index')->with(compact(
            'brands'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $quick_add = request()->quick_add ?? null;

        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');

        return view('product::back-end.brands.create')->with(compact(
            'quick_add',
            'brands'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']]
        );

        $brand_exist = Brand::where('name', $request->name)->first();

        if (!empty($brand_exist)) {
            if ($request->ajax()) {
                return response()->json(array(
                    'success' => false,
                    'message' => 'There are incorect values in the form!',
                    'msg' => 'Brand name already taken'
                ));
            }
        }

        try {
            $data = $request->except('_token', 'quick_add');
            DB::beginTransaction();
            $brand = Brand::create($data);

            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($request->cropImages as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $brand->addMedia($filePath)->toMediaCollection('brand');

                }
            }

            $brand_id = $brand->id;

            DB::commit();
            $output = [
                'success' => true,
                'brand_id' => $brand_id,
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $brand = Brand::find($id);
        $brands = Brand::orderBy('name', 'asc')->pluck('name', 'id');

        return view('product::back-end.brands.edit')->with(compact(
            'brand',
            'brands',
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']]
        );

        try {
            $data = $request->except('_token', '_method');

            DB::beginTransaction();
            $brand = Brand::find($id);

            $brand->update($data);

            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach (getCroppedImages($request->cropImages) as $imageData) {
                    $brand->clearMediaCollection('brand');
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $brand->addMedia($filePath)->toMediaCollection('brand');
                }
            }

            DB::commit();
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
            Brand::find($id)->delete();
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

    public function getDropdown()
    {

        $brand = Brand::orderBy('name', 'asc')->pluck('name', 'id');

        return $this->commonUtil->createDropdownHtml($brand, 'Please Select');
    }


}
