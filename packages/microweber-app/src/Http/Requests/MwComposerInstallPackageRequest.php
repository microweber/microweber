<?php

declare(strict_types=1);

namespace MicroweberPackages\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MwComposerInstallPackageRequest extends FormRequest
{
    /**
     * Allowed during install (not yet installed) or when admin is logged in.
     */
    public function authorize(): bool
    {
        if (!mw_is_installed()) {
            return true;
        }

        return is_admin();
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'require_name' => 'nullable|string|max:500',
            'namespace' => 'nullable|string|max:500',
            'confirm_key' => 'nullable|string|max:500',
        ];
    }
}
