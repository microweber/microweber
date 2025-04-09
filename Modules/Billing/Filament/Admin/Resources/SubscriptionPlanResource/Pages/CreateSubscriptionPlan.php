<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource;

class CreateSubscriptionPlan extends CreateRecord
{
    protected static string $resource = SubscriptionPlanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
