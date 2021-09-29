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


class NewFormEntryToMail extends Notification
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
        $relId = $this->formEntry->rel_id;

        $skipSavingEmails = false;
        $channels = [];

        if ($relId) {
            $skipSavingEmails = Option::getValue('skip_saving_emails', $relId);
        }

        if (!$skipSavingEmails) {
            $skipSavingEmails = Option::getValue('skip_saving_emails', 'contact_form_default');
        }

        if (!$skipSavingEmails) {
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
        $mail = new MailMessage();

        $hostname = mw()->url_manager->hostname();

        $formName = Option::getValue('form_name', $this->formEntry->rel_id);
        if ($formName) {
            $emailSubject = '[' . $hostname . '] ' . _e('New entry from ', true) . $formName;
        } else {
            $emailSubject = '[' . $hostname . '] ' . _e('New form entry', true);
        }

        $content = app()->format->array_to_ul($this->formEntry->form_values);

        $userEmails = false;
        $formValues = $this->formEntry->form_values;
        if (!empty($formValues)) {
            foreach ($formValues as $value) {
                if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $userEmails[] = $value;
                }
            }
        }

        if (!empty($userEmails)) {
            $mail->replyTo($userEmails);
        }

        $mail->subject($emailSubject);
        $mail->view('app::email.simple', ['content' => $content]);

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

        // >>> Move files in separate key
        if(!empty($data['form_values']) && is_array($data['form_values'])) {
            $formValuesArrCopy = $data['form_values']; //make copy in order not to edit the variable in iteration
            $uploads = [];

            foreach($formValuesArrCopy as $key => $val) {
                if(isset($val['type']) && $val['type'] == 'upload') {
                    //Add to uploads arr
                    $uploads[] = [$key => $val];

                    //Remove from form_values in order no to iterate them as normal key value pair in the view
                    unset($data['form_values'][$key]);
                }
            }

            $data['form_values']['uploads'] = $uploads;
        }
        // <<< Move files in separate key

        $data['vals']= !empty($data['form_values']) ? collect($data['form_values']) : []; //cast them to collection in order to be able to use ->split

        return view('form::admin.notifications.new_form_entry', $data);
    }

}
