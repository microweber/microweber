<?php

namespace MicroweberPackages\Admin\Filament\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;

/*
 * Mobile-audit a11y override for /admin/login (AI-281).
 *
 * The Filament base Login renders <input> elements with a visible <label>
 * and the right `id`/`for` pairing, but does NOT emit a `placeholder` hint
 * or a `name` attribute. On mobile (390×844) that meant:
 *   - empty input fields gave no inline cue what to type
 *   - password managers and browser autofill couldn't detect the fields
 *     reliably without a `name`
 *
 * Livewire submits via wire:model so the `name` attribute is non-functional
 * for form posting — but it's still the primary hint used by 1Password,
 * Bitwarden, Apple Keychain, Chrome autofill, and most accessibility tools.
 * Adding it is purely additive.
 */
class Login extends BaseLogin
{
    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->placeholder(__('you@example.com'))
            ->extraInputAttributes(['name' => 'email']);
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->placeholder(__('Your password'))
            ->extraInputAttributes(['name' => 'password']);
    }
}
