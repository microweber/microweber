<?php

namespace MicroweberPackages\Fortify\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use MicroweberPackages\Fortify\Contracts\TwoFactorAuthenticatable;

/**
 * Filament page for managing two-factor authentication settings.
 *
 * @property-read bool $enabled
 */
class TwoFactorSettingsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Two-Factor Auth';
    protected static ?string $title = 'Two-Factor Authentication';
    protected static string|\UnitEnum|null $navigationGroup = 'User Settings';
    protected static ?int $navigationSort = 999;
    protected static bool $shouldRegisterNavigation = true;
    protected static string $description = 'Configure your 2FA settings, including enabling/disabling 2FA, viewing recovery codes, and regenerating recovery codes.';

    protected string $view = 'microweber-fortify::filament.pages.two-factor-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];
    public bool $showingQrCode = false;
    public bool $showingRecoveryCodes = false;
    public bool $showingConfirmation = false;
    public ?string $code = null;
    public ?string $confirmablePassword = null;
    public bool $confirmingPassword = false;
    public ?string $pendingAction = null;

    /**
     * Get the authenticated user with 2FA capabilities.
     */
    protected function twoFactorUser(): ?TwoFactorAuthenticatable
    {
        /** @var TwoFactorAuthenticatable|null $user */
        $user = Auth::user();
        return $user;
    }

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'two-factor-settings';
    }

    public function mount(): void
    {
        $user = $this->twoFactorUser();
        if ($user && $user->getTwoFactorSecret() && !$user->getTwoFactorConfirmedAt()) {
            $this->showingQrCode = true;
            $this->showingConfirmation = true;
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('code')
                    ->label(__('Confirmation Code'))
                    ->placeholder(__('Enter the 6-digit code from your authenticator app'))
                    ->required()
                    ->visible(fn (): bool => $this->showingConfirmation)
                    ->numeric()
                    ->maxLength(6)
                    ->minLength(6),
            ])
            ->statePath('data');
    }

    public function enableTwoFactorAuthentication(): void
    {
        $this->pendingAction = 'enable';
        $this->confirmingPassword = true;
    }

    public function confirmPassword(): void
    {
        if (empty($this->confirmablePassword)) {
            Notification::make()->danger()->title(__('Password is required.'))->send();
            return;
        }

        $user = $this->twoFactorUser();
        if (!$user || !password_verify($this->confirmablePassword, $user->getPasswordHash())) {
            Notification::make()->danger()->title(__('This password does not match our records.'))->send();
            return;
        }

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
                Notification::make()->success()->title(__('Two factor authentication has been disabled.'))->send();
                break;

            case 'show-codes':
                $this->showingRecoveryCodes = true;
                break;

            case 'regenerate-codes':
                app(GenerateNewRecoveryCodes::class)($user);
                $user->refresh();
                $this->showingRecoveryCodes = true;
                Notification::make()->success()->title(__('Recovery codes have been regenerated.'))->send();
                break;
        }

        $this->confirmingPassword = false;
        $this->confirmablePassword = null;
        $this->pendingAction = null;
    }

    public function stopConfirmingPassword(): void
    {
        $this->confirmingPassword = false;
        $this->confirmablePassword = null;
        $this->pendingAction = null;
    }

    public function confirmTwoFactorAuthentication(): void
    {
        $code = $this->data['code'] ?? null;
        if (!$code) {
            Notification::make()->danger()->title(__('Please enter a confirmation code.'))->send();
            return;
        }

        $rateLimiterKey = '2fa_confirm_' . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            Notification::make()->danger()
                ->title(__('Too many attempts'))
                ->body(__('Please try again in :seconds seconds.', ['seconds' => RateLimiter::availableIn($rateLimiterKey)]))
                ->send();
            return;
        }

        $sanitised = preg_replace('/[^0-9]/', '', (string) $code);
        $code = str_pad((string) $sanitised, 6, '0', STR_PAD_LEFT);
        $user = $this->twoFactorUser();

        if (!$user) {
            return;
        }

        try {
            app(ConfirmTwoFactorAuthentication::class)($user, $code);
            $user->refresh();

            if ($user->getTwoFactorConfirmedAt()) {
                RateLimiter::clear($rateLimiterKey);
                $this->showingQrCode = false;
                $this->showingConfirmation = false;
                $this->showingRecoveryCodes = true;
                $this->data['code'] = null;
                Notification::make()->success()->title(__('Two factor authentication has been enabled.'))->send();
            } else {
                RateLimiter::hit($rateLimiterKey);
                $this->data['code'] = null;
                Notification::make()->danger()->title(__('The provided code was invalid.'))->send();
            }
        } catch (\Exception $e) {
            RateLimiter::hit($rateLimiterKey);
            $this->data['code'] = null;
            Notification::make()->danger()->title(__('The provided code was invalid.'))->send();
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

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check();
    }
}
