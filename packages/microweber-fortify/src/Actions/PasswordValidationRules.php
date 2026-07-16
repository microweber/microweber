<?php

namespace MicroweberPackages\Fortify\Actions;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * @return array<int, mixed>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', new Password, 'confirmed'];
    }
}