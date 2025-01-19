<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use MicroweberPackages\User\ConfirmsPasswords;

class TwoFactorAuth extends Page
{
    use ConfirmsPasswords;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static string $view = 'modules.profile::pages.two-factor-auth';
    public ?array $data = [];
    public $showingQrCode = false;
    public $showingRecoveryCodes = false;
    public $showingConfirmation = false;
    public $code;

    public function mount(): void
    {
        if (Auth::user()->two_factor_secret &&
            ! Auth::user()->two_factor_confirmed_at) {
            $this->showingQrCode = true;
            $this->showingConfirmation = true;
        }
    }

    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable): void
    {
        $this->ensurePasswordIsConfirmed();

        $enable(Auth::user());

        $this->showingQrCode = true;
        $this->showingConfirmation = true;
    }

    public function confirmTwoFactorAuthentication(ConfirmTwoFactorAuthentication $confirm): void
    {
        if (! $this->code) {
            $this->notify('danger', __('Please enter the code.'));
            return;
        }

        $confirm(Auth::user(), $this->code);

        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->notify('success', __('Two factor authentication has been enabled.'));
    }

    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable): void
    {
        $this->ensurePasswordIsConfirmed();

        $disable(Auth::user());

        $this->showingQrCode = false;
        $this->showingRecoveryCodes = false;
        $this->notify('success', __('Two factor authentication has been disabled.'));
    }

    public function showRecoveryCodes(): void
    {
        $this->ensurePasswordIsConfirmed();

        $this->showingRecoveryCodes = true;
    }

    public function regenerateRecoveryCodes(): void
    {
        $this->ensurePasswordIsConfirmed();

        Auth::user()->generateRecoveryCodes();

        $this->showingRecoveryCodes = true;
        $this->notify('success', __('Recovery codes have been regenerated.'));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getConfirmationCodeComponent(),
            ])
            ->statePath('data');
    }

    protected function getConfirmationCodeComponent(): Component
    {
        return TextInput::make('code')
            ->label(__('Confirmation Code'))
            ->placeholder(__('Enter the code from your authenticator app'))
            ->wire('model', 'code')
            ->required()
            ->visible(fn() => $this->showingConfirmation);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }
}
