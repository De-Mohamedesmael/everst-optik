<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Lens\Entities\Design;
use Modules\Lens\Entities\Focus;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Utils\Util;
class FocusController extends Controller
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
      $foci = Focus::orderBy('created_at','desc')->get();

      return view('setting::back-end.focus.index')->with(compact(
          'foci'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Application|Factory|View
   */
  public function create(): Factory|View|Application
  {
      $designs=Design::orderBy('name', 'asc')->pluck('name', 'id');

      return view('setting::back-end.focus.create')->with(compact('designs'));
  }

    /**
     * Focus a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse | array
     */
// ++++++++++++++++++++++ Task : focus() +++++++++++++++
  public function store(Request $request): RedirectResponse|array
  {
      $request->validate([
          'name' => 'max:255|required',
      ]);

      try {
          $focus=Focus::create([
              'name'=>$request->name
          ]);
          $focus->designs()->detach();
          if( $request->has("design_id")) {

              $focus->designs()->sync($request->design_id);
          }
          $output = [
              'success' => true,
              'id'=>$focus->id,
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
      $focus = Focus::find($id);
      $designs=Design::orderBy('name', 'asc')->pluck('name', 'id');

      return view('setting::back-end.focus.edit')->with(compact('focus','designs'));
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
          $focus = Focus::find($id);
          $focus->update([
              'name'=>$request->name
          ]);
          if( $request->has("design_id")) {
              $focus->designs()->detach();
              $focus->designs()->sync($request->design_id);
          }
          $output = [
              'success' => true,
              'id'=>$focus->id,
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
          $focus = Focus::find($id);
          $focus->delete();
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
        $foci =Focus::orderBy('name', 'asc')->pluck('name', 'id');
        return $this->Util->createDropdownHtml($foci, __('lang.please_select'));
    }
}

?>
