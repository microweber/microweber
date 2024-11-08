<?php

namespace Modules\Content\Filament\Admin\ContentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use Modules\Content\Filament\Admin\ContentResource;

class ListContents extends ListRecords
{
    protected static string $resource = ContentResource::class;

    use HasToggleableTable;
    use ListRecords\Concerns\Translatable;


    public function getDefaultLayoutView(): string
    {
        return 'grid';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->size('xl')
                ->icon('heroicon-o-plus')
                ->color('mw-secondary')
          //  Actions\LocaleSwitcher::make(),
        ];
    }


}
