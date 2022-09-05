<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Admin\AdminDataTableComponent;
use MicroweberPackages\Livewire\Views\Columns\HtmlColumn;
use MicroweberPackages\Livewire\Views\Columns\MwCardColumn;
use MicroweberPackages\Livewire\Views\Columns\MwCardTitleCategoriesButtonsColumn;
use MicroweberPackages\Livewire\Views\Filters\PriceRangeFilter;
use MicroweberPackages\Product\Models\Product;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
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
            ->setReorderEnabled()
            ->setSortingEnabled()
            ->setSearchEnabled()
            ->setDefaultReorderSort('position', 'asc')
            ->setReorderMethod('changePosition')
            ->setFilterLayoutSlideDown()
            ->setColumnSelectDisabled()
            ->setUseHeaderAsFooterEnabled()
            ->setBulkActionsEnabled()
            ->setHideBulkActionsWhenEmptyEnabled();

       /* $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
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
            if ($column->getTitle() == 'Sales') {
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
                    'class' => 'col-lg-5 col item-title',
                ];
            }
            return [
                'class' => 'col',
            ];
        });*/
    }

    public function columns(): array
    {
        return [
             ImageColumn::make('Image')
                 ->location(function($row) {
                     return $row->thumbnail();
                 })
                 ->attributes(function($row) {
                     return [
                         'class' => 'w-8 h-8 rounded-full',
                     ];
                 }),
          /* MwCardImageColumn::make('Image','image')
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
                }),*/

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

            HtmlColumn::make('Sales','content.sales')
            ->setOutputHtml(function($row) {
                $sales = '<span class="badge badge-danger badge-sm">0 sales</span>';
                return $sales;
            }),

            HtmlColumn::make('Quantity','content.price')
            ->setOutputHtml(function($row) {
                if ($row->qty == 'nolimit') {
                    $quantity = '<i class="fa fa-infinity" title="Unlimited Quantity"></i>';
                } else if ($row->qty == 0) {
                    $quantity = '<span class="text-small text-danger">0</span>';
                } else {
                    $quantity = $row->qty;
                }
                return $quantity;
            }),

          /*  HtmlColumn::make('Sku','content.sku')
            ->setOutputHtml(function($row) {
                if ($row->qty == 'nolimit') {
                    $quantity = '<i class="fa fa-infinity" title="Unlimited Quantity"></i> Unlimited';
                } else if ($row->qty == 0) {
                    $quantity = '<span class="text-small text-danger">No quantity</span>';
                } else {
                    $quantity = 'Quantity: ' . $row->qty;
                }
                if (!empty($row->sku)) {
                    $quantity .= '<br /><span class="text-small text-primary">SKU: ' . $row->sku.'</span>';
                }
                return $quantity;
            }),*/

           /* HtmlColumn::make('Author','content.created_by')
                ->setOutputHtml(function($row) {
                    return '<span class="text-muted">'.ucfirst($row->authorName()).'</span>';
                }),*/

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

        $filters = [];

        $priceRange = $this->getAppliedFilterWithValue('price_range');
        if ($priceRange) {
            if (strpos($priceRange, ',') !== false) {
                $filters['priceBetween'] = $priceRange;
            }
        }

        $quantity = $this->getAppliedFilterWithValue('quantity');
        if ($quantity) {
            $filters['qty'] = $quantity;
        }

        $status = $this->getAppliedFilterWithValue('status');
        if ($status == 'in_stock') {
            $filters['inStock'] = true;
        }
        if ($status == 'out_of_stock') {
            $filters['inStock'] = false;
        }

        $sku = $this->getAppliedFilterWithValue('s_k_u');
        if (!empty($sku)) {
            $filters['contentData']['sku'] = $sku;
        }

        $type = $this->getAppliedFilterWithValue('type');
        if ($type == 'discounted') {
            $filters['discounted'] = true;
        }
        if ($type == 'not_discounted') {
            $filters['not_discounted'] = true;
        }

        if ($this->hasSearch()) {
            $search = $this->getSearch();
            $filters['title'] = $search;
        }

        $query->filter($filters);

        return $query;
    }

    public function filters(): array
    {
        return [

            PriceRangeFilter::make('Price range')
             ->config([
                 'placeholder' => 'Select price range',
             ])->filter(function(Builder $builder, string $value) {

             }),

            SelectFilter::make('Status')
                ->setFilterPillTitle('Status')
                ->options([
                    '' => 'Any',
                    'in_stock' => 'In Stock',
                    'out_of_stock' => 'Out Of Stock',
                ])
                ->filter(function(Builder $builder, string $value) {


                }),

            SelectFilter::make('Type')
                ->setFilterPillTitle('Type')
                ->options([
                    '' => 'Any',
                    'discounted' => 'Discounted',
                    'not_discounted' => 'Not discounted',
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
                    'published' => 'Published',
                    'unpublished' => 'Unpublished',
                ])
                ->filter(function(Builder $builder, string $value) {
                    if ($value === 'published') {
                        $builder->where('is_active', 1);
                    } elseif ($value === 'unpublished') {
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

    public function bulkActions(): array
    {
        $bulkActions = [
            'multipleProductPublish(1)' => 'Publish',
            'multipleProductPublish(0)' => 'Unpublish',
        ];

        $bulkActions['multipleProductDelete'] = 'Delete';

        return $bulkActions;
    }
}

