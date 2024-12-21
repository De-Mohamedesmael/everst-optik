<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Modules\Setting\Entities\Store;
use  Modules\Setting\Entities\Tax;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaxController extends Controller
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
    public function index(): Application|Factory|View
    {
        $query = Tax::where('id', '>', 0);
        $type = request()->type ?? 'product_tax';

        if (!empty(request()->type)) {
            $query->where('type', request()->type);
        }
        $taxes = $query->get();

        return view('setting::back-end.tax.index')->with(compact('taxes', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): Factory|View|Application
    {
        $quick_add = request()->quick_add ?? null;
        $type = request()->type ?? 'product_tax';

        $taxes = Tax::orderBy('name', 'asc')->pluck('name', 'id');
        $stores = Store::orderBy('name', 'asc')->pluck('name', 'id');

        return view('setting::back-end.tax.create')->with(compact(
            'quick_add',
            'taxes',
            'stores',
            'type'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array|RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): array|RedirectResponse
    {

        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['rate' => ['required', 'max:255']],
            ['type' => ['required', 'max:255']]
        );

        try {
            $data = $request->except('_token', 'quick_add');

            DB::beginTransaction();
            if ($data['type'] == 'general_tax') {
                $data['status'] = !empty($data['status']) ? 1 : 0;
                $data['store_ids'] = !empty($data['store_ids']) ? $data['store_ids'] : [];
            } else {
                $data['status'] = 1;
                $data['store_ids'] = [];
            }
            $tax = Tax::create($data);

            $tax_id = $tax->id;

            DB::commit();
            $output = [
                'success' => true,
                'tax_id' => $tax_id,
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id): Factory|View|Application
    {
        $tax = Tax::find($id);
        $stores = Store::orderBy('name', 'asc')->pluck('name', 'id');

        return view('setting::back-end.tax.edit')->with(compact(
            'tax',
            'stores'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']],
            ['rate' => ['required', 'max:255']],
            ['type' => ['required', 'max:255']],
        );

        try {
            $data = $request->except('_token', '_method');

            DB::beginTransaction();
            if ($data['type'] == 'general_tax') {
                $data['status'] = !empty($data['status']) ? 1 : 0;
                $data['store_ids'] = !empty($data['store_ids']) ? $data['store_ids'] : [];
            } else {
                $data['status'] = 1;
                $data['store_ids'] = [];
            }
            $tax = Tax::where('id', $id)->update($data);

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
     * @param int $id
     * @return Response|array
     */
    public function destroy(int $id): Response|array
    {
        try {
            Tax::find($id)->delete();
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

    /**
     * get dropdown html by store
     *
     * @return string
     */
    public function getDropdownHtmlByStore(): string
    {
        $store_id = request()->store_id;

        $taxes = Tax::getDropdown($store_id);
        $tax_dp = '<option value="">No Tax</option>';
        foreach ($taxes as $tax) {
            $tax_dp .= '<option data-rate="' . $tax['rate'] . '" value="' . $tax['id'] . '">' . $tax['name'] . '</option>';
        }

        return $tax_dp;
    }
    /**
     * get dropdown html
     *
     * @return string
     */
    public function getDropdown(): string
    {
        $type = request()->type ?? 'product_tax';
        $query = Tax::orderBy('name', 'asc');
        if (!empty($type)) {
            $query->where('type', $type);
        }
        $tax = $query->pluck('name', 'id');
        return $this->commonUtil->createDropdownHtml($tax, 'Please Select');
    }

    /**
     * get Details
     *
     * @param int $id
     * @return object
     */
    public function getDetails( int $id): object
    {
        return Tax::find($id);
    }
}
