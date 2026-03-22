<?php

namespace Modules\Media\Filament\Resources\MediaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Media\Filament\Resources\MediaResource;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('bulkUpload')
                ->label('Bulk Upload')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->url(route('media.bulk-upload')),
        ];
    }
}
