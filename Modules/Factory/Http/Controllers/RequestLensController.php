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
use Modules\Setting\Entities\Store;
use Modules\Setting\Entities\System;
use Yajra\DataTables\Facades\DataTables;



class RequestLensController extends Controller
{


    public function index()
    {
        if (request()->ajax()) {
            $prescriptions = Prescription::leftjoin('products', 'prescriptions.product_id', 'products.id')
                ->leftjoin('factories', 'prescriptions.factory_id', 'factories.id')
                ->leftjoin('transactions', 'prescriptions.transaction_id', 'transactions.id')
                ->leftjoin('stores', 'prescriptions.store_id', 'stores.id')
                ->select(
                    'prescriptions.*',
                    'transactions.invoice_no as invoice_no',
                    'stores.name as store_name',
                    'products.name as lens_name',
                    'factories.name as factory_name'
                )->ofFactories();
            return DataTables::of($prescriptions)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('amount_product', function ($row) {
                   $amount_product= $row->amount_product;
                    $data_prescription =json_decode($row->data);
                    $q=1;
                    if((isset($data_prescription->Lens->Right->isCheck)&&$data_prescription->Lens->Right->isCheck) && (isset($data_prescription->Lens->Left->isCheck)&&$data_prescription->Lens->Left->isCheck)){
                      $q=2;
                    }
                    return  number_format($amount_product*$q);
                })
                ->editColumn('total_extra', function ($row) {
                    $total_extra= $row->total_extra;
                    $data_prescription =json_decode($row->data);
                    $q=1;
                    if((isset($data_prescription->Lens->Right->isCheck)&&$data_prescription->Lens->Right->isCheck) && (isset($data_prescription->Lens->Left->isCheck)&&$data_prescription->Lens->Left->isCheck)){
                        $q=2;
                    }
                    return  number_format($total_extra*$q);
                }) ->editColumn('amount_total', function ($row) {
                    $amount_total= $row->amount_total;
                    $data_prescription =json_decode($row->data);
                    $q=1;
                    if((isset($data_prescription->Lens->Right->isCheck)&&$data_prescription->Lens->Right->isCheck) && (isset($data_prescription->Lens->Left->isCheck)&&$data_prescription->Lens->Left->isCheck)){
                        $q=2;
                    }
                    return  number_format($amount_total*$q);
                })
                ->editColumn('sku', function ($row) {
                    return '<span class="copy-sku" data-id="' . $row->id . '" data-sku="' . $row->sku . '" style="cursor:pointer;" title="'.translate('Click_To_Copy').'">' . $row->sku . '</span>';
                })->addColumn('scan_input', function ($row) {
                    return '<input type="text" class="form-control scan-input" data-id="' . $row->id . '" value="' . e($row->qr_code) . '" />';
                })
                ->addColumn('qr_code_image', function ($row) {
                    if ($row->qr_code) {
                        $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($row->qr_code);
                        return '<img src="' . $qr_url . '" width="100" height="100">';
                    }
                    return '<span class="text-muted">—</span>';
                })
                ->rawColumns(['created_at','sku', 'amount_product', 'total_extra', 'amount_total', 'scan_input', 'qr_code_image', 'actions'])
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
        $stores = Store::getDropdown();

        $factories = Factory::where('active',1)->orderBy('name', 'asc')->pluck('name', 'id');

        return view('factory::back-end.lenses.create', compact(
            'brand_lenses',
            'foci',
            'design_lenses',
            'index_lenses',
            'stores',
            'colors',
            'lenses',
            'special_bases',
            'special_additions',
            'factories'));
    }

    public function store(Request $request)
    {

       $request->validate([
            'factory_id' => 'required|integer|exists:factories,id',
            'lens_id' => 'required|integer|exists:products,id',
            'store_id' => 'required|integer|exists:stores,id'
        ]);


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
            'Lens' => $request->product['Lens'] ?? null,
        ];

            $prescription_data=[
                'product_id' => $request->lens_id,
                'store_id' => $request->store_id,
                 'amount_product' =>$request->total_lens_valu,
                 'total_extra' => $total,
                 'amount_total' => $total+$request->total_lens_valu,
                'factory_id' => $request->factory_id,
                'note' => $request->note,
                'date' => date('Y-m-d'),
                'data' => json_encode($data),
            ];
            $prescription = Prescription::create($prescription_data);
            $sku = 'RX' . date('Ymd') . str_pad($prescription->id, 6, '0', STR_PAD_LEFT);
            $prescription->sku=$sku;
            $prescription->save();

        $factory = Factory::where('id',$request->factory_id)->first();

        if($factory->email)
            Mail::to($factory->email)->send(new LensOrderMail($prescription));


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
