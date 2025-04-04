<?php

namespace Modules\Newsletter\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Newsletter\Models\NewsletterCampaign;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;

    public function __construct(NewsletterCampaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function build()
    {
        return $this->view('newsletter::emails.newsletter')
                    ->subject($this->campaign->subject);
    }
}