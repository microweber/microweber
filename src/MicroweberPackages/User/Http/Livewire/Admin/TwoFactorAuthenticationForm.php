<?php

namespace MicroweberPackages\User\Http\Livewire\Admin;

/**
 * @deprecated Use \MicroweberPackages\Fortify\Http\Livewire\TwoFactorSetupComponent instead.
 * This class extends the package component for backward compatibility.
 */
class TwoFactorAuthenticationForm extends \MicroweberPackages\Fortify\Http\Livewire\TwoFactorSetupComponent
{
    public function render()
    {
        return view('admin::livewire.profile.two-factor-authentication-form');
    }
}
