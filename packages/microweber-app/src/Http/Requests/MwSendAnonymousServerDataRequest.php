<?php

declare(strict_types=1);

namespace MicroweberPackages\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MwSendAnonymousServerDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return is_admin();
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'function_name' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:20',
            'data' => 'nullable',
        ];
    }
}
