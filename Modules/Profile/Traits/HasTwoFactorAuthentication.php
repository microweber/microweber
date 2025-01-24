<?php

namespace Modules\Profile\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasTwoFactorAuthentication
{
    /**
     * Get the user's recovery codes.
     *
     * @return array
     */
    public function recoveryCodes(): array
    {
        return json_decode(decrypt($this->two_factor_recovery_codes), true) ?? [];
    }

    /**
     * Generate new recovery codes for the user.
     *
     * @return void
     */
    public function generateRecoveryCodes(): void
    {
        $this->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return Str::random(10).'-'.Str::random(10);
            })->all())),
        ])->save();
    }

    /**
     * Get the QR code SVG for the user's two factor authentication.
     *
     * @return string
     */
    public function twoFactorQrCodeSvg(): string
    {
        $provider = app(\Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider::class);
        $secret = decrypt($this->two_factor_secret);
        
        logger()->debug('2FA QR Code generation:', [
            'has_secret' => !empty($this->two_factor_secret),
            'secret_length' => strlen($secret),
        ]);

        $appName = config('app.name', 'Laravel');
        $companyName = config('app.name', 'Laravel');

        return $provider->qrCodeSvg(
            $secret,
            200,
            "otpauth://totp/{$appName}:{$this->email}?secret={$secret}&issuer={$companyName}"
        );
    }

    /**
     * Determine if two-factor authentication is enabled.
     *
     * @return bool
     */
    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_secret) && ! is_null($this->two_factor_confirmed_at);
    }

    /**
     * Check if the given code is valid for two-factor authentication.
     *
     * @param string $code
     * @return bool
     */
    public function validateTwoFactorCode(string $code): bool
    {
        return app(\Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider::class)->verify(
            decrypt($this->two_factor_secret), $code
        );
    }
}