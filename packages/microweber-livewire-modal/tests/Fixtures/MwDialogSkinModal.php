<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests\Fixtures;

use Illuminate\Contracts\View\View;
use MicroweberPackages\LivewireModal\ModalComponent;

class MwDialogSkinModal extends ModalComponent
{
    public string $title = 'Mw Dialog';

    /**
     * @var array<string, mixed>
     */
    public array $modalSettings = [
        'title' => 'Mw Dialog',
        'overlay' => true,
        'overlayClose' => false,
        'closeButton' => true,
        'autoHeight' => true,
        'autosize' => true,
        'autoScroll' => true,
        'draggable' => true,
        'width' => 640,
    ];

    public static function modalSkin(): string
    {
        return 'mw-dialog';
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
