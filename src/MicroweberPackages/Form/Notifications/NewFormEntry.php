<?php

namespace MicroweberPackages\Form\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MicroweberPackages\Notification\Channels\AppMailChannel;
use MicroweberPackages\Option\Facades\Option;


class NewFormEntry extends Notification
{
    use Queueable;
    use InteractsWithQueue, SerializesModels;

    public $notification;
    public $formEntry;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($formEntry = false)
    {
        $this->formEntry = $formEntry;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $rel_id = ($this->formEntry->rel_id);

        $skip_saving_emails = false;
        $channels = [];

        $default_mod_id = 'contact_form_default';

        if ($rel_id) {
            $skip_saving_emails = app()->option_manager->get('skip_saving_emails', $rel_id) == 'y';
        }
        if (!$skip_saving_emails) {
            $skip_saving_emails = app()->option_manager->get('skip_saving_emails', $default_mod_id) == 'y';
        }

        if (!$skip_saving_emails) {
            $channels[] = 'database';
        }

        $channels[] = AppMailChannel::class;

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $hostname = mw()->url_manager->hostname();

        $mail = new MailMessage();
        $mail->subject('[' . $hostname . '] ' . 'New form entry');
        $mail->view('app::email.simple', ['content' => app()->format->array_to_ul($this->formEntry->form_values)]);

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
        return $this->formEntry;
    }

    public function setNotification($noification)
    {
        $this->notification = $noification;
    }

    public function message()
    {
        $data = $this->notification->data;
        $data['ago'] = app()->format->ago($data['created_at']);
        $data['vals']= collect($data['form_values']); //cast them to collection in order to be able to use ->split

        return view('form::admin.notifications.new_form_entry', $data);
    }

}
