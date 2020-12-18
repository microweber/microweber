<?php

namespace MicroweberPackages\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LegacyNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use InteractsWithQueue, SerializesModels;

    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data = false)
    {
        $this->data = $data;
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
         $data['notifiable'] = 'legacy';
         //$data['notifiable'] = $notifiable->toArray();
         $data['notification'] = $this->data;

         return $data;
    }
}
