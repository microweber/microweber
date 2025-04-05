<?php

namespace Modules\Billing\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MicroweberPackages\Notification\Channels\AppMailChannel;
use MicroweberPackages\Option\Facades\Option;

class UserPaymentCanceledNotification extends Notification
{
    use Queueable;
    use InteractsWithQueue, SerializesModels;

    public $notification;

    public $notificationData = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->notificationData = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [AppMailChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = new MailMessage();
        $mail->subject('Payment canceled');
        $mail->greeting('Hey, '. $notifiable->first_name);
        $mail->line('You have canceled your subscription plan.');

        $mail->line('Please update your payment details in order to continue using the service.');

        $mail->action('Update payment details', url('/projects/plans'));

        $mail->line('Thank you for using our service!');

        return $mail;
    }

}
