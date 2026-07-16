<?php

namespace MicroweberPackages\Fortify\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * @param array<string, mixed> $input
     */
    public function create(array $input)
    {
        $userModel = config('microweber-fortify.user_model', config('auth.providers.users.model'));

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique($userModel),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return $userModel::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}