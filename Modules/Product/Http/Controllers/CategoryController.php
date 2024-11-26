<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Product\Entities\Category;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();

        return view('product::back-end.categories.index')->with(compact(
            'categories'
        ));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getSubCategories()
    {
        $categories = Category::whereNotNull('parent_id')->get();

        return view('product::back-end.categories.sub_categories')->with(compact(
            'categories'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $quick_add = $request->quick_add ?? null;
        $categories = Category::orderBy('name', 'asc')->pluck('name', 'id');
        return view('product::back-end.categories.create')->with(compact(
            'quick_add',
            'categories'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            ['name' => ['required', 'max:255']]
        );

        $category_exist = Category::where('name', $request->name)->exists();


        if ($category_exist) {
            if ($request->ajax()) {
                return response()->json(array(
                    'success' => false,
                    'message' => translate('There are incorect values in the form!'),
                    'msg' => translate('Category name already taken')
                ));
            }
        }
        try {
            $data = $request->except('_token', 'quick_add');
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];

            DB::beginTransaction();
            $category = Category::create($data);

            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach ($request->cropImages as $imageData) {
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $category->addMedia($filePath)->toMediaCollection('category');

                }
            }
            $category_id = $category->id;
            DB::commit();
            $output = [
                'success' => true,
                'category_id' => $category_id,
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view('product::back-end.categories.edit')->with(compact(
            'category'
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
            $data['translations'] = !empty($data['translations']) ? $data['translations'] : [];
            DB::beginTransaction();
            $category = Category::find($id);

            $category->update($data);
            if ($request->has("cropImages") && count($request->cropImages) > 0) {
                foreach (getCroppedImages($request->cropImages) as $imageData) {
                    $category->clearMediaCollection('category');
                    $extention = explode(";",explode("/",$imageData)[1])[0];
                    $image = rand(1,1500)."_image.".$extention;
                    $filePath = public_path('uploads/' . $image);
                    $fp = file_put_contents($filePath,base64_decode(explode(",",$imageData)[1]));
                    $category->addMedia($filePath)->toMediaCollection('category');
                }
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
            Category::find($id)->delete();
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

        $categories = Category::orderBy('name', 'asc')->pluck('name', 'id');
        return $this->commonUtil->createDropdownHtml($categories);
    }




}
