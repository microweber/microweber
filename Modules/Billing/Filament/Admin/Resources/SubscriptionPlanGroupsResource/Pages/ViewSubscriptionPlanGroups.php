<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource;

class ViewSubscriptionPlanGroups extends ViewRecord
{
    protected static string $resource = SubscriptionPlanGroupsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
