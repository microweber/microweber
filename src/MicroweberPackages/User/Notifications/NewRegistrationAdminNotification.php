<?php

namespace MicroweberPackages\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MicroweberPackages\Notification\Channels\AppMailChannel;
use MicroweberPackages\Option\Facades\Option;


class NewRegistrationAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use InteractsWithQueue, SerializesModels;

    public $data;
    public $notification;

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
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', AppMailChannel::class]; //return [AppMailChannel::class];
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

        $mail->greeting('Hello, '. $notifiable->username . '!');
        $mail->line('There has a new user registred on webiste.');
        $mail->line('Username: ' . $this->data->username);
        $mail->line('Email: ' . $this->data->email);
        $mail->line('Created At: ' . $this->data->created_at);
        $mail->action('Visit admin panel', admin_url());

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->data;
    }

    public function setNotification($noification)
    {
        $this->notification = $noification;
    }
}
