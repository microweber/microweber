<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Contracts;

/**
 * Contract for the reusable mail sender service.
 */
interface MailSenderContract
{
    /**
     * Send an email.
     *
     * @param  array<int, string>|string|false  $to
     * @param  array<int, string>  $attachments
     */
    public function send(
        string|array|false $to = false,
        string|false $subject = false,
        string|false $message = false,
        bool $addHostnameToSubject = false,
        bool $noCache = false,
        string|false $cc = false,
        string|false $emailFrom = false,
        string|false $fromName = false,
        string|false $replyTo = false,
        array $attachments = [],
    ): bool;

    /**
     * Low-level send used by providers that already validated addresses.
     *
     * @param  array<int, string>  $attachments
     */
    public function execSend(
        string $to,
        string $subject,
        string $text,
        string|false $fromAddress = false,
        string|false $fromName = false,
        string|false $replyTo = false,
        array $attachments = [],
    ): bool;

    /**
     * @return array<string, mixed>
     */
    public function getStatistics(): array;

    /**
     * @return array{ok: bool, last_send: array<string, mixed>|null, errors: list<string>}
     */
    public function selfTest(): array;

    /**
     * @return array<string, mixed>|null
     */
    public function getLastSend(): ?array;
}
