<?php

namespace MicroweberPackages\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Shipping\Filament\Admin\Resources\ShippingProviderResource;

class ListShippingProviders extends ListRecords
{
    protected static string $resource = ShippingProviderResource::class;
    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'active' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),
            'inactive' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
