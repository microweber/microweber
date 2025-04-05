<?php

namespace Modules\Comments\Filament\Resources\CommentResource\Pages;

use Modules\Comments\Filament\Resources\CommentResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Actions;

class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('settings')
                ->label('Comments Settings')
                ->url(route('filament.admin.pages.comments-module-settings-admin'))
                ->icon('heroicon-o-cog-6-tooth'),
        ];
    }
}
