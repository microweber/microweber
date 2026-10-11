<?php

namespace MicroweberPackages\Filament\Actions;

use MicroweberPackages\Filament\Concerns\Actions\HasOnlyIcon;

class DeleteActionOnlyIcon extends \Filament\Actions\DeleteAction
{
    use HasOnlyIcon;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extraAttributes([
            'data-mw-style' => 'icon-only',

        ]);

    }

}
