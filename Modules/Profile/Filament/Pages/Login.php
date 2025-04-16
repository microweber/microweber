<?php

namespace Modules\Profile\Filament\Pages;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

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
                $this->getRememberFormComponent(),
            ])->statePath('data');

    }




    public function authenticate(): ?LoginResponse
    {

        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }


        try {
            $data = [
                'email' => $this->data['email'],
                'password' => $this->data['password'],
            ];

            $response = app()->user_manager->login($data);


            if (isset($response['error']) and is_string($response['error'])) {
                throw ValidationException::withMessages([
                    'data.email' => $response['error'],
                ]);
            }
            if (isset($response['success']) and ($response['success'])) {

                $user = auth()->user();
                if ($user) {

                    return app(LoginResponse::class);

                } else {
                    throw ValidationException::withMessages([
                        'data.email' => 'Invalid email or password.',
                    ]);
                }


            }

        } catch (ValidationException $e) {
            throw $e;
        }
    }
}
