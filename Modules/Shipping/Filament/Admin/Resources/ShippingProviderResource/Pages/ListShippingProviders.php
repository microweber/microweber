<?php

namespace Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages;

use Filament\Actions;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource;

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
        // task-2026-05-28-2f5a6c / AI-1106 — header CreateAction hides when
        // the table has zero rows so the prominent emptyState CTA in the
        // shared empty-state blade is the only primary affordance on the
        // empty page. When records exist, the empty state never renders
        // and the header CTA is the sole primary.
        return [
            Actions\CreateAction::make()
                ->visible(fn (): bool => static::getResource()::getEloquentQuery()->exists()),
        ];
    }
}
