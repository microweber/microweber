<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Billing\Filament\Admin\Resources\SubscriptionResource;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - subscriptions are created via Stripe checkout
        ];
    }
}
