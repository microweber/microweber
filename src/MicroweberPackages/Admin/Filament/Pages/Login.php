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
 *
 * NOVICE #10 (task-2026-05-13-899d57) — extended with the standard
 * WHATWG `autocomplete` tokens. `name="email"` alone helps SOME password
 * managers, but `autocomplete="username"` + `autocomplete="current-password"`
 * are what the spec actually requires for autofill, and what iOS Keychain
 * / Android Autofill / Chrome / Edge / Safari all look for. Without
 * these tokens, autofill silently does nothing — the novice persona
 * reported lockouts from typo'd manual password entry after autofill
 * failed to surface.
 */
class Login extends BaseLogin
{
    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->placeholder(__('you@example.com'))
            ->extraInputAttributes([
                'name' => 'email',
                'autocomplete' => 'username',
                'inputmode' => 'email',
            ]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->placeholder(__('Your password'))
            ->extraInputAttributes([
                'name' => 'password',
                'autocomplete' => 'current-password',
            ]);
    }
}
