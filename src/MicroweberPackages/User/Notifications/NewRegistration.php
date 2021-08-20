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


class NewRegistration extends Notification implements ShouldQueue
{
    use Queueable;
    use InteractsWithQueue, SerializesModels;

    public $user;
    public $notification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user = false)
    {
        $this->user = $user;
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

        $templateId = Option::getValue('new_user_registration_mail_template', 'users');
        $template = get_mail_template_by_id($templateId, 'new_user_registration');

        if ($template) {

            $twig = new \MicroweberPackages\Template\Adapters\RenderHelpers\TwigRenderHelper();
            $parsedEmail = $twig->render($template['message'], [
                    'email' => $notifiable->getEmailForPasswordReset(),
                    'username' => $notifiable->username,
                    'first_name' => $notifiable->first_name,
                    'last_name' => $notifiable->last_name,
                    'url' => url('/'),
                    'created_at' => date('Y-m-d H:i:s')
                ]
            );
            $mail->subject($template['subject']);
            $mail->view('app::email.simple', ['content' => $parsedEmail]);
        } else {
            $mail->line('Thank you for your registration.');
            $mail->action('Visit our website', url('/'));
        }

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
        return $this->user;
    }

    public function setNotification($noification)
    {
        $this->notification = $noification;
    }

    public function message()
    {
        $toView = [];
        $toView['id'] = $this->notification->id;
        $toView['user_id'] = $this->notification->data['id'];
        $toView['display_name'] = '';
        if (isset($this->notification->data['first_name']) && !empty($this->notification->data['first_name'])) {
            $toView['display_name'] = $this->notification->data['first_name'];
        }
        if (isset($this->notification->data['last_name']) && !empty($this->notification->data['last_name'])) {
            $toView['display_name'] .= ' ' . $this->notification->data['last_name'];
        }

        $toView['display_email'] = '';
        if (isset($this->notification->data['email']) && !empty($this->notification->data['email'])) {
            $toView['display_email'] .= ' ' . $this->notification->data['email'];
        } else if (isset($this->notification->data['username']) && !empty($this->notification->data['username'])) {
            $toView['display_email'] = $this->notification->data['username'];
        }
        if (empty($toView['display_name'])) {
            if (isset($this->notification->data['username']) && !empty($this->notification->data['username'])) {
                $toView['display_name'] = $this->notification->data['username'];
            } else if (isset($this->notification->data['email']) && !empty($this->notification->data['email'])) {
                $toView['display_name'] .= ' ' . $this->notification->data['email'];
            }
        }

        $toView['created_at'] = $this->notification->data['created_at'];
        $toView['ago'] = app()->format->ago($this->notification->data['created_at']);

        return view('user::admin.notifications.new_user_registration', $toView);

    }
}
