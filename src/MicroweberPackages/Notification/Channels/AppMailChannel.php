<?php

namespace MicroweberPackages\Notification\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportException;

class AppMailChannel extends MailChannel
{
    /**
     * Send the given notification.
     *
     * Mail transport is configured once on boot by MailSenderServiceProvider /
     * NotificationServiceProvider — no per-send configMailDriver() call.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Skip if no mail driver/default is configured.
        $driver = Config::get('mail.default') ?: Config::get('mail.driver');
        if (!$driver) {
            return;
        }

        try {
            return parent::send($notifiable, $notification);
        } catch (TransportException $e) {
            // swallow transport errors (legacy behaviour)
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
