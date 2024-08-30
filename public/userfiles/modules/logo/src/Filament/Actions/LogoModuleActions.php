<?php

namespace MicroweberPackages\Modules\Logo\Filament\Actions;

use App\Filament\Admin\Resources\ContentResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Livewire;
use MicroweberPackages\Modules\Logo\Http\Livewire\LogoModuleSettings;

class LogoModuleActions
{
    public static function testAction()
    {
        return Action::make('testAction')
            ->form(
                ContentResource::formArray([
                    'contentType' => 'page'
                ])
            )->slideOver();

    }

    public static function testAction2()
    {


        return Action::make('testAction')
            ->form(
                [

                    Livewire::make(LogoModuleSettings::class)
                ]
            )->slideOver();

    }
}
