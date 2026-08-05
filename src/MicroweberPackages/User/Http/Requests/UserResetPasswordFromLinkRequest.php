<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserResetPasswordFromLinkRequest extends FormRequest
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
            'password' => 'nullable|string|min:1|max:500',
            'password2' => 'nullable|string|min:1|max:500',
            'password_reset_token' => 'nullable|string|max:500',
            'id' => 'nullable|integer',
            'email' => 'nullable|email|max:255',
        ];
    }
}
