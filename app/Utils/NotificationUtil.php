<?php

namespace App\Utils;

use App\Jobs\InternalStockRequestJob;
use App\Models\Admin;
use Modules\Customer\Entities\Customer;
use Mpdf\MpdfException;
use Modules\Hr\Entities\Notification as ModelsNotification;
use App\Notifications\AddSaleNotification;
use App\Notifications\AdminContactUsNotification;
use App\Notifications\ContactUsNotification;
use App\Notifications\PurchaseOrderToSupplierNotification;
use App\Notifications\QuotationToCustomerNotification;
use App\Notifications\RemoveStockToSupplierNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\AddStock\Entities\AddStockLine;
use Modules\AddStock\Entities\Transaction;
use Modules\Hr\Entities\Employee;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductStore;
use Modules\Setting\Entities\System;
use Notification;

class NotificationUtil extends Util
{

    public function getMpdf()
    {
        return new \Mpdf\Mpdf([
            'tempDir' => public_path('uploads/temp'),
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'autoVietnamese' => true,
            'autoArabic' => true
        ]);
    }


    /**
     * sendPurchaseOrderToSupplier
     *
     * @param [int] $transaction_id
     * @return void
     * @throws MpdfException
     */
    public function sendSellInvoiceToCustomer($transaction_id, $emails)
    {
        $transaction = Transaction::find($transaction_id);

        $payment_types = $this->getPaymentTypeArrayForPos();


        $invoice_lang = System::getProperty('invoice_lang');
        if (empty($invoice_lang)) {
            $invoice_lang = request()->session()->get('language');
        }
        $sale = $transaction;
        $payment_type_array = $payment_types;
        $create_pdf = true;
        $html = view('sale_pos.partials.commercial_invoice')->with(compact(
            'sale',
            'payment_type_array',
            'invoice_lang',
            'create_pdf',
        ))->render();

        $mpdf = $this->getMpdf();
        $mpdf->WriteHTML($html);
        $file = config('constants.mpdf_temp_path') . '/' . time() . '_sell-' . $transaction->invoice_no . '.pdf';
        $mpdf->Output($file, 'F');

        $data['email_body'] =  'New invoice ' . $transaction->invoice_no . ' please check the attachment.';
        $data['attachment'] =  $file;
        $data['attachment_name'] =  'sell-' . $transaction->invoice_no . '.pdf';


        $emails = explode(',', $emails);

        foreach ($emails as $email) {
            Notification::route('mail', $email)
                ->notify(new AddSaleNotification($data));
        }

        if (file_exists($file)) {
            unlink($file);
        }
    }


    /**
     * sendPurchaseOrderToSupplier
     *
     * @param [int] $transaction_id
     * @return void
     */
    public function sendQuotationToCustomer($transaction_id, $emails = null)
    {
        $sale = Transaction::find($transaction_id);
        $customer = Customer::find($sale->customer_id);
        $payment_types = $this->getPaymentTypeArrayForPos();


        $invoice_lang = System::getProperty('invoice_lang');
        if (empty($invoice_lang)) {
            $invoice_lang = request()->session()->get('language');
        }
        if ($sale->is_quotation == 1 && $sale->status == 'draft') {
            $payment_type_array = $payment_types;
            $create_pdf = true;
            $html = view('sale_pos.partials.commercial_invoice')->with(compact(
                'sale',
                'payment_type_array',
                'invoice_lang',
                'create_pdf',
            ))->render();
        }
        $mpdf = $this->getMpdf();

        $mpdf->WriteHTML($html);
        $file = config('constants.mpdf_temp_path') . '/' . time() . '_quotation-' . $sale->invoice_no . '.pdf';
        $mpdf->Output($file, 'F');

        $data['email_body'] =  'Quotation. Please find the file in attachment.';
        $data['attachment'] =  $file;
        $data['attachment_name'] =  'quotation-' . $sale->invoice_no . '.pdf';


        if (!empty($emails)) {
            $emails = explode(',', $emails);
            foreach ($emails as $email) {
                Notification::route('mail', $email)
                    ->notify(new QuotationToCustomerNotification($data));
            }
        } else {
            $email = $customer->email;
            Notification::route('mail', $email)
                ->notify(new QuotationToCustomerNotification($data));
        }

        // if (file_exists($file)) {
        //     unlink($file);
        // }
    }

    public function notifyInternalStockRequest($transaction)
    {
        if ($transaction->status == 'approved' || $transaction->status == 'declined') {
            $admin_ids = Employee::whereJsonContains('store_id', (string)$transaction->receiver_store_id)->pluck('admin_id')->toArray();
            $superadmins = Admin::select('id as admin_id')->pluck('admin_id')->toArray();
            $admin_ids = array_merge($admin_ids, $superadmins);
            foreach ($admin_ids as $admin_id) {
                $user = Admin::find($admin_id);
                if ($transaction->status  == 'approved') {
                    $data['subject'] = 'Internal Stock Request ' . $transaction->invoice_no . ' approved';
                    $data['content'] =  'Your internal stock request no <b>' . $transaction->invoice_no . '</b> has been approved by <b>' . $transaction->approved_by_user->name . '</b> from <b>' . $transaction->sender_store->name . '</b>';
                    $data['content'] .= view('internal_stock_return.partials.transfer_line_table')->with('transaction', $transaction)->render();
                    $from = $transaction->approved_by_user->email;
                }
                if ($transaction->status  == 'declined') {
                    $data['subject'] = 'Internal Stock Request ' . $transaction->invoice_no . ' declined';
                    $data['content'] =  'Your internal stock request no <b>' . $transaction->invoice_no . '</b> has been declined by <b>' . $transaction->declined_by_user->name . '</b> from <b>' . $transaction->sender_store->name . '</b>';
                    $data['content'] .= view('internal_stock_return.partials.transfer_line_table')->with('transaction', $transaction)->render();
                    $from = $transaction->declined_by_user->email;
                }
                $user = Admin::find($admin_id);
                $email = $user->email;
                $data['email'] = $email;
                dispatch(new InternalStockRequestJob($data, $from));

                $this->createNotification([
                    'admin_id' => $admin_id,
                    'transaction_id' => $transaction->id,
                    'type' => 'internal_stock_request',
                    'status' => $transaction->status,
                    'created_by' => Auth::guard('admin')->user()->id,
                ]);
            }
        }
        if ($transaction->status == 'pending' || $transaction->status == 'received') {
            $admin_ids = Employee::whereJsonContains('store_id', (string)$transaction->sender_store_id)->pluck('admin_id')->toArray();
            $superadmins = Admin::select('id as admin_id')->pluck('admin_id')->toArray();
            $admin_ids = array_merge($admin_ids, $superadmins);
            foreach ($admin_ids as $admin_id) {
                $user = Admin::find($admin_id);
                if ($transaction->status  == 'pending') {
                    $data['subject'] = 'Internal Stock Request ' . $transaction->invoice_no . ' requested';
                    $data['content'] =  ucfirst($transaction->created_by_user->name) . ' requested for stock from ' . $transaction->receiver_store->name . '. Internal stock request no <b>' . $transaction->invoice_no . '</b>';
                    $data['content'] .= view('internal_stock_return.partials.transfer_line_table')->with('transaction', $transaction)->render();
                }
                if ($transaction->status  == 'received') {
                    $data['subject'] = 'Internal Stock Request ' . $transaction->invoice_no . ' received';
                    $data['content'] =  ucfirst($transaction->created_by_user->name) . ' received requested stock from ' . $transaction->sender_store->name . '. Internal stock request no <b>' . $transaction->invoice_no . '</b>';
                    $data['content'] .= view('internal_stock_return.partials.transfer_line_table')->with('transaction', $transaction)->render();
                }
                $from = $transaction->created_by_user->email;
                $user = Admin::find($admin_id);
                $email = $user->email;
                $data['email'] = $email;
                dispatch(new InternalStockRequestJob($data, $from));

                $this->createNotification([
                    'admin_id' => $admin_id,
                    'transaction_id' => $transaction->id,
                    'type' => 'internal_stock_request',
                    'status' => $transaction->status,
                    'created_by' => Auth::guard('admin')->user()->id,
                ]);
            }
        }

        return true;
    }
    public function notifyInternalStockReturn($transaction)
    {
        if ($transaction->status == 'approved' || $transaction->status == 'declined') {
            $admin_ids = Employee::whereJsonContains('store_id', (string)$transaction->sender_store_id)->pluck('admin_id')->toArray();
            $superadmins = Admin::select('id as admin_id')->pluck('admin_id')->toArray();
            $admin_ids = array_merge($admin_ids, $superadmins);
            foreach ($admin_ids as $admin_id) {
                $user = Admin::find($admin_id);
                if ($transaction->status  == 'approved') {
                    $data['subject'] = 'Internal Stock return Request ' . $transaction->invoice_no . ' approved';
                    $data['content'] =  'Your internal stock return request no <b>' . $transaction->invoice_no . '</b> has been approved by <b>' . $transaction->approved_by_user->name . '</b> from <b>' . $transaction->sender_store->name . '</b>';
                    $data['content'] .= view('internal_stock_return.partials.transfer_line_table')->with('transaction', $transaction)->render();
                    $from = $transaction->approved_by_user->email;
                }
                if ($transaction->status  == 'declined') {
                    $data['subject'] = 'Internal Stock return Request ' . $transaction->invoice_no . ' declined';
                    $data['content'] =  'Your internal stock return request no <b>' . $transaction->invoice_no . '</b> has been declined by <b>' . $transaction->declined_by_user->name . '</b> from <b>' . $transaction->sender_store->name . '</b>';
                    $data['content'] .= view('internal_stock_return.partials.transfer_line_table')->with('transaction', $transaction)->render();
                    $from = $transaction->declined_by_user->email;
                }
                $user = Admin::find($admin_id);
                $email = $user->email;
                $data['email'] = $email;
                dispatch(new InternalStockRequestJob($data, $from));

                $this->createNotification([
                    'admin_id' => $admin_id,
                    'transaction_id' => $transaction->id,
                    'type' => 'internal_stock_return',
                    'status' => $transaction->status,
                    'created_by' => Auth::guard('admin')->user()->id,
                ]);
            }
        }
        if ($transaction->status == 'pending' || $transaction->status == 'received') {
            $admin_ids = Employee::whereJsonContains('store_id', (string)$transaction->receiver_store_id)->pluck('admin_id')->toArray();
            $superadmins = Admin::select('id as admin_id')->pluck('admin_id')->toArray();
            $admin_ids = array_merge($admin_ids, $superadmins);
            foreach ($admin_ids as $admin_id) {
                $user = Admin::find($admin_id);
                if ($transaction->status  == 'pending') {
                    $data['subject'] = 'Internal Stock Return Request ' . $transaction->invoice_no . ' requested';
                    $data['content'] =  ucfirst($transaction->created_by_user->name) . ' requested for stock return from ' . $transaction->receiver_store->name . '. Internal stock return request no <b>' . $transaction->invoice_no . '</b>';
                    $data['content'] .= view('internal_stock_return.partials.transfer_line_table')->with('transaction', $transaction)->render();
                }
                if ($transaction->status  == 'received') {
                    $data['subject'] = 'Internal Stock Return Request ' . $transaction->invoice_no . ' received';
                    $data['content'] =  ucfirst($transaction->created_by_user->name) . ' received requested stock return from ' . $transaction->sender_store->name . '. Internal stock return request no <b>' . $transaction->invoice_no . '</b>';
                    $data['content'] .= view('internal_stock_return.partials.transfer_line_table')->with('transaction', $transaction)->render();
                }
                $from = $transaction->created_by_user->email;
                $user = Admin::find($admin_id);
                $email = $user->email;
                $data['email'] = $email;
                dispatch(new InternalStockRequestJob($data, $from));

                $this->createNotification([
                    'admin_id' => $admin_id,
                    'transaction_id' => $transaction->id,
                    'type' => 'internal_stock_request',
                    'status' => $transaction->status,
                    'created_by' => Auth::guard('admin')->user()->id,
                ]);
            }
        }

        return true;
    }
    /**
     * add notification to system
     *
     * @param [type] $data
     * @return void
     */
    public function createNotification($data)
    {
        ModelsNotification::create([
            'admin_id' => $data['admin_id'],
            'customer_id' => !empty($data['customer_id']) ? $data['customer_id'] : null,
            'message' => !empty($data['message']) ? $data['message'] : null,
            'transaction_id' => !empty($data['transaction_id']) ? $data['transaction_id'] : null,
            'product_id' => !empty($data['product_id']) ? $data['product_id'] : null,
            'qty_available' => !empty($data['qty_available']) ? $data['qty_available'] : 0,
            'alert_quantity' => !empty($data['alert_quantity']) ? $data['alert_quantity'] : 0,
            'days' => !empty($data['days']) ? $data['days'] : 0,
            'type' => $data['type'],
            'status' => $data['status'],
            'created_by' => $data['created_by'],
        ]);

        return true;
    }

    /**
     * send login details to user by main
     *
     * @param int $employee_id
     * @return void
     */
    public function sendLoginDetails($employee_id)
    {
        $from = System::getProperty('sender_email');
        $app_name = env('APP_NAME');
        // email data
        $employee = Employee::find($employee_id);
        $user = Admin::find($employee->admin_id);
        $employee->pass_string = Crypt::decrypt($employee->pass_string);
        $email_data = array(
            'email' => $user->email,
            'user' => $user,
            'employee' => $employee,
        );

        Mail::send('notification_template.welcom_message', $email_data, function ($message) use ($email_data, $from, $app_name) {
            $message->to($email_data['email'], $email_data['user']->name)
                ->subject('Welcome')
                ->from($from, $app_name);
        });
    }

    /**
     * send contact us details
     *
     * @param array $data
     * @return void
     */
    public function sendContactUs($data)
    {
        $email_data = array(
            'email' => env('COMPANY_EMAIL'),
            'subject' => 'Contact Us',
            'email_body' => $data['email_body'],
            'from' => $data['email'],
        );

        Notification::route('mail', $email_data['email'])
            ->notify(new ContactUsNotification($email_data));
    }
    /**
     * send user contact us details
     *
     * @param array $data
     * @return void
     */
    public function sendAdminContactUs($data)
    {
        $email_data = array(
            'site_title' => $data['site_title'],
            'email' => env('COMPANY_EMAIL'),
            'subject' => 'Admin Contact Us',
            'email_body' => $data['email_body'],
            'from' => $data['email'],
            'files' => $data['files'],
            // 'attachment' => $data['attachment'],
            // 'attachment_name' => $data['attachment_name'],
        );

        Notification::route('mail', $email_data['email'])
            ->notify(new AdminContactUsNotification($email_data));
    }


    public function quantityAlert(){
        $query = Product::leftjoin('product_stores', 'products.id', 'product_stores.product_id')
        ->select(DB::raw('SUM(qty_available) as qty'), 'products.*')
        ->havingRaw('qty < alert_quantity');

        $items = $query->groupBy('products.id')->get();

        $admins = Admin::get();

        foreach($items as $item){
            foreach ($admins as $user) {
                $notification_data = [
                    'admin_id' => $user->id,
                    'product_id' => $item->id,
                    'qty_available' => $item->qty,
                    'alert_quantity' => $item->alert_quantity,
                    'type' => 'quantity_alert',
                    'status' => 'unread',
                    'created_by' => 1,
                ];
                $notification_exist = ModelsNotification::where('admin_id', $user->id)->where('type', 'quantity_alert')->where('product_id',$item->id )->where('status', 'unread')->first();
                if(empty($notification_exist)){
                    $this->createNotification($notification_data);
                }
            }
        }
    }
}
