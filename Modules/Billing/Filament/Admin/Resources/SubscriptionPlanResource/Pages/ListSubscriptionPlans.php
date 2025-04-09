<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource;

class ListSubscriptionPlans extends ListRecords
{
    protected static string $resource = SubscriptionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
