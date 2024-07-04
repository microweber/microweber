<?php

namespace App\Filament\Admin\Resources\ContentResource\Pages;

use App\Filament\Admin\Resources\ContentResource;
use App\Filament\Admin\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Hydrat\TableLayoutToggle\Concerns\HasToggleableTable;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use function Filament\Support\generate_search_column_expression;

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
            Actions\CreateAction::make(),
          //  Actions\LocaleSwitcher::make(),
        ];
    }


}
