<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Admin\AdminDataTableComponent;
use MicroweberPackages\Livewire\Views\Columns\HtmlColumn;
use MicroweberPackages\Livewire\Views\Columns\MwCardColumn;
use MicroweberPackages\Livewire\Views\Columns\MwCardImageColumn;
use MicroweberPackages\Livewire\Views\Columns\MwCardTitleCategoriesButtonsColumn;
use MicroweberPackages\Livewire\Views\Filters\PriceRangeFilter;
use MicroweberPackages\Product\Models\Product;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumberFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;

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
            ->setColumnSelectDisabled()
            ->setUseHeaderAsFooterEnabled()
            ->setHideBulkActionsWhenEmptyEnabled();

        $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->getTitle() == 'Author') {
                return [
                    'class' => 'col-lg-1 col d-xl-block d-none',
                ];
            }
            if ($column->getTitle() == 'Price') {
                return [
                    'class' => 'col-lg-2 col text-center',
                ];
            }
            if ($column->getTitle() == 'Stock') {
                return [
                    'class' => 'col-lg-1 col text-center',
                ];
            }
            if ($column->getTitle() == 'Inventory') {
                return [
                    'class' => 'col-lg-2 col text-center',
                ];
            }
            if ($column->getTitle() == 'Image') {
                return [
                    'class' => 'col-lg-2 col item-image',
                ];
            }
            if ($column->getTitle() == 'Title') {
                return [
                    'class' => 'col-lg-6 col item-title',
                ];
            }
            return [
                'class' => 'col',
            ];
        });
    }

    public function columns(): array
    {
        return [

           MwCardImageColumn::make('Image','image')
                ->location(function($row) {
                    $img = false;
                    if ($row->media()->first() !== null) {
                        $img = $row->thumbnail();
                    }
                    return [
                        'target'=>'_blank',
                        'href'=> '',
                        'location'=> $img
                    ];
                }),

            MwCardTitleCategoriesButtonsColumn::make('Title')
                ->buttons(function ($row) {
                $buttons = [
                    [
                        'name'=>'Edit',
                        'class'=>'btn btn-outline-primary btn-sm',
                        'href'=>route('admin.product.edit', $row->id),
                    ],
                    [
                        'name'=>'Live edit',
                        'class'=>'btn btn-outline-success btn-sm',
                        'href'=>route('admin.product.edit', $row->id),
                    ],
                    [
                        'name'=>'Delete',
                        'class'=>'btn btn-outline-danger btn-sm',
                        'href'=>route('admin.product.edit', $row->id),
                    ],
                ];

                if ($row->is_active < 1) {
                    $buttons[] = [
                        'name'=>'Unpublished',
                        'class'=>'badge badge-warning font-weight-normal',
                        'href'=> "",
                    ];
                }

                return $buttons;
            }),

            HtmlColumn::make('Price','content.price')
            ->setOutputHtml(function($row) {
                if ($row->hasSpecialPrice()) {
                    $price = '<span class="h6" style="text-decoration: line-through;">'.currency_format($row->price).'</span>';
                    $price .= '<br /><span class="h5">'.currency_format($row->specialPrice).'</span>';
                } else {
                    $price = '<span class="h5">'.currency_format($row->price).'</span>';
                }
                return $price;
            }),

            HtmlColumn::make('Stock','content.InStock')
            ->setOutputHtml(function($row) {
                if ($row->InStock) {
                    $stock = '<span class="badge badge-success badge-sm">In stock</span>';
                } else {
                    $stock = '<span class="badge badge-danger badge-sm">Out Of Stock</span>';
                }
                return $stock;
            }),

            HtmlColumn::make('Inventory','content.price')
            ->setOutputHtml(function($row) {
                if ($row->qty == 'nolimit') {
                    $quantity = '<i class="fa fa-infinity" title="Unlimited Quantity"></i>';
                } else if ($row->qty == 0) {
                    $quantity = '<span class="text-small text-danger">No quantity</span>';
                } else {
                    $quantity = 'Quantity: ' . $row->qty;
                }
                if (!empty($row->sku)) {
                    $quantity .= '<br /><span class="text-small text-primary">SKU: ' . $row->sku.'</span>';
                }
                return $quantity;
            }),

            HtmlColumn::make('Author','content.created_by')
                ->setOutputHtml(function($row) {
                    return '<span class="text-muted">'.ucfirst($row->authorName()).'</span>';
                }),

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
        $query->select(['content.id','content.is_active','content.title','content.url','content.position','content.created_by']);
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

         /*   PriceRangeFilter::make('Price range')
             ->config([
                 'placeholder' => 'Select price range',
             ])
             ->filter(function(Builder $builder, string $value) {
                 $builder->where('id', 'like', '%'.$value.'%');
             }),*/

            SelectFilter::make('Status')
                ->setFilterPillTitle('Status')
                ->options([
                    '' => 'Any',
                    '1' => 'InStock',
                    '0' => 'OutOfStock',
                ])
                ->filter(function(Builder $builder, string $value) {


                }),

            SelectFilter::make('Type')
                ->setFilterPillTitle('Type')
                ->options([
                    '' => 'Any',
                    '1' => 'Discounted',
                    '2' => 'Not discounted',
                ])
                ->filter(function(Builder $builder, string $value) {


                }),

            NumberFilter::make('Sales')
                ->filter(function(Builder $builder, string $value) {
                    //
                }),

            NumberFilter::make('Quantity')
                ->filter(function(Builder $builder, string $value) {
                    //
                }),

            TextFilter::make('SKU')
                ->filter(function(Builder $builder, string $value) {
                    //
                }),

            SelectFilter::make('Visible')
                ->setFilterPillTitle('Visible')
                ->options([
                    '' => 'Any',
                    '1' => 'Published',
                    '0' => 'Unpublished',
                ])
                ->filter(function(Builder $builder, string $value) {
                    if ($value === '1') {
                        $builder->where('is_active', 1);
                    } elseif ($value === '0') {
                        $builder->where('is_active', 0);
                    }
                }),

            DateFilter::make('Created at')
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('created_at', '>=', $value);
                }),

            DateFilter::make('Updated at')
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('updated_at', '>=', $value);
                })
        ];
    }

}

