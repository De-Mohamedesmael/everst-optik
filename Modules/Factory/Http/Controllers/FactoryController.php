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
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Http;

class FactoryController extends Controller
{

    public function index()
    {
        if (request()->ajax()) {

            $customers = Factory::leftjoin('admins', 'factories.created_by', 'admins.id')
                ->leftjoin('admins as edited', 'factories.edited_by', 'admins.id')
                ->select(
                'factories.*',
                'admins.name as created_by_name',
                'edited.name as updated_by_name',
            );

            return DataTables::of($customers)
                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
                ->editColumn('created_by', '{{$created_by_name}}')
                ->editColumn('updated_by', '{{$updated_by_name}}')
                ->editColumn('updated_at', '{{@format_datetime($updated_at)}}')
                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">' . __('lang.action') . '
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">';

                        if (auth()->user()->can('customer_module.customer.create_and_edit')) {
                            $html .=
                                '<li>
                    <a href="' . route('admin.customers.edit', $row->id) . '"
                        ><i class="dripicons-document-edit"></i>
                        ' .__('lang.edit') . '</a>
                        </li>';
                        }

                        if (auth()->user()->can('customer_module.customer.delete')) {
                                $html .=
                                    '<li>
                        <a data-href="' .route('admin.customers.destroy', $row->id). '"
                        data-check_password="' .route('admin.check-password', auth('admin')->user()->id). '"
                        class="btn text-red delete_customer"><i class="fa fa-trash"></i>
                            ' .__('lang.delete') . '</a>
                            </li>';
                            }

                        $html .="</ul>";
                       return $html;
                    }
                )
                ->rawColumns([
                    'action',
                    'created_at',
                    'created_by',
                    'updated_at',
                    'updated_by',
                ])
               ->make(true);
        }
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


    public function getFactoriesLenses(Request $request)
    {
        $prescriptions = Prescription::with(['factory', 'product'])->ofFactories();

        if (request()->ajax()) {
            return DataTables::of($prescriptions)
                ->addColumn('factory_name', fn($row) => $row->factory?->name ?? '')
                ->addColumn('product', fn($row) => $row->product?->name ?? '')
                ->addColumn('date', fn($row) => $row->date)
                ->addColumn('scan_input', function($row) {
                    return '<input type="text" class="form-control scan-input" data-id="'.$row->id.'" value="'.e($row->qr_code).'" />';
                })
                ->addColumn('qr_code_image', function($row) {
                    if ($row->qr_code) {
                        $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($row->qr_code);
                        return '<img src="' . $qr_url . '" width="100" height="100">';
                    }
                    return '<span class="text-muted">—</span>';
                })      
                ->addColumn('actions', function($row) {
                    return '<button class="btn btn-sm btn-primary send-btn" data-id="'.$row->id.'">بيع</button>';
                })
                ->rawColumns(['factory_name', 'product', 'date', 'scan_input', 'qr_code_image', 'actions'])                                          
                ->make(true);
        }        

        return view('factory::back-end.lenses.index', compact('prescriptions'));
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

    public function saveQr(Request $request)
    {

        $request->validate([
            'id' => 'required|exists:prescriptions,id',
            'qr_code' => 'nullable|string|max:255|unique:prescriptions,qr_code,' . $request->id,
        ]);

        $prescription = Prescription::findOrFail($request->id);
        $prescription->qr_code = $request->qr_code;
        $prescription->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حفظ QR بنجاح'
        ]);
    }

    public function sellToUts(Request $request)
    {
        $validated = $request->validate([
            'uno' => 'required|string',
            'lot' => 'nullable|string',
            'sno' => 'nullable|string',
            'adt' => 'nullable|integer',
            'vrn' => 'required|date',
            'vrnT' => 'required|string|in:SATIS,HIBE,IADESATIS',
            'aciklama' => 'nullable|string',
            'aliciKurum.krn' => 'required|string',
            'aliciKurum.tip' => 'required|string|in:KURUM,FIRMA',
        ]);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'utsToken' => 'PUT_YOUR_TOKEN_HERE'
            ])->post('https://utstest.saglik.gov.tr/UTS/uh/rest/bildirim/verme/ekle', $validated); // test
            // https://utsuygulama.saglik.gov.tr/UTS/uh/rest/bildirim/verme/ekle // real

            return response()->json([
                'status' => $response->successful(),
                'response' => $response->json(),
            ], $response->status());

        } catch (\Throwable $e) {
            Log::error('UTS Error: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    // not used
    // public function sendLenses(Request $request)
    // {

    //     $validator = validator($request->all(), [
    //         'factory_id' => 'required|integer|exists:factories,id',
    //         'lens_id' => 'required|integer|exists:products,id',
    //         // 'product' => 'required|array',
    //         // 'product.Lens.Right.Far.SPHDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
    //         // 'product.Lens.Right.Far.SPH' => 'required_if:product.Lens.Right.isCheck,==,1',
    //         // 'product.Lens.Right.Far.CYLDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
    //         // 'product.Lens.Right.Far.CYL' => 'required_if:product.Lens.Right.isCheck,==,1',
    //         // 'product.Lens.Right.Far.Axis' => 'required_if:product.Lens.Right.isCheck,==,1',
    //         // 'product.Lens.Right.Near.SPHDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
    //         // 'product.Lens.Right.Near.SPH' => 'required_if:product.Lens.Right.isCheck,==,1',
    //         // 'product.Lens.Right.Near.CYLDeg' => 'required_if:product.Lens.Right.isCheck,==,1',
    //         // 'product.Lens.Right.Near.CYL' => 'required_if:product.Lens.Right.isCheck,==,1',
    //         // 'product.Lens.Right.Near.Axis' => 'required_if:product.Lens.Right.isCheck,==,1',
    //         // 'product.Lens.Left.Far.SPHDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
    //         // 'product.Lens.Left.Far.SPH' => 'required_if:product.Lens.Left.isCheck,==,1',
    //         // 'product.Lens.Left.Far.CYLDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
    //         // 'product.Lens.Left.Far.CYL' => 'required_if:product.Lens.Left.isCheck,==,1',
    //         // 'product.Lens.Left.Far.Axis' => 'required_if:product.Lens.Left.isCheck,==,1',
    //         // 'product.Lens.Left.Near.SPHDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
    //         // 'product.Lens.Left.Near.SPH' => 'required_if:product.Lens.Left.isCheck,==,1',
    //         // 'product.Lens.Left.Near.CYLDeg' => 'required_if:product.Lens.Left.isCheck,==,1',
    //         // 'product.Lens.Left.Near.CYL' => 'required_if:product.Lens.Left.isCheck,==,1',
    //         // 'product.Lens.Left.Near.Axis' => 'required_if:product.Lens.Left.isCheck,==,1',
    //         // 'product.VA.TinTing.value' => 'required_if:product.VA.TinTing.isCheck,1',
    //         // 'product.VA.Base.value' => 'required_if:product.VA.Base.isCheck,1',
    //         // 'product.VA.Ozel.value' => 'required_if:product.VA.Ozel.isCheck,1',
    //         // 'product.VA.code.value' => 'required_if:product.VA.code.isCheck,1',
    //     ]);
    //     if ($validator->fails())
    //         return [
    //             'success' => false,
    //             'msg' => $validator->errors()->first()
    //         ];

    //     $VA_amount = [];
    //     $total = 0;
    //     $VA = [];

    //     if (isset($request->product['VA']['TinTing']['isCheck']) && $request->product['VA']['TinTing']['isCheck'] != null) {

    //         $VA_amount['TinTing_amount'] = System::getProperty('TinTing_amount') ?: 10;
    //         $color = Color::whereId($request->product['VA']['TinTing']['value'])->first();
    //         $total = $total + $VA_amount['TinTing_amount'];
    //         $VA['TinTing'] = $request->product['VA']['TinTing'];
    //         $VA['TinTing']['text'] = $color?->name;
    //     }

    //     if (isset($request->product['VA']['Base']['isCheck']) && $request->product['VA']['Base']['isCheck'] != null) {

    //         $Base = SpecialBase::whereId($request->product['VA']['Base']['value'])->first();
    //         $VA_amount['Base_amount'] = 0;
    //         if ($Base) {
    //             $VA_amount['Base_amount'] = $Base->price;
    //         }
    //         $total = $total + $VA_amount['Base_amount'];
    //         $VA['Base'] = $request->product['VA']['Base'];
    //         $VA['Base']['text'] = $Base?->name;
    //     }

    //     if (isset($request->product['VA']['Ozel']['isCheck']) && $request->product['VA']['Ozel']['isCheck'] != null) {
    //         $VA_amount['Ozel_amount'] = System::getProperty('Ozel_amount') ?: 10;
    //         $total = $total + $VA_amount['Ozel_amount'];
    //         $VA['Ozel'] = $request->product['VA']['Ozel'];
    //         $VA['Ozel']['text'] = $request->product['VA']['Ozel']['value'];
    //     }

    //     if (isset($request->product['VA']['Special']['isCheck']) && $request->product['VA']['Special']['isCheck'] != null) {
    //         $Specials = SpecialAddition::wherein('id', $request->product['VA']['Special']['value'])->get();
    //         $VA_amount['Special_amount'] = $Specials->sum('price');
    //         $VA['Special'] = $request->product['VA']['Special'];
    //         foreach ($Specials as $key => $Special) {
    //             $VA['Special']['TV'][$key] = [
    //                 'text' => $Special->name,
    //                 'price' => $Special->price,
    //             ];
    //         }
    //         $total = $total + $VA_amount['Special_amount'];
    //     }
    //     $VA['code'] = $request->product['VA']['code'];
    //     $VA['code']['text'] = $request->product['VA']['code']['value'];
    //     $VA_amount['total'] = $total;
    //     $data = [
    //         'VA' => $VA,
    //         'VA_amount' => $VA_amount,
    //         'Lens' => $request->product['Lens'],
    //     ];
    //     $randomNumber = mt_rand(1000, 9999);
    //     $timestamp = time();


    //     // $cacheKey = "{$randomNumber}_{$timestamp}";
    //     // $expirationTime = 60 * 6;
    //     // Cache::put($cacheKey, $data, $expirationTime);

        
    //     // if($line['is_lens']){
    //         // $is_lens=$line['is_lens'];
    //         // $KeyLens=$line['KeyLens'];
    //         $prescription_data=[
    //             // 'customer_id' => $transaction->customer_id,
    //             'product_id' => $request->lens_id,
    //             // 'sell_line_id' => $transaction_sell_line->id,
    //             'factory_id' => $request->factory_id,
    //             'date' => date('Y-m-d'),
    //             'data' => json_encode($data),
    //         ];
    //         $prescription = Prescription::create($prescription_data);

    //         //     api index from pdf, clcik any product send to supplier or send to client via uts
    //         //     api list of finshed lens  send to supplier or send to client via uts
    //         //     only ouyside indutries lenses will be traced
            
    //     // }

    //     return redirect()->route('admin.factories.lenses.index');

    //     Mail::to('tariksalahnet@hotmail.com')->send(new LensOrderMail($data));
        
        



    // }









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
