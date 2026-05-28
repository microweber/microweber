<?php

namespace Modules\Offer\Filament\Admin\Resources\OfferResource\Pages;

use Modules\Offer\Filament\Admin\Resources\OfferResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOffers extends ListRecords
{
    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        // task-2026-05-28-2f5a6c / AI-1106 — header CreateAction hides when
        // the table has zero rows so the prominent emptyState CTA in
        // OfferResource::table() is the only primary affordance on the
        // empty page. When records exist, the empty state never renders
        // and the header CTA is the sole primary.
        return [
            Actions\CreateAction::make()
                ->visible(fn (): bool => static::getResource()::getEloquentQuery()->exists()),
        ];
    }
}
