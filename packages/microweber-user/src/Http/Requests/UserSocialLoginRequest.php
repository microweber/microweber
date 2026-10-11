<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSocialLoginRequest extends FormRequest
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
            'provider' => 'nullable|string|max:100',
            'token' => 'nullable|string|max:5000',
        ];
    }
}
