<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Modules\Profile\Models\User;

class TwoFactorAuth extends Page
{
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static string $view = 'modules.profile::pages.two-factor-auth';
    protected static ?string $slug = 'two-factor-auth';
    protected static bool $shouldRegisterNavigation = true;
    
    public ?array $data = [];
    public $showingQrCode = false;
    public $showingRecoveryCodes = false;
    public $showingConfirmation = false;
    public $code;
    public $recoveryCodes = [];
    public $confirmablePassword;
    public $confirmingPassword = false;

    protected function getRateLimiterKey(): string
    {
        return '2fa_'.Auth::id();
    }

    public function mount(): void
    {
        if (Auth::user()->two_factor_secret &&
            !Auth::user()->two_factor_confirmed_at) {
            $this->showingQrCode = true;
            $this->showingConfirmation = true;
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label(__('Confirmation Code'))
                    ->placeholder(__('Enter the code from your authenticator app'))
                    ->required()
                    ->visible(fn() => $this->showingConfirmation)
                    ->numeric()
                    ->maxLength(6)
                    ->minLength(6),
            ])
            ->statePath('data');
    }

    protected function getConfirmPasswordFormSchema(): array
    {
        return [
            TextInput::make('confirmablePassword')
                ->label(__('Password'))
                ->password()
                ->required()
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('confirm')
                ->label(__('Confirm'))
                ->action(fn() => $this->confirmTwoFactorAuthentication(app(ConfirmTwoFactorAuthentication::class)))
                ->visible(fn() => $this->showingConfirmation),
            Action::make('enable')
                ->label(__('Enable'))
                ->action(fn() => $this->enableTwoFactorAuthentication(app(EnableTwoFactorAuthentication::class)))
                ->visible(fn() => !Auth::user()->two_factor_secret),
            Action::make('disable')
                ->label(__('Disable'))
                ->color('danger')
                ->action(fn() => $this->disableTwoFactorAuthentication(app(DisableTwoFactorAuthentication::class)))
                ->visible(fn() => Auth::user()->two_factor_secret),
            Action::make('showRecoveryCodes')
                ->label(__('Show Recovery Codes'))
                ->action('showRecoveryCodes')
                ->visible(fn() => Auth::user()->two_factor_secret && !$this->showingRecoveryCodes),
            Action::make('regenerateRecoveryCodes')
                ->label(__('Regenerate Recovery Codes'))
                ->action('regenerateRecoveryCodes')
                ->visible(fn() => Auth::user()->two_factor_secret)
        ];
    }

    protected $pendingAction = null;

    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable): void
    {
        if (!$this->confirmingPassword) {
            $this->pendingAction = 'enable';
            $this->startConfirmingPassword();
            return;
        }

        $user = Auth::user();
        $enable($user);
        
        if ($user instanceof User) {
            $this->recoveryCodes = $user->recoveryCodes();
        }
        
        $this->showingQrCode = true;
        $this->showingConfirmation = true;
        $this->showingRecoveryCodes = true;
    }

    public function confirmTwoFactorAuthentication(ConfirmTwoFactorAuthentication $confirm): void
    {
        if (RateLimiter::tooManyAttempts($this->getRateLimiterKey(), config('profile.twofactor.rate_limit.max_attempts'))) {
            Notification::make()
                ->danger()
                ->title(__('Too many attempts'))
                ->body(__('Please try again in :seconds seconds.', [
                    'seconds' => RateLimiter::availableIn($this->getRateLimiterKey())
                ]))
                ->send();
            return;
        }

        if (!$this->code || !preg_match('/^\d{6}$/', $this->code)) {
            RateLimiter::hit($this->getRateLimiterKey());
            Notification::make()
                ->danger()
                ->title(__('Invalid code'))
                ->body(__('Please enter a valid 6-digit code.'))
                ->send();
            return;
        }

        if (!$confirm(Auth::user(), $this->code)) {
            RateLimiter::hit($this->getRateLimiterKey());
            Notification::make()
                ->danger()
                ->title(__('Invalid code'))
                ->body(__('The code you entered is invalid.'))
                ->send();
            return;
        }

        RateLimiter::clear($this->getRateLimiterKey());
        
        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        Notification::make()
            ->success()
            ->title(__('Success'))
            ->body(__('Two factor authentication has been enabled.'))
            ->send();
    }

    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable): void
    {
        if (!$this->confirmingPassword) {
            $this->pendingAction = 'disable';
            $this->startConfirmingPassword();
            return;
        }

        $disable(Auth::user());

        $this->showingQrCode = false;
        $this->showingRecoveryCodes = false;
        Notification::make()
            ->success()
            ->title(__('Success'))
            ->body(__('Two factor authentication has been disabled.'))
            ->send();
    }

    public function showRecoveryCodes(): void
    {
        if (!$this->confirmingPassword) {
            $this->pendingAction = 'show-codes';
            $this->startConfirmingPassword();
            return;
        }
        
        $this->showingRecoveryCodes = true;
    }

    public function regenerateRecoveryCodes(): void
    {
        if (!$this->confirmingPassword) {
            $this->pendingAction = 'regenerate-codes';
            $this->startConfirmingPassword();
            return;
        }

        $user = Auth::user();
        if ($user instanceof User) {
            $user->generateRecoveryCodes();
            $this->recoveryCodes = $user->recoveryCodes();
        }

        $this->showingRecoveryCodes = true;
        Notification::make()
            ->success()
            ->title(__('Success'))
            ->body(__('Recovery codes have been regenerated.'))
            ->send();
    }

    public function confirmPassword(): void
    {
        $this->validate([
            'data.confirmablePassword' => 'required|string',
        ]);

        $this->confirmablePassword = $this->data['confirmablePassword'];

        if (!password_verify($this->confirmablePassword, Auth::user()->password)) {
            Notification::make()
                ->danger()
                ->title(__('Invalid password'))
                ->body(__('This password does not match our records.'))
                ->send();
            return;
        }

        if (session()->has('auth.password_confirmed_at')) {
            session()->put('auth.password_confirmed_at', time());
        }

        switch ($this->pendingAction) {
            case 'enable':
                $enable = app(EnableTwoFactorAuthentication::class);
                $user = Auth::user();
                $enable($user);
                if ($user instanceof User) {
                    $this->recoveryCodes = $user->recoveryCodes();
                }
                $this->showingQrCode = true;
                $this->showingConfirmation = true;
                $this->showingRecoveryCodes = true;
                break;
            case 'disable':
                $disable = app(DisableTwoFactorAuthentication::class);
                $disable(Auth::user());
                $this->showingQrCode = false;
                $this->showingRecoveryCodes = false;
                break;
            case 'show-codes':
                $this->showingRecoveryCodes = true;
                break;
            case 'regenerate-codes':
                $user = Auth::user();
                if ($user instanceof User) {
                    $user->generateRecoveryCodes();
                    $this->recoveryCodes = $user->recoveryCodes();
                }
                $this->showingRecoveryCodes = true;
                break;
        }

        $this->confirmingPassword = false;
        $this->confirmablePassword = null;
        $this->data['confirmablePassword'] = null;
        $this->pendingAction = null;
    }

    public function startConfirmingPassword(): void
    {
        $this->confirmingPassword = true;
    }

    public function stopConfirmingPassword(): void
    {
        $this->confirmingPassword = false;
        $this->confirmablePassword = null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check();
    }
}
