<?php

namespace MicroweberPackages\Notification\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleHtmlEmail extends Mailable
{
    use Queueable, SerializesModels;


    public $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    public function build()
    {
        return $this->html;
    }
}