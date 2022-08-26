<?php

namespace MicroweberPackages\Page\Http\Livewire\Admin;

use MicroweberPackages\Page\Models\Page;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PagesTable extends DataTableComponent
{
    protected $model = Page::class;
    public array $perPageAccepted = [10, 25, 50, 100, 200];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            //->setDebugEnabled()
            ->setReorderEnabled()
            ->setSortingEnabled()
            ->setSearchEnabled()
            ->setSearchDebounce(0)
            ->setDefaultReorderSort('position', 'asc')
            ->setReorderMethod('changePosition')
            ->setFilterLayoutSlideDown()
            ->setRememberColumnSelectionDisabled()
            ->setUseHeaderAsFooterEnabled()
            ->setHideBulkActionsWhenEmptyEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Title')->sortable(),
            Column::make('Url')->sortable(),
            Column::make('Position','position')->sortable(),
        ];
    }

    public function changePosition($items): void
    {
        foreach ($items as $item) {
            Page::find((int)$item['value'])->update(['position' => (int)$item['order']]);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->setupColumnSelect();
        $this->setupPagination();
        $this->setupSecondaryHeader();
        $this->setupFooter();
        $this->setupReordering();

        return view('page::livewire.admin.livewire-tables.datatable')
            ->with([
                'columns' => $this->getColumns(),
                'rows' => $this->getRows(),
                'customView' => $this->customView(),
            ]);
    }
}

