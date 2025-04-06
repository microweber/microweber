<?php

namespace Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource\Pages;

use Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubscriptionPlanGroups extends CreateRecord
{
    protected static string $resource = SubscriptionPlanGroupsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
