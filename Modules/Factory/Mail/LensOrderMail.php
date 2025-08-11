<?php
namespace Modules\Factory\Mail;

use  Modules\Customer\Entities\Prescription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LensOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct(Prescription $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Lens Order Details')
                    ->view('factory::back-end.emails.lens_order')
                    ->with(['prescription' => $this->data]);
    }
}
