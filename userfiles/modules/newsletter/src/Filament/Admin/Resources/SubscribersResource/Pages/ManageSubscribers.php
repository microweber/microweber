<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;

class ManageSubscribers extends ManageRecords
{
    protected static string $resource = SubscribersResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
