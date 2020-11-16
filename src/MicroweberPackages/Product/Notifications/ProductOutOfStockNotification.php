<?php

namespace MicroweberPackages\Product\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductOutOfStockNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use InteractsWithQueue, SerializesModels;

    public $product;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->product;
    }

    public function message()
    {

        /*$notification = array();
        $notification['rel_type'] = 'content';
        $notification['rel_id'] = $item['rel_id'];
        $notification['title'] = 'Your item is out of stock!';
        $notification['description'] = 'You sold all items you had in stock. Please update your quantity';

        */
    }
}
