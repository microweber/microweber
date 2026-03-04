<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset;
use Illuminate\Validation\ValidationException;
use Modules\Profile\Actions\ResetPasswordAction;

class ForgotPassword extends RequestPasswordReset
{
    public ?string $captcha = null;
    public $schema_id;

    public function mount(): void
    {
        parent::mount();
        $this->form_id = uniqid('forgot_');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->getEmailFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('Email'))
            ->email()
            ->required()
            ->maxLength(255);
    }

}
