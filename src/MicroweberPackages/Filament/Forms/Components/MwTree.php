<?php

namespace MicroweberPackages\Filament\Forms\Components;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\KeyValue;

class MwTree extends Field
{
    use HasPlaceholder;



//    protected function setUp(): void
//    {
//
//
//
//        parent::setUp();
//
//    //    $this->default([]);
//
////        $this->dehydrateStateUsing(static function (?array $state) {
////
////            dd($state);
////            return collect($state ?? [])
////                ->filter(static fn (?string $value, ?string $key): bool => filled($key))
////                ->map(static fn (?string $value): ?string => filled($value) ? $value : null)
////                ->all();
////        });
//
//    }
    protected string $view = 'filament-forms::components.mw-tree';

}
