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
use Modules\Billing\Notifications\Traits\HasEditableNotificationContent;


class UserDemoExpiredNotification extends Notification
{
    use Queueable;
    use InteractsWithQueue, SerializesModels;
    use HasEditableNotificationContent;

    public $notification;

    public $notificationData = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data = [])
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

    public function contentToArray()
    {
        return [
            'type' => class_basename(self::class),
            'subject' => 'Your demo account is expired',
            'content'=>'Hey, {{first_name}}, <br /> <br />
Unfortunately your websites is expired. <br />
You have a 10 days before the websites be deleted from our servers. <br />
You can still upgrade just for $1 <br /> <br />
Want to Upgrade now? <br />
<a href="{{upgrade_link}}">Upgrade</a>',
            'variables' => [
                'first_name',
                'last_name',
                'upgrade_link'
            ]
        ];
    }
}
