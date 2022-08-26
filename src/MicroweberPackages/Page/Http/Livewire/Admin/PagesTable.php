<?php

namespace MicroweberPackages\Page\Http\Livewire\Admin;

use MicroweberPackages\Page\Models\Page;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PagesTable extends DataTableComponent
{
    protected $model = Page::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),
            Column::make('Title')
                ->sortable(),
        ];
    }
}

