<?php

namespace MicroweberPackages\Fortify\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Component;

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

    public function mount(): void
    {
        $user = Auth::user();
        if ($user && $user->two_factor_secret && !$user->two_factor_confirmed_at) {
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

        $user = Auth::user();
        if (!password_verify($this->confirmablePassword, $user->password)) {
            $this->errorMessage = __('This password does not match our records.');
            return;
        }

        $this->errorMessage = null;

        switch ($this->pendingAction) {
            case 'enable':
                $enable = app(EnableTwoFactorAuthentication::class);
                $enable($user);
                $user->refresh();
                $this->showingQrCode = true;
                $this->showingConfirmation = true;
                $this->showingRecoveryCodes = false;
                break;

            case 'disable':
                $disable = app(DisableTwoFactorAuthentication::class);
                $disable($user);
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
                $generate = app(GenerateNewRecoveryCodes::class);
                $generate($user);
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
        $maxAttempts = config('microweber-fortify.rate_limit.max_attempts', 5);

        if (RateLimiter::tooManyAttempts($rateLimiterKey, $maxAttempts)) {
            $this->errorMessage = __('Too many attempts. Please try again in :seconds seconds.', [
                'seconds' => RateLimiter::availableIn($rateLimiterKey),
            ]);
            return;
        }

        $user = Auth::user();
        $code = str_pad(preg_replace('/[^0-9]/', '', $this->code), 6, '0', STR_PAD_LEFT);

        try {
            $confirm = app(ConfirmTwoFactorAuthentication::class);
            $confirm($user, $code);

            $user->refresh();

            if ($user->two_factor_confirmed_at) {
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

    public function getEnabledProperty(): bool
    {
        $user = Auth::user();
        return $user && !empty($user->two_factor_secret) && !is_null($user->two_factor_confirmed_at);
    }

    public function getPendingProperty(): bool
    {
        $user = Auth::user();
        return $user && !empty($user->two_factor_secret) && is_null($user->two_factor_confirmed_at);
    }

    public function getRecoveryCodesProperty(): array
    {
        $user = Auth::user();
        if (!$user || empty($user->two_factor_recovery_codes)) {
            return [];
        }
        return json_decode(decrypt($user->two_factor_recovery_codes), true) ?? [];
    }

    public function getQrCodeSvgProperty(): string
    {
        $user = Auth::user();
        if (!$user || !method_exists($user, 'twoFactorQrCodeSvg')) {
            return '';
        }
        return $user->twoFactorQrCodeSvg();
    }

    public function render()
    {
        return view('microweber-fortify::livewire.two-factor-setup');
    }
}