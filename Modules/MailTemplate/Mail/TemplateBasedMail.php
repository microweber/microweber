<?php

namespace Modules\MailTemplate\Mail;

use Illuminate\Mail\Mailable;
use Modules\MailTemplate\Models\MailTemplate;

class TemplateBasedMail extends Mailable
{
    public MailTemplate $template;
    public string $parsedMessage;
    public $attachments;

    public function __construct(
        MailTemplate $template,
        string $parsedMessage,
        array $attachments = []
    ) {
        $this->template = $template;
        $this->parsedMessage = $parsedMessage;
        $this->attachments = $attachments;
        $this->from($template->from_email, $template->from_name)
             ->subject($template->subject);

        if ($template->copy_to) {
            $this->cc($template->copy_to);
        }
    }

    public function build()
    {
        $mail = $this->html($this->parsedMessage);

        foreach ($this->attachments as $attachment) {
            if (isset($attachment['path'])) {
                $mail->attach($attachment['path'], $attachment['options'] ?? []);
            }
        }

        return $mail;
    }


}
