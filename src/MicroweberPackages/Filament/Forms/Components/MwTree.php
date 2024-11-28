<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Concerns\HasExtraFieldWrapperAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\KeyValue;
use Filament\Support\Concerns\HasExtraAttributes;

class MwTree extends Field
{
    use HasPlaceholder;
    use HasExtraFieldWrapperAttributes;

    protected string $view = 'filament-forms::components.mw-tree';

//    protected function setUp(): void
//    {
//        parent::setUp();
//
//     $this->columnSpan('full');
//
//
//    //    $this->hiddenLabel(true);
//    $this->inlineLabel(true);
//     }
}
