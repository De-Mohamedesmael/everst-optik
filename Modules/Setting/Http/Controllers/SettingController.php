<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\Util;
use Modules\Setting\Entities\Currency;
use Modules\Setting\Entities\System;
use Modules\Setting\Entities\TermsAndCondition;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
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
    public function getGeneralSetting()
    {
        $settings = System::pluck('value', 'key');
        $config_languages = config('constants.langs');
        $languages = [];
        foreach ($config_languages as $key => $value) {
            $languages[$key] = $value['full_name'];
        }
        $currencies  = $this->commonUtil->allCurrencies();

        $timezone_list = $this->commonUtil->allTimeZones();
        $terms_and_conditions = TermsAndCondition::where('type', 'invoice')->orderBy('name', 'asc')->pluck('name', 'id');
        $fonts=System::getFonts();
        return view('setting::back-end.settings.general_setting')->with(compact(
            'settings',
            'currencies',
            'timezone_list',
            'terms_and_conditions',
            'languages','fonts'
        ));
    }
    public function updateGeneralSetting(Request $request)
    {
        try {
            System::updateOrCreate(
                ['key' => 'site_title'],
                ['value' => $request->site_title, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );

            System::updateOrCreate(
                ['key' => 'time_format'],
                ['value' => $request->time_format, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'timezone'],
                ['value' => $request->timezone, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'invoice_terms_and_conditions'],
                ['value' => $request->invoice_terms_and_conditions, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'language'],
                ['value' => $request->language, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'show_the_window_printing_prompt'],
                ['value' => $request->show_the_window_printing_prompt ?? 0, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );

            System::updateOrCreate(
                ['key' => 'numbers_length_after_dot'],
                ['value' => $request->numbers_length_after_dot ?? 0, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'currency'],
                ['value' => $request->currency, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'font_size_at_invoice'],
                ['value' => $request->font_size_at_invoice, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            if (!empty($request->currency)) {
                $currency = Currency::find($request->currency);
                $currency_data = [
                    'country' => $currency->country,
                    'code' => $currency->code,
                    'symbol' => $currency->symbol,
                    'decimal_separator' => '.',
                    'thousand_separator' => ',',
                    'currency_precision' => !empty(System::getProperty('numbers_length_after_dot')) ? System::getProperty('numbers_length_after_dot') : 5,
                    'currency_symbol_placement' => 'before',
                ];
                $request->session()->put('currency', $currency_data);
            }
            System::updateOrCreate(
                ['key' => 'invoice_lang'],
                ['value' => $request->invoice_lang, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'default_purchase_price_percentage'],
                ['value' => $request->default_purchase_price_percentage ?? 0, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );



            System::updateOrCreate(
                ['key' => 'Ozel_amount'],
                ['value' => $request->Ozel_amount ?? 0, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            System::updateOrCreate(
                ['key' => 'TinTing_amount'],
                ['value' => $request->TinTing_amount ?? 0, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );

            System::updateOrCreate(
                ['key' => 'watsapp_numbers'],
                ['value' => $request->watsapp_numbers, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
            );
            if (!empty($request->language)) {
                session()->put('language', $request->language);
            }
            $data['login_screen'] = null;
            if ($request->hasFile('login_screen')) {
                $file = $request->file('login_screen');
                $data['login_screen'] = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/', $data['login_screen']);
            }


            $data['letter_header'] = null;
            if ($request->has('letter_header') && !is_null('letter_header')) {
                foreach (getCroppedImages([$request->letter_header])as $imageData) {
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $data['letter_header'] = $image;
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                }
            }
            $data['letter_footer'] = null;
            if ($request->has('letter_footer') && !is_null('letter_footer')) {
                foreach (getCroppedImages([$request->letter_footer])as $imageData) {
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $data['letter_footer'] = $image;
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));
                }
            }
            $data['logo'] = null;
            if ($request->has('logo') && !is_null('logo')) {
                foreach (getCroppedImages([$request->logo])as $imageData) {
                    $extention = explode(";", explode("/", $imageData)[1])[0];
                    $image = rand(1, 1500) . "_image." . $extention;
                    $filePath = public_path('uploads/' . $image);
                    $data['logo'] = $image;
                    $fp = file_put_contents($filePath, base64_decode(explode(",", $imageData)[1]));

                }
            }

            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    System::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value, 'date_and_time' => Carbon::now(), 'created_by' => Auth::user()->id]
                    );
                    if ($key == 'logo') {
                        $logo = System::getProperty('logo');
                        $request->session()->put('logo', $logo);
                    }
                }
            }
            Artisan::call("optimize:clear");

            $output = [
                'success' => true,
                'msg' => translate('success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => translate('something_went_wrong')
            ];
        }

        return redirect()->back()->with('status', $output);
    }
    public function removeImage($type)
    {
        try {
            $image= System::getProperty($type);
            $filePath = public_path('uploads/' . $image);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
            System::where('key', $type)->update(['value' => null]);
            $output = [
                'success' => true,
                'msg' => translate('success')
            ];
        } catch (\Exception $e) {
            Log::emergency('File: ' . $e->getFile() . 'Line: ' . $e->getLine() . 'Message: ' . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => translate('something_went_wrong')
            ];
        }

        return $output;
    }
}
