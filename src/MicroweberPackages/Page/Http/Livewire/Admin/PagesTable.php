<?php

namespace MicroweberPackages\Page\Http\Livewire\Admin;

use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Admin\AdminDataTableComponent;
use MicroweberPackages\Admin\View\Columns\ImageWithLinkColumn;
use MicroweberPackages\Page\Models\Page;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PagesTable extends AdminDataTableComponent
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

           ImageWithLinkColumn::make('Image','Image')
            ->location(function($row) {
                return [
                    'target'=>'_blank',
                    'href'=> route('admin.page.edit', $row->id),
                    'location'=>  $row->thumbnail()
                ];
            }),

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

    public function builder(): Builder
    {
        $query = Page::query();
        $query->select(['content.id','content.title','content.url','content.position','content.created_by']);
        $query->orderBy('position','asc');

        return $query;
    }
}

