<?php

namespace Modules\Content\Filament\Admin\ContentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Modules\Content\Filament\Admin\ContentResource;

class ViewContent extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
