<?php
/***
 * This notifications is only for admin panel
 */

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

        return $channels;
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
        if(!empty($data['form_data_values']) && is_array($data['form_data_values'])) {
            $formValuesArrCopy = $data['form_data_values']; //make copy in order not to edit the variable in iteration
            $uploads = [];
            $formValues = [];
            foreach($formValuesArrCopy as $fieldData) {
                if(isset($fieldData['field_type']) && $fieldData['field_type'] == 'upload') {
                    //Add to uploads arr
                    $uploads[] = [$fieldData['field_name'] => $fieldData['field_value_json']];
                    //Remove from form_values in order no to iterate them as normal key value pair in the view
                    unset($data['form_values'][$fieldData['field_name']]);
                } else {
                    if (is_array($fieldData['field_value_json']) && !empty($fieldData['field_value_json'])) {
                        $formValues[$fieldData['field_name']] = $fieldData['field_value_json'];
                    } else {
                        $formValues[$fieldData['field_name']] = $fieldData['field_value'];
                    }
                }
            }
            $data['form_values'] = $formValues;
            $data['form_values']['uploads'] = $uploads;
        }
        // <<< Move files in separate key

        $data['vals'] = !empty($data['form_values']) ? collect($data['form_values']) : []; //cast them to collection in order to be able to use ->split


        $data['ago'] = app()->format->ago($this->notification->data['created_at']);
        $data['is_read'] = false;
        if ($this->notification->read_at > 0) {
            $data['is_read'] = true;
        }

        return view('form::admin.notifications.new_form_entry', $data);
    }

}
