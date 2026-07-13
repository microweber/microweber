<?php

namespace MicroweberPackages\Fortify\Http\Livewire;

use Livewire\Component;

class TwoFactorChallengeComponent extends Component
{
    public bool $recovery = false;
    public ?string $code = null;
    public ?string $recovery_code = null;

    public function toggleRecovery(): void
    {
        $this->recovery = !$this->recovery;
    }

    public function render()
    {
        return view('microweber-fortify::livewire.two-factor-challenge');
    }
}