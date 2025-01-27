<?php

namespace App\Http\Controllers;

use App\Models\ContactUsDetail;
use App\Utils\NotificationUtil;
use App\Utils\Util;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
// use App\Models\WagesAndCompensation;

class GeneralController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $commonUtil;
    protected $notificationUtil;

    /**
     * Constructor
     *
     * @param Util $commonUtil
     * @param NotificationUtil $notificationUtil
     * @return void
     */
    public function __construct(Util $commonUtil, NotificationUtil $notificationUtil)
    {
        $this->commonUtil = $commonUtil;
        $this->notificationUtil = $notificationUtil;
    }


    public function viewUploadedFiles($model_name, $model_id)
    {
        $collection_name = request()->collection_name;

        $item = $model_name::find($model_id);

        $uploaded_files = [];
        if (!empty($item)) {
            if (!empty($collection_name)) {
                $uploaded_files = $item->getMedia($collection_name);
            }
        }



        return view('back-end.layouts.partials.view_uploaded_files')->with(compact(
            'uploaded_files'
        ));
    }
    public function switchLanguage($lang): RedirectResponse
    {
        session()->put('language', $lang);

        return redirect()->back();
    }

    /**
     * create the contact us page
     *
     * @return Application|Factory|View
     */
    public function getContactUs(): Application|Factory|View
    {
        return view('contact_us.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function sendContactUs(Request $request): RedirectResponse
    {
        try {
            $this->validate($request, [
                'country_code' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email'
            ]);

            $contact_us = new ContactUsDetail();
            $contact_us->country_code = $request->country_code;
            $contact_us->phone_number = $request->phone_number;
            $contact_us->email = $request->email;
            $contact_us->message = $request->message;
            $contact_us->save();

            $data['country_code'] = $request->country_code;
            $data['phone_number'] = $request->phone_number;
            $data['message'] = $request->message;
            $data['email'] = $request->email;
            $data['email_body'] = view('contact_us.form_data')->with(compact(
                'data'
            ))->render();
            $this->notificationUtil->sendContactUs($data);


            $output = [
                'success' => true,
                'msg' => __('lang.your_message_sent_sccuessfully')
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
}
