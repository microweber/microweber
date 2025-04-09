<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource;

class CreateSubscriptionPlanGroups extends CreateRecord
{
    protected static string $resource = SubscriptionPlanGroupsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
