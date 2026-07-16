<?php

namespace MicroweberPackages\Fortify\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Provides two-factor authentication capabilities for a User model.
 *
 * @property string|null $two_factor_secret           Encrypted TOTP secret key.
 * @property string|null $two_factor_recovery_codes   Encrypted JSON array of recovery codes.
 * @property \Illuminate\Support\Carbon|null $two_factor_confirmed_at  Timestamp of 2FA confirmation.
 * @property string|null $email
 * @property string|null $username
 */
trait HasTwoFactorAuthentication
{
    use TwoFactorAuthenticatable;

    /**
     * Get the encrypted two-factor secret.
     */
    public function getTwoFactorSecret(): ?string
    {
        return $this->two_factor_secret;
    }

    /**
     * Get the encrypted two-factor recovery codes JSON.
     */
    public function getTwoFactorRecoveryCodes(): ?string
    {
        return $this->two_factor_recovery_codes;
    }

    /**
     * Get the two-factor confirmed-at timestamp.
     */
    public function getTwoFactorConfirmedAt(): ?\Illuminate\Support\Carbon
    {
        $value = $this->two_factor_confirmed_at;
        if ($value instanceof \Illuminate\Support\Carbon) {
            return $value;
        }
        return $value ? \Illuminate\Support\Carbon::parse($value) : null;
    }

    /**
     * Get the user's hashed password.
     */
    public function getPasswordHash(): string
    {
        return (string) $this->password;
    }

    /**
     * Get the user's two-factor authentication recovery codes.
     *
     * @return array<int, string>
     */
    public function recoveryCodes(): array
    {
        if (empty($this->two_factor_recovery_codes)) {
            return [];
        }

        return json_decode(decrypt($this->two_factor_recovery_codes), true) ?? [];
    }

    /**
     * Generate new recovery codes.
     */
    public function generateRecoveryCodes(): void
    {
        $count = config('microweber-fortify.recovery_codes_count', 8);

        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(
                Collection::times($count, function () {
                    return Str::random(10) . '-' . Str::random(10);
                })->all()
            )),
        ])->save();
    }

    /**
     * Get the decrypted two-factor secret.
     */
    public function getDecryptedTwoFactorSecret(): ?string
    {
        if (empty($this->two_factor_secret)) {
            return null;
        }

        return decrypt($this->two_factor_secret);
    }

    /**
     * Generate the QR code SVG for 2FA setup.
     */
    public function twoFactorQrCodeSvg(): string
    {
        $secret = $this->getDecryptedTwoFactorSecret();
        if (!$secret) {
            return '';
        }

        $issuer = config('microweber-fortify.issuer') ?: config('app.name', 'Laravel');
        $email = $this->email ?? $this->username ?? 'user';
        $size = config('microweber-fortify.qr_code_size', 200);

        $otpauthUrl = "otpauth://totp/" . rawurlencode($issuer) . ":" . rawurlencode($email)
            . "?secret=" . $secret
            . "&issuer=" . rawurlencode($issuer);

        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($otpauthUrl);
    }

    /**
     * Get the TOTP provisioning URI.
     */
    public function twoFactorProvisioningUrl(): string
    {
        $secret = $this->getDecryptedTwoFactorSecret();
        if (!$secret) {
            return '';
        }

        $issuer = config('microweber-fortify.issuer') ?: config('app.name', 'Laravel');
        $email = $this->email ?? $this->username ?? 'user';

        return "otpauth://totp/" . rawurlencode($issuer) . ":" . rawurlencode($email)
            . "?secret=" . $secret
            . "&issuer=" . rawurlencode($issuer);
    }

    /**
     * Determine if two-factor authentication has been enabled and confirmed.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret) && !is_null($this->two_factor_confirmed_at);
    }

    /**
     * Validate a given 2FA code against the user's secret.
     */
    public function validateTwoFactorCode(string $code): bool
    {
        $secret = $this->getDecryptedTwoFactorSecret();
        if (!$secret) {
            return false;
        }

        $google2fa = new Google2FA();
        return $google2fa->verifyKey($secret, $code);
    }

    /**
     * Use a recovery code (marks it as used).
     */
    public function useRecoveryCode(string $code): bool
    {
        $codes = $this->recoveryCodes();

        if (!in_array($code, $codes)) {
            return false;
        }

        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(
                array_values(array_filter($codes, fn($c) => $c !== $code))
            )),
        ])->save();

        return true;
    }
}