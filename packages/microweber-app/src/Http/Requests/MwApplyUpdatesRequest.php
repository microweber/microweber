<?php

declare(strict_types=1);

namespace MicroweberPackages\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MwApplyUpdatesRequest extends FormRequest
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
            'confirm_key' => 'nullable|string|max:500',
        ];
    }
}
