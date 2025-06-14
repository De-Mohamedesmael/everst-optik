<?php

namespace Modules\Factory\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
// use Illuminate\Routing\Controller;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Modules\Factory\Entities\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Modules\Lens\Entities\BrandLens;
use Modules\Lens\Entities\Focus;
use Modules\Lens\Entities\Design;
use Modules\Lens\Entities\IndexLens;
use Modules\Setting\Entities\Color;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\SpecialBase;
use Modules\Setting\Entities\SpecialAddition;
use Illuminate\Support\Facades\Cache;
use Modules\Factory\Mail\LensOrderMail;
use Illuminate\Support\Facades\Mail;



class FactoryController extends Controller
{

    public function index()
    {
        
        return view('factory::back-end.factories.index');
    }

    public function create()
    {
        $countries = Country::pluck('arabic', 'id');
        return view('factory::back-end.factories.create')->with(compact('countries'));            
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => ['required', 'max:250'],
                'email' => ['required', 'email', 'max:250'],
                'address' => ['nullable', 'string', 'max:250'],
                'country_id' => ['required', 'integer', 'exists:countries,id'],
                'code' => ['required', 'string', 'unique:factories,code'],
                'postal_code' => ['nullable', 'string', 'unique:factories,postal_code'],
                'phone' => ['nullable', 'max:250'],
                'owner_name' => ['nullable', 'string', 'max:250']
            ],
        );

        try {
            $data = $request->except('_token', 'quick_add', 'reward_system');
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];
            $data['created_by'] = auth('admin')->user()->id;

            DB::beginTransaction();
            $factory = Factory::create($data);
            if ($request->has('image')) {
                $customer->addMedia($request->image)->toMediaCollection('customer_photo');
            }
            $factory_id = $factory->id;
            DB::commit();
            $output = [
                'success' => true,
                'factory_id' => $factory_id,
                'msg' => __('lang.success')
            ];
        } catch (\Exception $e) {
             DB::rollBack();
             Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
             $output = [
                 'success' => false,
                 'msg' => __('lang.something_went_wrong')
             ];
        }


        if ($request->quick_add) {
            return $output;
        }

        return redirect()->route('admin.factories.index')->with('status', $output);
    }

    public function edit(int $id)
    {
        $factory = Factory::find($id);
        $countries = Country::pluck('arabic', 'id');

        return view('factory::back-end.factories.edit')->with(compact('factory','countries'));
    }

    public function update(Request $request, int $id)
    {

        $this->validate(
            $request,
            [
                'name' => ['required', 'max:250'],
                'email' => ['required', 'email', 'max:250'],
                'address' => ['nullable', 'string', 'max:250'],
                'country_id' => ['required', 'integer', 'exists:countries,id'],
                'code' => ['required', 'string', Rule::unique('factories', 'code')->ignore($id)],
                'postal_code' => ['nullable', 'string', Rule::unique('factories', 'postal_code')->ignore($id)],
                'phone' => ['nullable', 'max:250'],
                'owner_name' => ['nullable', 'string', 'max:250']
            ],
        );


        try {
            $data = $request->except('_token', '_method');

            DB::beginTransaction();
            $factory = Factory::find($id);
            $factory->update($data);
            if ($request->has('image')) {
                if ($factory->getFirstMedia('customer_photo')) {
                    $factory->getFirstMedia('customer_photo')->delete();
                }
                $factory->addMedia($request->image)->toMediaCollection('customer_photo');
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

        return redirect()->route('admin.factories.index')->with('status', $output);
    }

    public function createLenses()
    {
        $brand_lenses = BrandLens::orderBy('name', 'asc')->pluck('name', 'id');
        $foci = Focus::orderBy('name', 'asc')->pluck('name', 'id');
        $design_lenses = Design::orderBy('name', 'asc')->pluck('name', 'id');
        $index_lenses = IndexLens::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::where('is_lens',true)->orderBy('name', 'asc')->pluck('name', 'id');
        $lenses = Product::Lens()->orderBy('name', 'asc')->pluck('name', 'id');
        $special_bases = SpecialBase::orderBy('name', 'asc')->pluck('name', 'id');
        $special_additions = SpecialAddition::orderBy('name', 'asc')->pluck('name', 'id');


        return view('factory::back-end.lenses.create', compact(
            'brand_lenses', 
            'foci', 
            'design_lenses', 
            'index_lenses', 
            'colors', 
            'lenses', 
            'special_bases',
            'special_additions'));
    }

    public function sendLenses(Request $request)
    {
        $validator = validator($request->all(), [
            'lens_id' => 'required|integer|exists:products,id',
            'product' => 'required|array',
            //            'product.Lens.Right.Far.SPHDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
            //            'product.Lens.Right.Far.SPH' => 'required_if:product.Lens.Right.isCheck,==,1',
            //            'product.Lens.Right.Far.CYLDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
            //            'product.Lens.Right.Far.CYL' => 'required_if:product.Lens.Right.isCheck,==,1',
            //            'product.Lens.Right.Far.Axis' => 'required_if:product.Lens.Right.isCheck,==,1',
            //            'product.Lens.Right.Near.SPHDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
            //            'product.Lens.Right.Near.SPH' => 'required_if:product.Lens.Right.isCheck,==,1',
            //            'product.Lens.Right.Near.CYLDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
            //            'product.Lens.Right.Near.CYL' => 'required_if:product.Lens.Right.isCheck,==,1',
            //            'product.Lens.Right.Near.Axis' => 'required_if:product.Lens.Right.isCheck,==,1',


            //            'product.Lens.Left.Far.SPHDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
            //            'product.Lens.Left.Far.SPH' => 'required_if:product.Lens.Left.isCheck,==,1',
            //            'product.Lens.Left.Far.CYLDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
            //            'product.Lens.Left.Far.CYL' => 'required_if:product.Lens.Left.isCheck,==,1',
            //            'product.Lens.Left.Far.Axis' => 'required_if:product.Lens.Left.isCheck,==,1',
            //            'product.Lens.Left.Near.SPHDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
            //            'product.Lens.Left.Near.SPH' => 'required_if:product.Lens.Left.isCheck,==,1',
            //            'product.Lens.Left.Near.CYLDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
            //            'product.Lens.Left.Near.CYL' => 'required_if:product.Lens.Left.isCheck,==,1',
            //            'product.Lens.Left.Near.Axis' => 'required_if:product.Lens.Left.isCheck,==,1',


            'product.VA.TinTing.value' => 'required_if:product.VA.TinTing.isCheck,1',
            'product.VA.Base.value' => 'required_if:product.VA.Base.isCheck,1',
            'product.VA.Ozel.value' => 'required_if:product.VA.Ozel.isCheck,1',
            'product.VA.code.value' => 'required_if:product.VA.code.isCheck,1',
        ]);
        if ($validator->fails())
            return [
                'success' => false,
                'msg' => $validator->errors()->first()
            ];

        $VA_amount = [];
        $total = 0;
        $VA = [];
        if (isset($request->product['VA']['TinTing']['isCheck']) && $request->product['VA']['TinTing']['isCheck'] != null) {

            $VA_amount['TinTing_amount'] = System::getProperty('TinTing_amount') ?: 10;
            $color = Color::whereId($request->product['VA']['TinTing']['value'])->first();
            $total = $total + $VA_amount['TinTing_amount'];
            $VA['TinTing'] = $request->product['VA']['TinTing'];
            $VA['TinTing']['text'] = $color?->name;
        }

        if (isset($request->product['VA']['Base']['isCheck']) && $request->product['VA']['Base']['isCheck'] != null) {

            $Base = SpecialBase::whereId($request->product['VA']['Base']['value'])->first();
            $VA_amount['Base_amount'] = 0;
            if ($Base) {
                $VA_amount['Base_amount'] = $Base->price;
            }
            $total = $total + $VA_amount['Base_amount'];
            $VA['Base'] = $request->product['VA']['Base'];
            $VA['Base']['text'] = $Base?->name;
        }


        if (isset($request->product['VA']['Ozel']['isCheck']) && $request->product['VA']['Ozel']['isCheck'] != null) {
            $VA_amount['Ozel_amount'] = System::getProperty('Ozel_amount') ?: 10;
            $total = $total + $VA_amount['Ozel_amount'];
            $VA['Ozel'] = $request->product['VA']['Ozel'];
            $VA['Ozel']['text'] = $request->product['VA']['Ozel']['value'];
        }


        if (isset($request->product['VA']['Special']['isCheck']) && $request->product['VA']['Special']['isCheck'] != null) {
            $Specials = SpecialAddition::wherein('id', $request->product['VA']['Special']['value'])->get();
            $VA_amount['Special_amount'] = $Specials->sum('price');
            $VA['Special'] = $request->product['VA']['Special'];
            foreach ($Specials as $key => $Special) {
                $VA['Special']['TV'][$key] = [
                    'text' => $Special->name,
                    'price' => $Special->price,
                ];
            }
            $total = $total + $VA_amount['Special_amount'];
        }
        $VA['code'] = $request->product['VA']['code'];
        $VA['code']['text'] = $request->product['VA']['code']['value'];
        $VA_amount['total'] = $total;
        $data = [
            'VA' => $VA,
            'VA_amount' => $VA_amount,
            'Lens' => $request->product['Lens'],
        ];
        $randomNumber = mt_rand(1000, 9999);
        $timestamp = time();


        $cacheKey = "{$randomNumber}_{$timestamp}";
        $expirationTime = 60 * 6;
        Cache::put($cacheKey, $data, $expirationTime);


        // dd($data);

        Mail::to('tariksalahnet@hotmail.com')->send(new LensOrderMail($data));





    }








    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
