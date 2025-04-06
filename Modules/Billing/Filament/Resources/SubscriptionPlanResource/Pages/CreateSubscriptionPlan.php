<?php

namespace Modules\Billing\Filament\Resources\SubscriptionPlanResource\Pages;

use Modules\Billing\Filament\Resources\SubscriptionPlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubscriptionPlan extends CreateRecord
{
    protected static string $resource = SubscriptionPlanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
