<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests\Fixtures;

use Illuminate\Contracts\View\View;
use MicroweberPackages\LivewireModal\ModalComponent;

class DemoModal extends ModalComponent
{
    public string $title = 'Demo';

    public function render(): View
    {
        return view('livewire-modal-tests::demo-modal');
    }
}
