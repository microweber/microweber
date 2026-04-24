<?php

namespace Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource;

class ListWordPressMigrations extends ListRecords
{
    protected static string $resource = WordPressMigrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('startImport')
                ->label('Start new import')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->url(fn (): string => '/admin/word-press-migration-import-page'),
        ];
    }
}
