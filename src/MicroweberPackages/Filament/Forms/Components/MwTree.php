<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\KeyValue;

class MwTree extends Field
{
    use HasPlaceholder;

    protected string $view = 'filament-forms::components.mw-tree';

    protected function setUp(): void
    {
        parent::setUp();

    //    $this->columnSpan('full');
    //    $this->hiddenLabel(true);
     }
}
