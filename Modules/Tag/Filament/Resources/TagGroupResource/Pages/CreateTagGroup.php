<?php

namespace Modules\Tag\Filament\Resources\TagGroupResource\Pages;

use Modules\Tag\Filament\Resources\TagGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTagGroup extends CreateRecord
{
    protected static string $resource = TagGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
