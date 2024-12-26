<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Lens\Entities\IndexLens;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Utils\Util;
class IndexLensController extends Controller
{
    protected Util $Util;

    /**
     * Constructor
     *
     * @param Util $Util
     */
    public function __construct(Util $Util)
    {
        $this->Util = $Util;
    }
  /**
   * Display a listing of the resource.
   *
   * @return Application|Factory|View
   */
  public function index(): Factory|View|Application
  {
      $index_lenses = IndexLens::orderBy('created_at','desc')->get();

      return view('setting::back-end.index_lens.index')->with(compact(
          'index_lenses'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Application|Factory|View
   */
  public function create(): Factory|View|Application
  {

      return view('setting::back-end.index_lens.create');
  }

    /**
     * IndexLens a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse | array
     */
// ++++++++++++++++++++++ Task : index_lens() +++++++++++++++
  public function store(Request $request): RedirectResponse|array
  {
      $request->validate([
          'name' => 'max:255|required',
      ]);

      try {
          $index_lens=IndexLens::create([
              'name'=>$request->name
          ]);
          $output = [
              'success' => true,
              'id'=>$index_lens->id,
              'msg' => __('lang.success')
          ];

      }
      catch (\Exception $e) {
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
      $index_lens = IndexLens::find($id);
      return view('setting::back-end.index_lens.edit')->with(compact('index_lens'));
  }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
  public function update(Request $request, int $id): RedirectResponse
  {
      try {
          $index_lens = IndexLens::find($id);
          $index_lens->update([
              'name'=>$request->name
          ]);

          $output = [
              'success' => true,
              'id'=>$index_lens->id,
              'msg' => __('lang.success')
          ];

      }
      catch (\Exception $e) {
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
     * @return array|RedirectResponse
     */
  public function destroy( int $id): array|RedirectResponse
  {
      try {
          $index_lens = IndexLens::find($id);
          $index_lens->delete();
          $output = [
              'success' => true,
              'msg' => __('lang.success')
          ];
      }

      catch (\Exception $e) {
          Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
          $output = [
              'success' => false,
              'msg' => __('messages.something_went_wrong')
          ];
      }
      return $output;

  }
    public function getDropdown(): string
    {
        $index_lenses =IndexLens::orderBy('name', 'asc')->pluck('name', 'id');
        return $this->Util->createDropdownHtml($index_lenses, __('lang.please_select'));
    }
}

?>
