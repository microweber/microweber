<?php

namespace MicroweberPackages\User\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\ResetPassword;
use MicroweberPackages\Notification\Channels\AppMailChannel;
use MicroweberPackages\Option\Facades\Option;

class MailResetPasswordNotification extends ResetPassword {

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', AppMailChannel::class];
    }


    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        if (static::$createUrlCallback) {
            $url = call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        } else {
            $url = url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        }

        $mailMessage = new MailMessage();

        $templateId = Option::getValue('forgot_password_mail_template', 'users');
        $emailTemplate = get_mail_template_by_id($templateId, 'forgot_password');

        if ($emailTemplate) {

            $loader = new \Twig\Loader\ArrayLoader([
                'mailResetPassword' => $emailTemplate['message'],
            ]);
            $twig = new \Twig\Environment($loader);
            $parsedEmail = $twig->render('mailResetPassword', [
                    'email'=>$notifiable->getEmailForPasswordReset(),
                    'username'=>$notifiable->username,
                    'reset_password_link'=>$url,
                    'created_at'=>date('Y-m-d H:i:s')
                ]
            );
            $mailMessage->subject($emailTemplate['subject']);
            $mailMessage->action($emailTemplate['subject'], $url);
            $mailMessage->view('app::email.simple', ['content'=>$parsedEmail]);

        } else {
            $mailMessage = new MailMessage();
            $mailMessage->subject(Lang::get('Reset Password Notification'));
            $mailMessage->line(Lang::get('You are receiving this email because we received a password reset request for your account.'));
            $mailMessage->action(Lang::get('Reset Password'), $url);
            $mailMessage->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]));
            $mailMessage->line(Lang::get('If you did not request a password reset, no further action is required.'));
        }

        return $mailMessage;
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $notifiable->toArray();
    }
}