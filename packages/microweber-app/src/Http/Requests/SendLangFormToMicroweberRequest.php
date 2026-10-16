<?php

declare(strict_types=1);

namespace MicroweberPackages\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendLangFormToMicroweberRequest extends FormRequest
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
            'translations' => 'nullable|array',
            'language' => 'nullable|string|max:20',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null): mixed
    {
        $validated = parent::validated($key, $default);
        if ($key !== null) {
            return $validated;
        }

        return array_merge($this->all(), is_array($validated) ? $validated : []);
    }
}
