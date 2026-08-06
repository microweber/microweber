<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleHtmlEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /** @var string */
    public $htmlBody;

    public function __construct(string $html)
    {
        $this->htmlBody = $html;
    }

    /**
     * @return $this
     */
    public function build(): static
    {
        return $this->html($this->htmlBody);
    }
}
