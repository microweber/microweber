<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Admin\AdminDataTableComponent;
use MicroweberPackages\Admin\View\Columns\MwCardColumn;
use MicroweberPackages\Admin\View\Columns\ImageWithLinkColumn;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ProductsTable extends AdminDataTableComponent
{
    protected $model = Product::class;
    public array $perPageAccepted = [1, 25, 50, 100, 200];

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
            MwCardColumn::make('Card', 'id')
                ->buttons(function ($row) {
                    return [
                         [
                            'name'=>'Edit',
                            'class'=>'btn btn-outline-primary btn-sm',
                            'href'=>route('admin.page.edit', $row->id),
                         ],
                        [
                            'name'=>'Live edit',
                            'class'=>'btn btn-outline-success btn-sm',
                            'href'=>route('admin.page.edit', $row->id),
                         ],
                        [
                            'name'=>'Delete',
                            'class'=>'btn btn-outline-danger btn-sm',
                            'href'=>route('admin.page.edit', $row->id),
                         ],
                    ];
                })
                ->attributes(function($row) {
                return [''];
            })->sortable(),
        ];
    }

    public function changePosition($items): void
    {
        foreach ($items as $item) {
            Product::find((int)$item['value'])->update(['position' => (int)$item['order']]);
        }
    }

    public function builder(): Builder
    {
        $query = Product::query();
        $query->select(['content.id','content.title','content.url','content.position','content.created_by']);
        $query->orderBy('position','asc');

        if ($this->hasSearch()) {
            $search = $this->getSearch();
            $search = trim(strtolower($search));

            $query->where(function (Builder $subQuery) use ($search) {
                $subQuery->whereRaw('LOWER(`title`) LIKE ? ', ['%' . $search . '%']);
                $subQuery->orWhereRaw('LOWER(`url`) LIKE ? ', ['%' . $search . '%']);
            });
        }

        return $query;
    }

    public function filters(): array
    {
        return [
            /* TextFilter::make('Package Name')
                 ->config([
                     'maxlength' => 5,
                     'placeholder' => 'Search Package Name',
                 ])
                 ->filter(function(Builder $builder, string $value) {
                     $builder->where('package.name', 'like', '%'.$value.'%');
                 }),*/

            SelectFilter::make('Visible')
                ->setFilterPillTitle('Visible')
                ->options([
                    '' => 'Any',
                    '1' => 'Published',
                    '0' => 'Hidden',
                ])
                ->filter(function(Builder $builder, string $value) {
                    if ($value === '1') {
                        $builder->where('is_active', 1);
                    } elseif ($value === '0') {
                        $builder->where('is_active', 0);
                    }
                }),

            DateFilter::make('Updated at')
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('updated_at', '>=', $value);
                })
        ];
    }

}

