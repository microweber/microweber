<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use Illuminate\Validation\ValidationException;
use MicroweberPackages\User\UserManager;

class ForgotPassword extends RequestPasswordReset
{
    public ?string $captcha = null;
    public $form_id;

    public function mount(): void
    {
        parent::mount();
        $this->form_id = uniqid('forgot_');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getCaptchaFormComponent(),
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

    protected function getCaptchaFormComponent(): Component
    {
        return View::make('modules.profile::components.captcha')
            ->label('Security Code');
    }

    public function request(): void
    {
        try {
            $userManager = app(UserManager::class);

            $data = [
                'email' => $this->data['email'],
                'captcha' => $this->captcha,
            ];

            $response = $userManager->send_forgot_password($data);

            if (isset($response['error'])) {
                throw ValidationException::withMessages([
                    'data.email' => $response['error'],
                ]);
            }

            $this->notify('success', __('We have emailed your password reset link.'));

            $this->redirect($this->getLoginUrl());
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}
