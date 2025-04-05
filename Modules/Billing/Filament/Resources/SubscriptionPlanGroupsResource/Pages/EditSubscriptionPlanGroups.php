<?php

namespace Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource\Pages;

use Modules\Billing\Filament\Resources\SubscriptionPlanGroupsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubscriptionPlanGroups extends EditRecord
{
    protected static string $resource = SubscriptionPlanGroupsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
