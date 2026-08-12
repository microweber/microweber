<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests\Fixtures;

use Illuminate\Contracts\View\View;
use MicroweberPackages\LivewireModal\ModalComponent;

class NestedChildModal extends ModalComponent
{
    public string $title = 'Child Modal';

    public function render(): View
    {
        return view('livewire-modal-tests::nested-child-modal');
    }
}
