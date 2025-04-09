<?php

namespace Modules\Billing\Notifications\Traits;

use Illuminate\Notifications\Messages\MailMessage;
use MicroweberPackages\Notification\Models\MailTemplate;

trait HasEditableNotificationContent
{

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $html = '';

        $mail = new MailMessage();

        $content = $this->contentToArray();
        $mailTemplate = MailTemplate::where('type', $content['type'])->first();

        $saasWebsiteTitle = get_option('website_title', 'website');
        $saasSupportEmail = get_option('support_email', 'panel');

        $emailVariables = [];
        $emailVariables['saas_website_title'] = $saasWebsiteTitle;
        $emailVariables['saas_support_email'] = $saasSupportEmail;

        $emailVariables = array_merge($emailVariables, $this->notificationData);

        $twig = new \MicroweberPackages\View\TwigView();


        if ($mailTemplate) {
            $mail->subject($twig->render($mailTemplate->subject, $emailVariables));
            $html = $mailTemplate->message;
        } else {
            $mail->subject($twig->render($content['subject'], $emailVariables));
            $html = $content['message'];
        }

        $parsedEmail = $twig->render($html, $emailVariables);

        $fullEmail = '';
        if (str_contains($parsedEmail, '<html>')) {
            $fullEmail = $parsedEmail;
        } else {
            $fullEmail = $this->getDefaultMailTemplateHeader();
            $fullEmail .= $parsedEmail;
            $fullEmail .= $this->getDefaultMailTemplateFooter();
        }


        $mail->view('panel::admin.notification-mail-html-render', [
            'html' => $fullEmail
        ]);

        return $mail;
    }

    public function getDefaultMailTemplateHeader()
    {
        return view('panel::admin.default-mail-template-header')->render();
    }

    public function getDefaultMailTemplateFooter()
    {
        return view('panel::admin.default-mail-template-footer')->render();
    }

}
