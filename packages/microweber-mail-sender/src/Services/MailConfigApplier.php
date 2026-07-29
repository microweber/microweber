<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Services;

use Illuminate\Support\Facades\Config;

/**
 * Applies mail-sender package config to Laravel's mail configuration once.
 *
 * Called from the service provider on boot (and again when CMS options change).
 * There is no per-instance configMailDriver() — configuration is service-level.
 */
class MailConfigApplier
{
    /**
     * Apply package config to the Laravel mailer.
     *
     * @param  array<string, mixed>|null  $override  Optional merge over config('mail-sender')
     */
    public function apply(?array $override = null): void
    {
        /** @var array<string, mixed> $cfg */
        $cfg = array_replace_recursive(
            is_array(config('mail-sender')) ? config('mail-sender') : [],
            $override ?? []
        );

        if (isset($override)) {
            config(['mail-sender' => $cfg]);
        }

        $transport = $this->stringOr($cfg['transport'] ?? null, 'smtp');

        // "config" means: leave Laravel mail.php as-is (host CMS / env owns it).
        if ($transport === 'config') {
            return;
        }

        /** @var array<string, mixed> $from */
        $from = is_array($cfg['from'] ?? null) ? $cfg['from'] : [];
        /** @var array<string, mixed> $smtp */
        $smtp = is_array($cfg['smtp'] ?? null) ? $cfg['smtp'] : [];

        $fromAddress = $this->nullableString($from['address'] ?? null);
        $fromName = $this->nullableString($from['name'] ?? null);
        $host = $this->stringOr($smtp['host'] ?? null, '127.0.0.1');
        $port = $this->intOr($smtp['port'] ?? null, 587);
        $username = $this->nullableString($smtp['username'] ?? null);
        $password = $this->nullableString($smtp['password'] ?? null);
        $encryption = $this->nullableString($smtp['encryption'] ?? null);

        // Resolve known transport presets.
        $laravelTransport = 'smtp';
        $resolvedHost = $host;
        $resolvedPort = $port;
        $resolvedEncryption = $encryption;

        switch ($transport) {
            case 'gmail':
                $resolvedHost = 'smtp.gmail.com';
                $resolvedPort = 587;
                $resolvedEncryption = 'tls';
                $laravelTransport = 'smtp';
                break;
            case 'cpanel':
                $resolvedPort = 587;
                $resolvedEncryption = 'tls';
                $laravelTransport = 'smtp';
                break;
            case 'plesk':
                $resolvedPort = 25;
                $resolvedEncryption = 'tls';
                $laravelTransport = 'smtp';
                break;
            case 'php':
                // PHP mail() — Laravel maps this via sendmail transport historically as "mail".
                $laravelTransport = 'sendmail';
                break;
            case 'log':
                $laravelTransport = 'log';
                break;
            case 'array':
                $laravelTransport = 'array';
                break;
            case 'smtp':
            default:
                $laravelTransport = 'smtp';
                break;
        }

        // Legacy flat keys still read by older Microweber code paths.
        Config::set('mail.from.name', $fromName);
        Config::set('mail.from.address', $fromAddress);
        Config::set('mail.username', $username);
        Config::set('mail.password', $password);
        Config::set('mail.host', $resolvedHost);
        Config::set('mail.port', $resolvedPort);
        Config::set('mail.encryption', $resolvedEncryption);
        Config::set('mail.driver', $laravelTransport);
        Config::set('mail.transport', $laravelTransport);

        // Modern Laravel mailers structure (v9+).
        Config::set('mail.default', $laravelTransport);
        Config::set('mail.mailers.smtp.transport', 'smtp');
        Config::set('mail.mailers.smtp.host', $resolvedHost);
        Config::set('mail.mailers.smtp.port', $resolvedPort);
        Config::set('mail.mailers.smtp.username', $username);
        Config::set('mail.mailers.smtp.password', $password);
        Config::set('mail.mailers.smtp.encryption', $resolvedEncryption);

        if ($fromAddress !== null) {
            Config::set('mail.from', [
                'address' => $fromAddress,
                'name' => $fromName ?? $fromAddress,
            ]);
        }
    }

    private function stringOr(mixed $value, string $default): string
    {
        if (is_string($value) && $value !== '') {
            return $value;
        }

        return $default;
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

    private function intOr(mixed $value, int $default): int
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return (int) $value;
        }

        return $default;
    }
}
