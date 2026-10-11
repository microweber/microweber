<?php

declare(strict_types=1);

namespace MicroweberPackages\Module\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveModuleAsTemplateRequest extends FormRequest
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
            'id' => 'nullable|integer',
            'name' => 'nullable|string|max:500',
            'module' => 'nullable|string|max:500',
            'module_attrs' => 'nullable',
            'position' => 'nullable|integer',
            'category' => 'nullable|string|max:255',
        ];
    }

    /**
     * Pass through all input for the legacy manager which expects a free-form array.
     *
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): mixed
    {
        // Legacy save accepts arbitrary module template columns; merge validated with all input keys.
        $validated = parent::validated($key, $default);
        if ($key !== null) {
            return $validated;
        }

        return array_merge($this->all(), is_array($validated) ? $validated : []);
    }
}
