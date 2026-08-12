<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests\Fixtures;

use Illuminate\Contracts\View\View;
use MicroweberPackages\LivewireModal\ModalComponent;

class OptionsModal extends ModalComponent
{
    public string $title = 'Options';

    /** @var array<string, mixed> */
    public array $modalSettings = [
        'overlay' => true,
        'overlayClose' => true,
        'width' => '500px',
    ];

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public static function closeModalOnEscape(): bool
    {
        return false;
    }

    public static function showCloseButton(): bool
    {
        return false;
    }

    public static function showBackdrop(): bool
    {
        return false;
    }

    public function render(): View
    {
        return view('livewire-modal-tests::demo-modal');
    }
}
