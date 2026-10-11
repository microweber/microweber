<?php

declare(strict_types=1);

namespace MicroweberPackages\Module\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteModuleAsTemplateRequest extends FormRequest
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
            'id' => 'nullable|integer|min:1',
            'ids' => 'nullable|array',
            'ids.*' => 'integer|min:1',
        ];
    }
}
