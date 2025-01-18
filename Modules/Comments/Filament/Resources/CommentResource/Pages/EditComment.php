<?php

namespace Modules\Comments\Filament\Resources\CommentResource\Pages;

use Modules\Comments\Filament\Resources\CommentResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditComment extends EditRecord
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
