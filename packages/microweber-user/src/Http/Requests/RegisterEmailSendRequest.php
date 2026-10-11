<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterEmailSendRequest extends FormRequest
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
            'user_id' => 'nullable|integer|min:1',
        ];
    }
}
