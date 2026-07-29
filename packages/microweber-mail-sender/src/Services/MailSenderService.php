<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Services;

use Illuminate\Support\Facades\Mail;
use MicroweberPackages\MailSender\Contracts\MailSenderContract;
use Throwable;

/**
 * Reusable mail sender service — no CMS entanglement.
 *
 * Mail transport / from-address config is applied once on boot via
 * {@see MailConfigApplier}. Callers only set message fields and send.
 */
class MailSenderService implements MailSenderContract
{
    /** @var array<string, mixed>|null */
    public static ?array $lastSend = null;

    public bool $debug = false;

    public bool $silentExceptions = true;

    public string|false $cc = false;

    public string|false $emailFrom = false;

    public string|false $emailFromName = false;

    public bool $emailNoCache = false;

    public string|false $emailCc = false;

    public string|false $emailReplyTo = false;

    /** @var array<int, string> */
    public array $emailAttachments = [];

    public bool $emailAddHostnameToSubject = false;

    public string|false $emailTo = false;

    public string|false $emailSubject = false;

    public string|false $emailMessage = false;

    /**
     * Optional content transformer (e.g. CMS site-url rewrite).
     *
     * @var callable(string): string|null
     */
    protected $contentTransformer = null;

    public function setEmailTo(string $email): static
    {
        $this->emailTo = $email;

        return $this;
    }

    public function setEmailSubject(string $subject): static
    {
        $this->emailSubject = $subject;

        return $this;
    }

    public function setEmailMessage(string $message): static
    {
        $this->emailMessage = $message;

        return $this;
    }

    public function setEmailHostnameToSubject(bool $hostname): static
    {
        $this->emailAddHostnameToSubject = $hostname;

        return $this;
    }

    public function setEmailNoCache(bool $cache): static
    {
        $this->emailNoCache = $cache;

        return $this;
    }

    public function setEmailCc(string $cc): static
    {
        $this->emailCc = $cc;
        $this->cc = $cc;

        return $this;
    }

    public function setEmailFrom(string $email): static
    {
        $this->emailFrom = $email;

        return $this;
    }

    public function setEmailFromName(string $name): static
    {
        $this->emailFromName = $name;

        return $this;
    }

    public function setEmailReplyTo(string $replyTo): static
    {
        $this->emailReplyTo = $replyTo;

        return $this;
    }

    /**
     * @param  array<int, string>  $attachments
     */
    public function setEmailAttachments(array $attachments): static
    {
        $this->emailAttachments = $attachments;

        return $this;
    }

    public function setCc(string $to): static
    {
        return $this->setEmailCc($to);
    }

    /**
     * @param  callable(string): string  $transformer
     */
    public function setContentTransformer(callable $transformer): static
    {
        $this->contentTransformer = $transformer;

        return $this;
    }

    /**
     * {@inheritdoc}
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
    ): bool {
        // Support legacy array payload: send(['to' => ..., 'subject' => ...]).
        if (is_array($to)) {
            $payload = $to;
            $to = isset($payload['to']) && is_string($payload['to']) ? $payload['to'] : false;
            if ($subject === false && isset($payload['subject']) && is_string($payload['subject'])) {
                $subject = $payload['subject'];
            }
            if ($message === false && isset($payload['message']) && is_string($payload['message'])) {
                $message = $payload['message'];
            }
            if ($emailFrom === false && isset($payload['from']) && is_string($payload['from'])) {
                $emailFrom = $payload['from'];
            }
            if ($fromName === false && isset($payload['from_name']) && is_string($payload['from_name'])) {
                $fromName = $payload['from_name'];
            }
            if ($replyTo === false && isset($payload['reply_to']) && is_string($payload['reply_to'])) {
                $replyTo = $payload['reply_to'];
            }
            if ($cc === false && isset($payload['cc']) && is_string($payload['cc'])) {
                $cc = $payload['cc'];
            }
            if ($attachments === [] && isset($payload['attachments']) && is_array($payload['attachments'])) {
                /** @var array<int, string> $atts */
                $atts = array_values(array_filter($payload['attachments'], 'is_string'));
                $attachments = $atts;
            }
        }

        if ($to === false || $to === '') {
            $to = $this->emailTo !== false ? $this->emailTo : false;
        }
        if ($subject === false || $subject === '') {
            $subject = $this->emailSubject !== false ? $this->emailSubject : false;
        }
        if ($message === false || $message === '') {
            $message = $this->emailMessage !== false ? $this->emailMessage : false;
        }
        if ($addHostnameToSubject === false) {
            $addHostnameToSubject = $this->emailAddHostnameToSubject;
        }
        if ($cc === false || $cc === '') {
            $cc = $this->emailCc !== false ? $this->emailCc : false;
        }
        if ($emailFrom === false || $emailFrom === '') {
            $emailFrom = $this->emailFrom !== false
                ? $this->emailFrom
                : $this->nullableString(config('mail-sender.from.address'));
        }
        if ($fromName === false || $fromName === '') {
            $fromName = $this->emailFromName !== false
                ? $this->emailFromName
                : $this->nullableString(config('mail-sender.from.name'));
        }
        if ($replyTo === false || $replyTo === '') {
            $replyTo = $this->emailReplyTo !== false ? $this->emailReplyTo : false;
        }
        if ($attachments === []) {
            $attachments = $this->emailAttachments;
        }

        if (!is_string($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (!is_string($subject)) {
            $subject = '';
        }
        if (!is_string($message)) {
            $message = '';
        }

        if ($addHostnameToSubject) {
            $hostname = $this->resolveHostname();
            if ($hostname !== '') {
                $subject = '[' . $hostname . '] ' . $subject;
            }
        }

        if (!config('mail-sender.enabled', true)) {
            return false;
        }

        try {
            $sender = $this->execSend(
                $to,
                $subject,
                $message,
                is_string($emailFrom) ? $emailFrom : false,
                is_string($fromName) ? $fromName : false,
                is_string($replyTo) ? $replyTo : false,
                $attachments
            );

            if (is_string($cc) && filter_var($cc, FILTER_VALIDATE_EMAIL)) {
                $this->execSend(
                    $cc,
                    $subject,
                    $message,
                    is_string($emailFrom) ? $emailFrom : false,
                    is_string($fromName) ? $fromName : false,
                    is_string($replyTo) ? $replyTo : false,
                    $attachments
                );
            }

            return $sender;
        } catch (Throwable $e) {
            if ($this->debug) {
                throw $e;
            }

            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execSend(
        string $to,
        string $subject,
        string $text,
        string|false $fromAddress = false,
        string|false $fromName = false,
        string|false $replyTo = false,
        array $attachments = [],
    ): bool {
        $fromAddress = $fromAddress !== false && $fromAddress !== ''
            ? $fromAddress
            : ($this->emailFrom !== false
                ? $this->emailFrom
                : $this->nullableString(config('mail-sender.from.address')));

        $fromName = $fromName !== false && $fromName !== ''
            ? $fromName
            : ($this->emailFromName !== false
                ? $this->emailFromName
                : $this->nullableString(config('mail-sender.from.name')));

        if ($this->contentTransformer !== null) {
            $text = ($this->contentTransformer)($text);
        }

        $content = [
            'content' => $text,
            'subject' => $subject,
            'to' => $to,
            'from' => $fromAddress,
            'from_name' => $fromName,
        ];

        self::$lastSend = $content;

        $view = $this->stringOr(config('mail-sender.view'), 'mail-sender::emails.simple');

        Mail::send(
            $view,
            $content,
            function ($message) use ($to, $subject, $fromAddress, $fromName, $replyTo, $attachments): void {
                $resolvedFromName = is_string($fromName) && $fromName !== ''
                    ? $fromName
                    : (is_string($fromAddress) ? $fromAddress : null);

                if (is_string($fromAddress) && $fromAddress !== '' && filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
                    $message->from($fromAddress, $resolvedFromName);
                }

                if (is_string($replyTo) && $replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                    $message->replyTo($replyTo);
                }

                $message->to($to)->subject($subject);

                foreach ($attachments as $attachmentFile) {
                    if (is_string($attachmentFile) && $attachmentFile !== '' && is_file($attachmentFile)) {
                        $message->attach($attachmentFile);
                    }
                }
            }
        );

        return true;
    }

    /**
     * Test send — admin auth must be enforced by the HTTP layer, not here.
     *
     * @param  array<string, mixed>  $params
     * @return array{success?: bool|string, error?: string}
     */
    public function test(array $params): array
    {
        $emailFrom = $this->nullableString(config('mail-sender.from.address'))
            ?? $this->nullableString(config('mail.from.address'));

        if ($emailFrom === null || $emailFrom === '') {
            return ['error' => 'Sender E-mail is not set'];
        }
        if (!filter_var($emailFrom, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Sender E-mail is not valid'];
        }

        $to = isset($params['to']) && is_string($params['to']) ? $params['to'] : null;
        if ($to === null || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Recipient E-mail is not valid'];
        }

        $subject = isset($params['subject']) && is_string($params['subject'])
            ? $params['subject']
            : 'Test mail';

        $message = isset($params['message']) && is_string($params['message'])
            ? $params['message']
            : 'Hello! This is a simple email message.';

        try {
            $send = $this->execSend($to, $subject, $message, $emailFrom);
        } catch (Throwable $e) {
            return ['error' => $e->getMessage()];
        }

        if ($send) {
            return ['success' => true];
        }

        return ['error' => 'Email is not sent'];
    }

    /**
     * {@inheritdoc}
     */
    public function getStatistics(): array
    {
        return [
            'enabled' => (bool) config('mail-sender.enabled', true),
            'transport' => config('mail-sender.transport', 'smtp'),
            'from_address' => config('mail-sender.from.address'),
            'from_name' => config('mail-sender.from.name'),
            'smtp_host' => config('mail-sender.smtp.host'),
            'smtp_port' => config('mail-sender.smtp.port'),
            'view' => config('mail-sender.view'),
            'hostname' => $this->resolveHostname(),
            'version' => '1.0.0',
            'last_send' => self::$lastSend,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function selfTest(): array
    {
        $errors = [];

        $enabled = (bool) config('mail-sender.enabled', true);
        if (!$enabled) {
            $errors[] = 'Mail sender is disabled';
        }

        $from = $this->nullableString(config('mail-sender.from.address'));
        if ($from !== null && !filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Configured from.address is not a valid email';
        }

        return [
            'ok' => $errors === [],
            'last_send' => self::$lastSend,
            'errors' => $errors,
            'stats' => $this->getStatistics(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getLastSend(): ?array
    {
        return self::$lastSend;
    }

    /**
     * @deprecated Use getLastSend() — kept as public static for CMS test BC.
     * @return array<string, mixed>|null
     */
    public static function lastSend(): ?array
    {
        return self::$lastSend;
    }

    protected function resolveHostname(): string
    {
        $configured = $this->nullableString(config('mail-sender.hostname'));
        if ($configured !== null) {
            return $configured;
        }

        $appUrl = $this->nullableString(config('app.url'));
        if ($appUrl !== null) {
            $host = parse_url($appUrl, PHP_URL_HOST);
            if (is_string($host) && $host !== '') {
                return $host;
            }
        }

        return '';
    }

    private function nullableString(mixed $value): ?string
    {
        if ($value === null || $value === false) {
            return null;
        }
        if (is_string($value)) {
            $trimmed = trim($value);

            return $trimmed === '' ? null : $trimmed;
        }
        if (is_scalar($value)) {
            return (string) $value;
        }

        return null;
    }

    private function stringOr(mixed $value, string $default): string
    {
        if (is_string($value) && $value !== '') {
            return $value;
        }

        return $default;
    }
}
