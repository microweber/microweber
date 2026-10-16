<?php

namespace Modules\DigitalProduct\Filament\Resources\ProductDownloadableFileResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\DigitalProduct\Filament\Resources\ProductDownloadableFileResource;

class ListProductDownloadableFiles extends ListRecords
{
    protected static string $resource = ProductDownloadableFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
