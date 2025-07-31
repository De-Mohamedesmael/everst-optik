<?php

namespace Modules\Factory\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
// use Illuminate\Routing\Controller;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Modules\Customer\Entities\Customer;
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
use Modules\Customer\Entities\Prescription;
use Modules\Setting\Entities\System;
use Yajra\DataTables\Facades\DataTables;



class RequestLensController extends Controller
{


    public function index()
    {
        if (request()->ajax()) {

            $customers = Prescription::leftjoin('products', 'prescriptions.product_id', 'products.id')
                ->leftjoin('factories', 'prescriptions.factory_id', 'factories.id')
                ->select(
                    'prescriptions.*',
                    'products.name as lens_name',
                    'factories.name as factory_name'
                );

            return DataTables::of($customers)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('amount_product', '{{@number_format($amount_product)}}')
                ->editColumn('total_extra', '{{@number_format($total_extra)}}')
                ->editColumn('amount_total', '{{@number_format($amount_total)}}')
                ->addColumn('lens_name', function ($row) {
                    dd($row->lens_name);
                    return $row->lens_name ? $row->lens_name : '';
                })->addColumn(
                    'action',
                    function ($row) {
                        $html = '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">' . __('lang.action') . '
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';





                        $html .="</ul>";
                        return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'lens_name',
                    'created_at',
                    'amount_product',
                    'amount_total',
                    'total_extra',
                ])
                ->make(true);
        }
        return view('factory::back-end.lenses.index');
    }


    public function create()
    {
        $brand_lenses = BrandLens::orderBy('name', 'asc')->pluck('name', 'id');
        $foci = Focus::orderBy('name', 'asc')->pluck('name', 'id');
        $design_lenses = Design::orderBy('name', 'asc')->pluck('name', 'id');
        $index_lenses = IndexLens::orderBy('name', 'asc')->pluck('name', 'id');
        $colors = Color::where('is_lens',true)->orderBy('name', 'asc')->pluck('name', 'id');
        $lenses = Product::Lens()->orderBy('name', 'asc')->pluck('name', 'id');
        $special_bases = SpecialBase::orderBy('name', 'asc')->pluck('name', 'id');
        $special_additions = SpecialAddition::orderBy('name', 'asc')->pluck('name', 'id');

        $factories = Factory::where('active',1)->orderBy('name', 'asc')->pluck('name', 'id');

        return view('factory::back-end.lenses.create', compact(
            'brand_lenses',
            'foci',
            'design_lenses',
            'index_lenses',
            'colors',
            'lenses',
            'special_bases',
            'special_additions',
            'factories'));
    }

    public function store(Request $request)
    {

        $validator = validator($request->all(), [
            'factory_id' => 'required|integer|exists:factories,id',
            'lens_id' => 'required|integer|exists:products,id',
            // 'product' => 'required|array',
            // 'product.Lens.Right.Far.SPHDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
            // 'product.Lens.Right.Far.SPH' => 'required_if:product.Lens.Right.isCheck,==,1',
            // 'product.Lens.Right.Far.CYLDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
            // 'product.Lens.Right.Far.CYL' => 'required_if:product.Lens.Right.isCheck,==,1',
            // 'product.Lens.Right.Far.Axis' => 'required_if:product.Lens.Right.isCheck,==,1',
            // 'product.Lens.Right.Near.SPHDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
            // 'product.Lens.Right.Near.SPH' => 'required_if:product.Lens.Right.isCheck,==,1',
            // 'product.Lens.Right.Near.CYLDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
            // 'product.Lens.Right.Near.CYL' => 'required_if:product.Lens.Right.isCheck,==,1',
            // 'product.Lens.Right.Near.Axis' => 'required_if:product.Lens.Right.isCheck,==,1',
            // 'product.Lens.Left.Far.SPHDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
            // 'product.Lens.Left.Far.SPH' => 'required_if:product.Lens.Left.isCheck,==,1',
            // 'product.Lens.Left.Far.CYLDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
            // 'product.Lens.Left.Far.CYL' => 'required_if:product.Lens.Left.isCheck,==,1',
            // 'product.Lens.Left.Far.Axis' => 'required_if:product.Lens.Left.isCheck,==,1',
            // 'product.Lens.Left.Near.SPHDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
            // 'product.Lens.Left.Near.SPH' => 'required_if:product.Lens.Left.isCheck,==,1',
            // 'product.Lens.Left.Near.CYLDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
            // 'product.Lens.Left.Near.CYL' => 'required_if:product.Lens.Left.isCheck,==,1',
            // 'product.Lens.Left.Near.Axis' => 'required_if:product.Lens.Left.isCheck,==,1',
            // 'product.VA.TinTing.value' => 'required_if:product.VA.TinTing.isCheck,1',
            // 'product.VA.Base.value' => 'required_if:product.VA.Base.isCheck,1',
            // 'product.VA.Ozel.value' => 'required_if:product.VA.Ozel.isCheck,1',
            // 'product.VA.code.value' => 'required_if:product.VA.code.isCheck,1',
        ]);
        if ($validator->fails())
            return [
                'success' => false,
                'msg' => $validator->errors()->first()
            ];

        $VA_amount = [];
        $total = 0;
        $VA = [];
//        dd($request->all());

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


        // $cacheKey = "{$randomNumber}_{$timestamp}";
        // $expirationTime = 60 * 6;
        // Cache::put($cacheKey, $data, $expirationTime);


        // if($line['is_lens']){
            // $is_lens=$line['is_lens'];
            // $KeyLens=$line['KeyLens'];
            $prescription_data=[
                // 'customer_id' => $transaction->customer_id,
                'product_id' => $request->lens_id,
                 'amount_product' =>$request->total_lens_valu,
                 'total_extra' => $total,
                 'amount_total' => $total+$request->total_lens_valu,
                'factory_id' => $request->factory_id,
                'date' => date('Y-m-d'),
                'data' => json_encode($data),
            ];
            $prescription = Prescription::create($prescription_data);

            //     api index from PDF, clcik any product send to supplier or send to a client via uts
            //     api list of finshed lens  send to supplier or send to a client via uts
            //     only ouyside industry lenses will be traced

        // }
        Mail::to('tariksalahnet@hotmail.com')->send(new LensOrderMail($prescription));

        return redirect()->route('admin.factories.lenses.index');

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
