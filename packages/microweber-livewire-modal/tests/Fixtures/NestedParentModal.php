<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests\Fixtures;

use Illuminate\Contracts\View\View;
use MicroweberPackages\LivewireModal\ModalComponent;

class NestedParentModal extends ModalComponent
{
    public string $title = 'Parent Modal';

    public function openChild(): void
    {
        $this->openModal('nested-child-modal', ['title' => 'Child Modal']);
    }

    public function render(): View
    {
        return view('livewire-modal-tests::nested-parent-modal');
    }
}
