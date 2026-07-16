<?php

namespace MicroweberPackages\Fortify\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Component;
use MicroweberPackages\Fortify\Contracts\TwoFactorAuthenticatable;

/**
 * Livewire component for setting up and managing two-factor authentication.
 *
 * @property-read bool $enabled
 * @property-read bool $pending
 * @property-read array<int, string> $recoveryCodes
 * @property-read string $qrCodeSvg
 */
class TwoFactorSetupComponent extends Component
{
    public bool $showingQrCode = false;
    public bool $showingConfirmation = false;
    public bool $showingRecoveryCodes = false;
    public ?string $code = null;
    public ?string $confirmablePassword = null;
    public bool $confirmingPassword = false;
    public ?string $pendingAction = null;
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    /**
     * Get the authenticated user with 2FA capabilities.
     */
    protected function twoFactorUser(): ?TwoFactorAuthenticatable
    {
        /** @var TwoFactorAuthenticatable|null $user */
        $user = Auth::user();
        return $user;
    }

    public function mount(): void
    {
        $user = $this->twoFactorUser();
        if ($user && $user->getTwoFactorSecret() && !$user->getTwoFactorConfirmedAt()) {
            $this->showingQrCode = true;
            $this->showingConfirmation = true;
        }
    }

    public function enableTwoFactorAuthentication(): void
    {
        $this->pendingAction = 'enable';
        $this->confirmingPassword = true;
    }

    public function confirmPassword(): void
    {
        if (!$this->confirmablePassword) {
            $this->errorMessage = __('Password is required.');
            return;
        }

        $user = $this->twoFactorUser();
        if (!$user || !password_verify($this->confirmablePassword, $user->getPasswordHash())) {
            $this->errorMessage = __('This password does not match our records.');
            return;
        }

        $this->errorMessage = null;

        switch ($this->pendingAction) {
            case 'enable':
                app(EnableTwoFactorAuthentication::class)($user);
                $user->refresh();
                $this->showingQrCode = true;
                $this->showingConfirmation = true;
                $this->showingRecoveryCodes = false;
                break;

            case 'disable':
                app(DisableTwoFactorAuthentication::class)($user);
                $user->refresh();
                $this->showingQrCode = false;
                $this->showingConfirmation = false;
                $this->showingRecoveryCodes = false;
                $this->successMessage = __('Two factor authentication has been disabled.');
                break;

            case 'show-codes':
                $this->showingRecoveryCodes = true;
                break;

            case 'regenerate-codes':
                app(GenerateNewRecoveryCodes::class)($user);
                $user->refresh();
                $this->showingRecoveryCodes = true;
                $this->successMessage = __('Recovery codes have been regenerated.');
                break;
        }

        $this->confirmingPassword = false;
        $this->confirmablePassword = null;
        $this->pendingAction = null;
    }

    public function cancelConfirmPassword(): void
    {
        $this->confirmingPassword = false;
        $this->confirmablePassword = null;
        $this->pendingAction = null;
    }

    public function confirmTwoFactorCode(): void
    {
        $this->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ]);

        $rateLimiterKey = '2fa_confirm_' . Auth::id();
        /** @var int $maxAttempts */
        $maxAttempts = config('microweber-fortify.rate_limit.max_attempts', 5);

        if (RateLimiter::tooManyAttempts($rateLimiterKey, $maxAttempts)) {
            $this->errorMessage = __('Too many attempts. Please try again in :seconds seconds.', [
                'seconds' => RateLimiter::availableIn($rateLimiterKey),
            ]);
            return;
        }

        $user = $this->twoFactorUser();
        if (!$user) {
            return;
        }

        $sanitised = preg_replace('/[^0-9]/', '', (string) $this->code);
        $code = str_pad((string) $sanitised, 6, '0', STR_PAD_LEFT);

        try {
            app(ConfirmTwoFactorAuthentication::class)($user, $code);
            $user->refresh();

            if ($user->getTwoFactorConfirmedAt()) {
                RateLimiter::clear($rateLimiterKey);
                $this->showingQrCode = false;
                $this->showingConfirmation = false;
                $this->showingRecoveryCodes = true;
                $this->code = null;
                $this->errorMessage = null;
                $this->successMessage = __('Two factor authentication has been enabled.');
            } else {
                RateLimiter::hit($rateLimiterKey);
                $this->errorMessage = __('The provided two factor authentication code was invalid.');
                $this->code = null;
            }
        } catch (\Exception $e) {
            RateLimiter::hit($rateLimiterKey);
            $this->errorMessage = __('The provided two factor authentication code was invalid.');
            $this->code = null;
        }
    }

    public function disableTwoFactorAuthentication(): void
    {
        $this->pendingAction = 'disable';
        $this->confirmingPassword = true;
    }

    public function showRecoveryCodes(): void
    {
        $this->pendingAction = 'show-codes';
        $this->confirmingPassword = true;
    }

    public function regenerateRecoveryCodes(): void
    {
        $this->pendingAction = 'regenerate-codes';
        $this->confirmingPassword = true;
    }

    /** @return bool */
    public function getEnabledProperty(): bool
    {
        $user = $this->twoFactorUser();
        return $user !== null && $user->getTwoFactorSecret() !== null && $user->getTwoFactorConfirmedAt() !== null;
    }

    /** @return bool */
    public function getPendingProperty(): bool
    {
        $user = $this->twoFactorUser();
        return $user !== null && $user->getTwoFactorSecret() !== null && $user->getTwoFactorConfirmedAt() === null;
    }

    /** @return array<int, string> */
    public function getRecoveryCodesProperty(): array
    {
        $user = $this->twoFactorUser();
        if (!$user) {
            return [];
        }
        $codes = $user->getTwoFactorRecoveryCodes();
        if (empty($codes)) {
            return [];
        }
        return json_decode(decrypt($codes), true) ?? [];
    }

    /** @return string */
    public function getQrCodeSvgProperty(): string
    {
        $user = $this->twoFactorUser();
        if (!$user) {
            return '';
        }
        return $user->twoFactorQrCodeSvg();
    }

    /** @return \Illuminate\Contracts\View\View */
    public function render()
    {
        return view('microweber-fortify::livewire.two-factor-setup');
    }
}