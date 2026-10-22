<?php

namespace MicroweberPackages\User\Http\Livewire;

/**
 * @deprecated Use \MicroweberPackages\Fortify\Http\Livewire\TwoFactorSetupComponent instead.
 * This class extends the package component for backward compatibility.
 */
class TwoFactorAuthenticationForm extends \MicroweberPackages\Fortify\Http\Livewire\TwoFactorSetupComponent
{
    public $showForm = false;

    public function toggleForm()
    {
        // Method name must differ from the public $showForm property, or
        // Livewire resolves `$wire.showForm` to the boolean and the button
        // throws "$wire.showForm is not a function".
        $this->showForm = !$this->showForm;
    }

    public function render()
    {
        return view('user::livewire.profile.two-factor-authentication-form');
    }
}
