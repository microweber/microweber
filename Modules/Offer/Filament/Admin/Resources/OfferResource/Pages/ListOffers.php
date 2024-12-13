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
        return [
            Actions\CreateAction::make(),
        ];
    }
}
