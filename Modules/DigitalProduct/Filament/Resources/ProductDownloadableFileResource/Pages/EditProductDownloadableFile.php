<?php

namespace Modules\DigitalProduct\Filament\Resources\ProductDownloadableFileResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\DigitalProduct\Filament\Resources\ProductDownloadableFileResource;

class EditProductDownloadableFile extends EditRecord
{
    protected static string $resource = ProductDownloadableFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
