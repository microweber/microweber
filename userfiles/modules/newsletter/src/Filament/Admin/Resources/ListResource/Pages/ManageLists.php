<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\ListResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\ListResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;

class ManageLists extends ManageRecords
{
    protected static string $resource = ListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
