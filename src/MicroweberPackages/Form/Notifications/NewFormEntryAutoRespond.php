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


class NewFormEntryAutoRespond extends Notification
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
        return ['database', AppMailChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $formId = $this->formEntry->rel_id;

        $autoRespondSettings = mw()->forms_manager->getAutoRespondSettings($formId);

        $mail = new MailMessage();

        if ($autoRespondSettings['emailAppendFiles']) {

            $appendFilesAll = explode(',', $autoRespondSettings['emailAppendFiles']);

            if ($appendFilesAll) {
                foreach ($appendFilesAll as $appendFile) {
                    $appendFilePath = url2dir($appendFile);
                    $mail->attach($appendFilePath, [
                        'as' => basename($appendFilePath),
                        'mime' => \Illuminate\Support\Facades\File::mimeType($appendFilePath),
                    ]);
                }
            }
        }

        if ($autoRespondSettings['emailFrom']) {
            $mail->from($autoRespondSettings['emailFrom'], $autoRespondSettings['emailFromName']);
        }

        if ($autoRespondSettings['emailReplyTo']) {
            $emailsReplyList = mw()->forms_manager->explodeMailsFromString($autoRespondSettings['emailReplyTo']);
            if (!empty($emailsReplyList)) {
                $mail->replyTo($emailsReplyList);
            }
        }

        if ($autoRespondSettings['emailSubject']) {
            $mail->line($autoRespondSettings['emailSubject']);
            $mail->subject($autoRespondSettings['emailSubject']);
        }

        $twig = new \MicroweberPackages\View\TwigView();
        $string = $autoRespondSettings['emailContent'];

        if($string == strip_tags($string)) {
            // emailContent is plain text so we add br tags
            $autoRespondSettings['emailContent'] = nl2br($autoRespondSettings['emailContent']);
        }


        $parsedEmail = $twig->render($autoRespondSettings['emailContent'], [
                'url' => url('/'),
                'created_at' => date('Y-m-d H:i:s')
            ]
        );

        $mail->view('app::email.simple', ['content' => $parsedEmail]);

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
        $toView = $this->notification->data;
        $toView['ago'] = app()->format->ago($this->notification->data['created_at']);

        return view('form::admin.notifications.new_form_entry', $toView);
    }

}
