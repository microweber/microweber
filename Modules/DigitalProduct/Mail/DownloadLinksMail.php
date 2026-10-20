<?php

namespace Modules\DigitalProduct\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Emails a buyer the secure, tokenized download link(s) for a purchased order.
 *
 * Only ever carries the /download/{token} links (never the file itself nor a
 * direct file URL) — the token is the secret and the download route enforces
 * each grant's limit + expiry. Sent per product via the per-product
 * "Email download link on order" setting.
 *
 * @see \Modules\DigitalProduct\Services\DigitalProductService::notifyForOrder()
 */
class DownloadLinksMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @param array<int, array{title: string, url: string, original_name: string}> $links
     */
    public function __construct(
        public array $links,
        public ?string $orderId = null,
        public ?string $siteName = null
    ) {
    }

    public function build(): self
    {
        $site = $this->siteName
            ?: (function_exists('get_option') ? (string) get_option('website_title', 'website') : '');
        $site = $site !== '' ? $site : 'Your download';

        return $this
            ->subject($site . ' — your download' . (count($this->links) > 1 ? 's' : '') . ' is ready')
            ->view('modules.digitalproduct::emails.download-links')
            ->with([
                'links' => $this->links,
                'orderId' => $this->orderId,
                'siteName' => $site,
            ]);
    }
}
