<?php

declare(strict_types=1);

use MicroweberPackages\MailSender\Services\MailSenderService;

if (!function_exists('mail_sender')) {
    /**
     * Resolve a fresh MailSenderService instance.
     */
    function mail_sender(): MailSenderService
    {
        return app(MailSenderService::class);
    }
}

if (!function_exists('mail_sender_send')) {
    /**
     * Convenience helper to send a simple email.
     *
     * @param  array<int, string>  $attachments
     */
    function mail_sender_send(
        string $to,
        string $subject,
        string $message,
        string|false $from = false,
        string|false $fromName = false,
        array $attachments = [],
    ): bool {
        return app(MailSenderService::class)->send(
            $to,
            $subject,
            $message,
            false,
            false,
            false,
            $from,
            $fromName,
            false,
            $attachments
        );
    }
}

if (!function_exists('mail_sender_stats')) {
    /**
     * @return array<string, mixed>
     */
    function mail_sender_stats(): array
    {
        return app(MailSenderService::class)->getStatistics();
    }
}
