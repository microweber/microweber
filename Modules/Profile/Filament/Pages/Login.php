<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Arr;
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
            ])  ->statePath('data');

    }


    protected function getCaptchaFormComponent(): Component
    {
        return View::make('modules.profile::components.captcha')
            ->label('Security Code');
    }

    public function authenticate(): ?LoginResponse
    {
        try {
             $data = [
                'email' => $this->data['email'],
                'password' =>  $this->data['password'],
                'captcha' => $this->captcha,
            ];

            $response = app()->user_manager->login($data);

            dd($response);

            $flatArr = Arr::flatten($response['errors']);


            if (isset($response['errors'])) {
                dd($response['errors']);
               $errors = [];
                foreach ($flatArr as $key =>$error) {
                    $errors[$key] = $error;
                }
                if($errors) {
                    throw ValidationException::withMessages($errors);
                }
            }

            return $this->getLoginResponse();
        } catch (ValidationException $e) {
            throw $e;
        }
    }
}
