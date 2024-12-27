<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\Prescription;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Lens\Entities\Feature;

class PrescriptionController extends Controller
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
        $prescriptions= Prescription::get();
        return view('customer::back-end.prescription.index')->with(compact(
            'prescriptions'
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
        $customer_id=$request->customer_id?? null;
        $customers = Customer::orderBy('name', 'asc')->pluck('name', 'id');
        return view('customer::back-end.prescription.create')->with(compact(
            'quick_add',
            'customer_id',
            'customers'
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
            ['doctor_name' => ['required', 'max:100']],
            ['date' => ['required']]
        );


        try {
            $data = $request->except('_token', 'quick_add','cropImages');
            DB::beginTransaction();
            $prescription = Prescription::create($data);
            if ($request->has("image")) {
                $prescription->addMediaFromRequst('image')->toMediaCollection('image');
            }

            $prescription_id = $prescription->id;
            DB::commit();
            $output = [
                'success' => true,
                'prescription_id' => $prescription_id,
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
        $prescription = Prescription::find($id);
        $features = Feature::orderBy('name', 'asc')->pluck('name', 'id');

        return view('lens::back-end.prescription.edit')->with(compact(
            'prescription',
            'features'
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
            $data = $request->except('_token','cropImages','feature_id', '_method');
            DB::beginTransaction();
            $prescription = Prescription::find($id);

            $prescription->update($data);
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach (getCroppedImages($request->cropImages) as $imageData) {
                    $prescription->clearMediaCollection('icon');
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $prescription->addMedia($filePath)->toMediaCollection('icon');
                }
            }

            if( $request->has("feature_id")) {
                $prescription->features()->detach();
                $prescription->features()->sync($request->feature_id);
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
            Prescription::find($id)->delete();
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

        $prescriptions= Prescription::when(\request()->customer_id , function ($q){
            $q->where('customer_id', \request()->customer_id);
        })->orderBy('date', 'asc')
            ->selectRaw("CONCAT(doctor_name, ' - ', date) AS name, id")
            ->pluck('name', 'id');
        return $this->commonUtil->createDropdownHtml($prescriptions);
    }

    /**
     * show brands with features list .
     *
     * @return Application|Factory|View
     */
    public function showWithFeatures()
    {
        $prescription = Prescription::with('features')->get();
        return view('lens::back-end.prescription.partial.brands_with_features')->with(compact(
            'prescription'
        ));
    }


}
