<?php

namespace Modules\Lens\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Modules\Lens\Entities\BrandLens;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Lens\Entities\Feature;

class BrandLensController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected Util $commonUtil;

    /**
     * Constructor
     *
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
    public function index(): Factory|View|Application
    {
        $brand_lenses = BrandLens::withCount('features')->get();

        return view('lens::back-end.brand_lens.index')->with(compact(
            'brand_lenses'
        ));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(Request $request): Factory|View|Application
    {
        $quick_add = $request->quick_add ?? null;
        $features = Feature::orderBy('name', 'asc')->pluck('name', 'id');
        return view('lens::back-end.brand_lens.create')->with(compact(
            'quick_add',
            'features'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array|JsonResponse|RedirectResponse
     */
    public function store(Request $request): JsonResponse|array|RedirectResponse
    {
        $this->validate(
            $request,
            ['name' => ['required', 'max:100']]
        );

        $brand_lens_exist = BrandLens::where('name', $request->name)->exists();


        if ($brand_lens_exist) {
            if ($request->ajax()) {
                return response()->json(array(
                    'success' => false,
                    'message' => translate('There are incorect values in the form!'),
                    'msg' => translate('BrandLens name already taken')
                ));
            }
        }
        try {
            $data = $request->except('_token', 'quick_add');
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];

            DB::beginTransaction();
            $brand_lens = BrandLens::create($data);

            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($request->cropImages as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $brand_lens->addMedia($filePath)->toMediaCollection('icon');

                }
            }
            if ($request->has("second_icon") && count($request->second_icon) > 0) {
                foreach ($request->second_icon as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $brand_lens->addMedia($filePath)->toMediaCollection('second_icon');

                }
            }
           if( $request->has("feature_id")) {
                $brand_lens->features()->attach($request->feature_id);
            }

            $brand_lens_id = $brand_lens->id;
            DB::commit();
            $output = [
                'success' => true,
                'brand_lens_id' => $brand_lens_id,
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
     * @return void
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
    public function edit($id)
    {
        $brand_lens = BrandLens::find($id);
        return view('lens::back-end.brand_lens.edit')->with(compact(
            'brand_lens'
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
            ['name' => ['required', 'max:100']]
        );

        try {
            $data = $request->except('_token', '_method');
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];
            DB::beginTransaction();
            $brand_lens = BrandLens::find($id);

            $brand_lens->update($data);
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach (getCroppedImages($request->cropImages) as $imageData) {
                    $brand_lens->clearMediaCollection('icon');
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $brand_lens->addMedia($filePath)->toMediaCollection('icon');
                }
            }
            if ($request->has("second_icon") && count($request->second_icon) > 0) {
                foreach ($request->second_icon as $imageData) {
                    $brand_lens->clearMediaCollection('second_icon');
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $brand_lens->addMedia($filePath)->toMediaCollection('second_icon');

                }
            }
            if( $request->has("feature_id")) {
                $brand_lens->features()->attach($request->feature_id);
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
            BrandLens::find($id)->delete();
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

        $brand_lenses = BrandLens::orderBy('name', 'asc')->pluck('name', 'id');
        return $this->commonUtil->createDropdownHtml($brand_lenses);
    }




}
