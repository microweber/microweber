<?php

namespace Modules\Vendors\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use MicroweberPackages\Notification\Channels\AppMailChannel;

/**
 * Tells a vendor that one of their products has been ordered.
 *
 * Multi-channel: the delivery channels are whatever the vendor opted into
 * (mail + in-app database by default) — resolved from the Vendor's
 * notify_channels. Built on the existing Microweber notification layer
 * (database channel + AppMailChannel), so it shows in the admin/profile
 * notification bell and is written to the mail log like every other
 * notification — no new infrastructure needed.
 */
class VendorOrderNotification extends Notification
{
    use Queueable;

    public $order;
    /** @var array<int,array{title:string,qty:int|float,price:float}> */
    public array $items;
    public float $subtotal;
    /** @var array<int,string> vendor-selected channels: mail|database */
    public array $channels;

    public function __construct($order, array $items, array $channels)
    {
        $this->order = $order;
        $this->items = $items;
        $this->subtotal = array_sum(array_map(
            fn ($i) => (float) ($i['price'] ?? 0) * (float) ($i['qty'] ?? 1),
            $items
        ));
        $this->channels = $channels;
    }

    public function via($notifiable): array
    {
        $via = [];
        if (in_array('database', $this->channels, true)) {
            $via[] = 'database';
        }
        if (in_array('mail', $this->channels, true)) {
            $via[] = AppMailChannel::class;
        }
        return $via;
    }

    public function icon(): string
    {
        return '<i class="mdi mdi-store"></i>';
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage())
            ->subject(__('You have a new order'))
            ->greeting(__('You have a new order!'))
            ->line(__('One or more of your products were just ordered:'));

        foreach ($this->items as $item) {
            $mail->line(sprintf(
                '• %s × %s — %s',
                $item['title'] ?? __('Product'),
                (string) ($item['qty'] ?? 1),
                function_exists('currency_format')
                    ? currency_format($item['price'] ?? 0)
                    : (string) ($item['price'] ?? 0)
            ));
        }

        $mail->line(__('Order total for your products:') . ' ' . (
            function_exists('currency_format') ? currency_format($this->subtotal) : (string) $this->subtotal
        ));

        $mail->action(__('Manage my products'), url('/profile/my-vendor-products'));
        $mail->line(__('Order reference:') . ' #' . ($this->order->id ?? '—'));

        return $mail;
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'vendor_order',
            'order_id' => $this->order->id ?? null,
            'items' => $this->items,
            'subtotal' => $this->subtotal,
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }
}
