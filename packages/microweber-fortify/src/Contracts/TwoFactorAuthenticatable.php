<?php

namespace MicroweberPackages\Fortify\Contracts;

/**
 * Contract for a User model that supports two-factor authentication.
 *
 * Models implementing this contract MUST have the following database columns:
 *   - two_factor_secret (text, nullable)
 *   - two_factor_recovery_codes (text, nullable)
 *   - two_factor_confirmed_at (timestamp, nullable)
 */
interface TwoFactorAuthenticatable extends \Illuminate\Contracts\Auth\Authenticatable
{
    /**
     * Get the encrypted two-factor secret.
     */
    public function getTwoFactorSecret(): ?string;

    /**
     * Get the encrypted two-factor recovery codes JSON.
     */
    public function getTwoFactorRecoveryCodes(): ?string;

    /**
     * Get the two-factor confirmed-at timestamp.
     */
    public function getTwoFactorConfirmedAt(): ?\Illuminate\Support\Carbon;

    /**
     * Get the user's hashed password.
     */
    public function getPasswordHash(): string;

    /**
     * Determine if two-factor authentication has been enabled and confirmed.
     */
    public function hasTwoFactorEnabled(): bool;

    /**
     * Generate the QR code SVG for 2FA setup.
     */
    public function twoFactorQrCodeSvg(): string;

    /**
     * Get the decrypted two-factor secret.
     */
    public function getDecryptedTwoFactorSecret(): ?string;

    /**
     * Get the TOTP provisioning URI.
     */
    public function twoFactorProvisioningUrl(): string;

    /**
     * Get the user's two-factor authentication recovery codes.
     *
     * @return array<int, string>
     */
    public function recoveryCodes(): array;

    /**
     * Generate new recovery codes.
     */
    public function generateRecoveryCodes(): void;

    /**
     * Validate a given 2FA code against the user's secret.
     */
    public function validateTwoFactorCode(string $code): bool;

    /**
     * Use a recovery code (marks it as used).
     */
    public function useRecoveryCode(string $code): bool;

    /**
     * Refresh the model from the database.
     *
     * @return static
     */
    public function refresh();
}