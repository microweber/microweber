<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Channels;

use Illuminate\Mail\SentMessage;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportException;

/**
 * Mail notification channel that skips send when no mailer is configured
 * and swallows transport errors (legacy Microweber behaviour).
 */
class AppMailChannel extends MailChannel
{
    /**
     * Send the given notification.
     *
     * Mail transport is configured once on boot by MailSenderServiceProvider /
     * NotificationServiceProvider — no per-send configMailDriver() call.
     *
     * @param  mixed  $notifiable
     */
    public function send($notifiable, Notification $notification): ?SentMessage
    {
        $driver = Config::get('mail.default') ?: Config::get('mail.driver');
        if (! $driver) {
            return null;
        }

        try {
            return parent::send($notifiable, $notification);
        } catch (TransportException) {
            // Swallow transport errors (legacy behaviour).
            return null;
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return null;
        }
    }
}
