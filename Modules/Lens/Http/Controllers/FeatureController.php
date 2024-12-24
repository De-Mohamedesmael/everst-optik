<?php

namespace Modules\Lens\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Modules\Lens\Entities\Feature;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Lens\Entities\BrandLens;

class FeatureController extends Controller
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
        $features = Feature::get();

        return view('lens::back-end.feature.index')->with(compact(
            'features'
        ));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request): Factory|View|Application
    {
        $quick_add = $request->quick_add ?? null;
        $brand_lenses = BrandLens::orderBy('name', 'asc')->pluck('name', 'id');
        return view('lens::back-end.feature.create')->with(compact(
            'quick_add',
            'brand_lenses'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array|JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse|array|RedirectResponse
    {
        $this->validate(
            $request,
            ['name' => ['required', 'max:100']]
        );
        $feature_exist = Feature::where('name', $request->name)->exists();


        if ($feature_exist) {
            if ($request->ajax()) {
                return response()->json(array(
                    'success' => false,
                    'message' => translate('There are incorect values in the form!'),
                    'msg' => translate('Feature name already taken')
                ));
            }
        }
        try {
            DB::beginTransaction();
            $feature = Feature::create([
                'name'=>$request->name
            ]);
            if ($request->has("feature_images") && count($request->feature_images) > 0) {
                foreach ($request->feature_images as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $feature->addMedia($filePath)->toMediaCollection('icon');

                }
            }
            //icon_active
            if ($request->has("icon-active_images") && count($request->get('icon-active_images')) > 0) {
                foreach ($request->get('icon-active_images') as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $feature->addMedia($filePath)->toMediaCollection('icon_active');

                }
            }
            //before_effect
            if ($request->has("before-effect_images") && count($request->get('after-effect_images')) > 0) {
                foreach ($request->get('before-effect_images') as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $feature->addMedia($filePath)->toMediaCollection('before_effect');

                }
            }
            //after_effect
            if ($request->has("after-effect_images") && count($request->get('after-effect_images')) > 0) {
                foreach ($request->get('after-effect_images') as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $feature->addMedia($filePath)->toMediaCollection('after_effect');

                }
            }
            if( $request->has("brand_lens_id")) {
                $feature->brand_lenses()->attach($request->brand_lens_id);
            }

            $feature_id = $feature->id;
            DB::commit();
            $output = [
                'success' => true,
                'feature_id' => $feature_id,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
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
        $feature = Feature::find($id);
        $brand_lenses = BrandLens::orderBy('name', 'asc')->pluck('name', 'id');

        return view('lens::back-end.feature.edit')->with(compact(
            'feature',
            'brand_lenses'
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
            DB::beginTransaction();
            $feature = Feature::find($id);

            $feature->update([
                'name'=>$request->name
            ]);

            if ($request->has("feature_images") && count($request->feature_images) > 0) {
                foreach ($request->feature_images as $imageData) {
                    $feature->clearMediaCollection('icon');
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $feature->addMedia($filePath)->toMediaCollection('icon');

                }
            }
            //icon_active
            if ($request->has("icon-active_images") && count($request->get('icon-active_images')) > 0) {
                foreach ($request->get('icon-active_images') as $imageData) {
                    $feature->clearMediaCollection('icon_active');
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $feature->addMedia($filePath)->toMediaCollection('icon_active');

                }
            }
            //before_effect
            if ($request->has("before-effect_images") && count($request->get('after-effect_images')) > 0) {
                foreach ($request->get('before-effect_images') as $imageData) {
                    $feature->clearMediaCollection('before_effect');

                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $feature->addMedia($filePath)->toMediaCollection('before_effect');

                }
            }
            //after_effect
            if ($request->has("after-effect_images") && count($request->get('after-effect_images')) > 0) {
                foreach ($request->get('after-effect_images') as $imageData) {
                    $feature->clearMediaCollection('after_effect');

                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $feature->addMedia($filePath)->toMediaCollection('after_effect');

                }
            }
            if( $request->has("brand_lens_id")) {
                $feature->brand_lenses()->detach();
                $feature->brand_lenses()->sync($request->brand_lens_id);
            }
            DB::commit();
            $output = [
                'success' => true,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
            DB::rollback();
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
            Feature::find($id)->delete();
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

        $features = Feature::orderBy('name', 'asc')->pluck('name', 'id');
        return $this->commonUtil->createDropdownHtml($features);
    }




}
