<?php

namespace Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource;

class EditSubscriptionPlan extends EditRecord
{
    protected static string $resource = SubscriptionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
