<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource;

class ListSubscriptionPlanGroups extends ListRecords
{
    protected static string $resource = SubscriptionPlanGroupsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
