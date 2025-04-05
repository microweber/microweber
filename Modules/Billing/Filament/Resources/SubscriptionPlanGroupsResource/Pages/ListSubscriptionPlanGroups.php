<?php

namespace Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource\Pages;

use Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
