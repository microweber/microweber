<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
use MicroweberPackages\User\Http\Requests\RegisterRequest;
use MicroweberPackages\User\UserManager;

class Register extends BaseRegister
{
    public ?string $captcha = null;
    public $form_id;

    public function mount(): void
    {
        parent::mount();
        $this->form_id = uniqid('register_');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getFirstNameFormComponent(),
                $this->getLastNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCaptchaFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getFirstNameFormComponent(): Component
    {
        return TextInput::make('first_name')
            ->label(__('First Name'))
            ->required()
            ->maxLength(255);
    }

    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('last_name')
            ->label(__('Last Name'))
            ->required()
            ->maxLength(255);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('Email'))
            ->email()
            ->required()
            ->maxLength(255)
            ->unique('users');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('Password'))
            ->password()
            ->required()
            ->minLength(1)
            ->same('passwordConfirmation');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('Password Confirmation'))
            ->password()
            ->required()
            ->minLength(1);
    }

    protected function getCaptchaFormComponent(): Component
    {
        return View::make('modules.profile::components.captcha')
            ->label('Security Code');
    }

    public function register(): ?RegistrationResponse
    {

        $userManager = app(UserManager::class);

        $data = [
            'first_name' => $this->data['first_name'],
            'last_name' => $this->data['last_name'],
            'email' => $this->data['email'],
            'password' => $this->data['password'],
            'password_confirmation' => $this->data['passwordConfirmation'],
            'captcha' => $this->captcha,
        ];
        $registerRequest = new RegisterRequest();

        $registerRequest->merge($data);
        $response = $userManager->register($data);

        if (isset($response['errors']) and !empty($response['errors'])) {

            //add .data to kte heys
            $messages = $response['errors'];
            $responseErrors = [];
            foreach ($messages as $key => $value) {
                if (is_array($value)) {
                    $value = implode(' ', $value);
                }
                $responseErrors['data.' . $key] = $value;
            }

            throw ValidationException::withMessages($responseErrors);

        } else if (isset($response['error'])) {

            throw ValidationException::withMessages([
                'data.email' => $response['error'],
            ]);
        }

        if (isset($response['id'])) {
            event(new Registered($response));

            // Login the user after registration
            $userManager->make_logged($response['id']);

            return $this->getRegistrationResponse();
        }

        throw ValidationException::withMessages([
            'data.email' => __('Registration failed. Please try again.'),
        ]);

    }
}
