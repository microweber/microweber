<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource;

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
