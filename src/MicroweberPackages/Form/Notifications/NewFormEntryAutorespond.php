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


class NewFormEntryAutorespond extends Notification
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

        $mail = new MailMessage();

        $emailBcc = Option::getValue('email_bcc', $formId);
        if (!$emailBcc) {
            $emailBcc = Option::getValue('email_bcc', 'contact_form_default');
        }
        if (!$emailBcc) {
            $emailBcc = Option::getValue('email_bcc', 'email');
        }

        $emailReply = Option::getValue('email_reply', $formId);
        if (!$emailReply) {
            $emailReply = Option::getValue('email_reply', 'contact_form_default');
        }
        if (!$emailReply) {
            $emailReply = Option::getValue('email_reply', 'email');
        }

        $emailAutorespond = Option::getValue('email_autorespond', $formId);
        if (!$emailAutorespond) {
            $emailAutorespond = Option::getValue('email_autorespond', 'contact_form_default');
        }
        if (!$emailAutorespond) {
            $emailAutorespond = Option::getValue('email_autorespond', 'email');
        }

        if (!$emailAutorespond) {
            $emailAutorespond = _e('Thank you for your subscription!', true);
        }

        $emailAutorespondSubject = Option::getValue('email_autorespond_subject', $formId);
        if (!$emailAutorespondSubject) {
            $emailAutorespondSubject = Option::getValue('email_autorespond_subject', 'contact_form_default');
        }
        if (!$emailAutorespondSubject) {
            $emailAutorespondSubject = Option::getValue('email_autorespond_subject', 'email');
        }
        if (!$emailAutorespondSubject) {
            $emailAutorespondSubject = _e('Thank you for your message.', true);
        }

        $appendFiles = Option::getValue('append_files', $formId);

        if (!$appendFiles) {
            $appendFiles = Option::getValue('append_files', 'email');
        }


        if ($appendFiles) {
            $appendFilesAll = explode(',', $appendFiles);

            if ($appendFilesAll) {
                foreach ($appendFilesAll as $appendFile) {
                    $appendFile_path = url2dir($appendFile);

                    $file_extension = \Illuminate\Support\Facades\File::extension($appendFile_path);
                    $mail->attach($appendFile_path, [
                        'as' => basename($appendFile_path),
                        'mime' => $file_extension,
                    ]);
                }
            }
        }


        $emailsBccList = $this->_explodeMailsFromString($emailBcc);
        if (!empty($emailsBccList)) {
            $mail->bcc($emailsBccList);
        }

        $emailsReplyList = $this->_explodeMailsFromString($emailReply);
        if (!empty($emailsBccList)) {
            $mail->replyTo($emailsReplyList);
        }

        $mail->line($emailAutorespondSubject);

        $twig = new \MicroweberPackages\Template\Adapters\RenderHelpers\TwigRenderHelper();
        $parsedEmail = $twig->render($emailAutorespond, [
                'url' => url('/'),
                'created_at' => date('Y-m-d H:i:s')
            ]
        );
        $mail->subject($emailAutorespondSubject);
        $mail->view('app::email.simple', ['content' => $parsedEmail]);

        return $mail;
    }

    private function _explodeMailsFromString($emailsListString)
    {
        $emailsList = [];
        if (!empty($emailsListString)) {
            if (strpos($emailsListString, ',') !== false) {
                $explodedMails = explode(',', $emailsListString);
                if (is_array($explodedMails)) {
                    foreach ($explodedMails as $email) {
                        $email = trim($email);
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $emailsList[] = $email;
                        }
                    }
                }
            } else {
                if (filter_var($emailsListString, FILTER_VALIDATE_EMAIL)) {
                    $emailsList[] = $emailsListString;
                }
            }
        }

        return $emailsList;
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
