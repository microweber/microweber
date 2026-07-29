<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\MailSender\Services\MailSenderService;

/**
 * @method static MailSenderService setEmailTo(string $email)
 * @method static MailSenderService setEmailSubject(string $subject)
 * @method static MailSenderService setEmailMessage(string $message)
 * @method static MailSenderService setEmailFrom(string $email)
 * @method static MailSenderService setEmailFromName(string $name)
 * @method static MailSenderService setEmailReplyTo(string $replyTo)
 * @method static MailSenderService setEmailCc(string $cc)
 * @method static MailSenderService setEmailAttachments(array<int, string> $attachments)
 * @method static bool send(string|array<string, mixed>|false $to = false, string|false $subject = false, string|false $message = false, bool $addHostnameToSubject = false, bool $noCache = false, string|false $cc = false, string|false $emailFrom = false, string|false $fromName = false, string|false $replyTo = false, array<int, string> $attachments = [])
 * @method static bool execSend(string $to, string $subject, string $text, string|false $fromAddress = false, string|false $fromName = false, string|false $replyTo = false, array<int, string> $attachments = [])
 * @method static array{success?: bool|string, error?: string} test(array<string, mixed> $params)
 * @method static array<string, mixed> getStatistics()
 * @method static array{ok: bool, last_send: array<string, mixed>|null, errors: list<string>} selfTest()
 * @method static array<string, mixed>|null getLastSend()
 *
 * @see MailSenderService
 */
class MailSender extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MailSenderService::class;
    }
}
