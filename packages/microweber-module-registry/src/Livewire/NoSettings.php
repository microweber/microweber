<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Default Live Edit settings component when a module has no settings UI.
 *
 * Requires livewire/livewire (suggested dependency).
 */
class NoSettings extends Component
{
    public function render(): View
    {
        return view('module-registry::livewire.no-settings');
    }
}
