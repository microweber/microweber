<?php

namespace Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource;

class ListProductPricingRules extends ListRecords
{
    protected static string $resource = ProductPricingRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Schemas\Components\Actions\CreateAction::make(),
        ];
    }
}
