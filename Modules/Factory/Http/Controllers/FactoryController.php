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


//    public function getFactoriesLenses(Request $request)
//    {
//
//
//        if (request()->ajax()) {
//            $prescriptions = Prescription::leftjoin('products', 'prescriptions.product_id', 'products.id')
//                ->leftjoin('factories', 'prescriptions.factory_id', 'factories.id')
//                ->select(
//                    'prescriptions.*',
//                    'products.name as lens_name',
//                    'factories.name as factory_name'
//                )->ofFactories();
//            return DataTables::of($prescriptions)
//                ->editColumn('created_at', '{{@format_datetime($created_at)}}')
//                ->editColumn('amount_product', '{{@number_format($amount_product)}}')
//                ->editColumn('total_extra', '{{@number_format($total_extra)}}')
//                ->editColumn('amount_total', '{{@number_format($amount_total)}}')
//                ->addColumn('scan_input', function($row) {
//                    return '<input type="text" class="form-control scan-input" data-id="'.$row->id.'" value="'.e($row->qr_code).'" />';
//                })
//                ->addColumn('qr_code_image', function($row) {
//                    if ($row->qr_code) {
//                        $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($row->qr_code);
//                        return '<img src="' . $qr_url . '" width="100" height="100">';
//                    }
//                    return '<span class="text-muted">—</span>';
//                })
//
//                ->rawColumns(['created_at', 'amount_product','total_extra','amount_total','scan_input', 'qr_code_image', 'actions'])
//                ->make(true);
//        }
//
//        return view('factory::back-end.lenses.index');
//    }



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


}
