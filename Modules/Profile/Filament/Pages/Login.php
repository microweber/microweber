<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use MicroweberPackages\User\UserManager;

class Login extends BaseLogin
{
    public ?string $captcha = null;
    public $form_id;

    public function mount(): void
    {
        parent::mount();
        $this->form_id = uniqid('login_');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getCaptchaFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('Email'))
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('Password'))
            ->password()
            ->required();
    }

    protected function getCaptchaFormComponent(): Component
    {
        return View::make('modules.profile::components.captcha')
            ->label('Security Code');
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $userManager = app(UserManager::class);
            $data = [
                'email' => $this->data['email'],
                'password' => $this->data['password'],
                'captcha' => $this->captcha,
            ];
            
            $response = $userManager->login($data);
            
            if (isset($response['error'])) {
                throw ValidationException::withMessages([
                    'data.email' => $response['error'],
                ]);
            }

            return $this->getLoginResponse();
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}
