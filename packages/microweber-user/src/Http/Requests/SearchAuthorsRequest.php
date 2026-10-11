<?php

declare(strict_types=1);

namespace MicroweberPackages\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchAuthorsRequest extends FormRequest
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
            'kw' => 'nullable|string|max:255',
            'limit' => 'nullable|integer|min:1|max:500',
        ];
    }
}
