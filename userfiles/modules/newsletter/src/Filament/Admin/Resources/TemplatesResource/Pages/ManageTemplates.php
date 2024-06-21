<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;

class ManageTemplates extends ManageRecords
{
    protected static string $resource = TemplatesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
