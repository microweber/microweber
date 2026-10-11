<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSendForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'email' => 'nullable|email|max:255',
            'username' => 'nullable|string|max:255',
            'captcha' => 'nullable|string|max:100',
        ];
    }
}
