<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;

class ProductsIndexComponent extends Component
{
    use WithPagination;
    public $paginate = 2;

    public $filters = [];
    protected $listeners = ['refreshProductIndexComponent' => '$refresh'];
    protected $queryString = ['filters'];

    public $showColumns = [
        'image'=>true,
        'title'=>true,
        'price'=>true,
        'stock'=>true,
        'sales'=>true,
        'quantity'=>true,
        'author'=>false
    ];

    public $checked = [];
    public $selectAll = false;

    public function clearFilters()
    {
        $this->filters = [];
    }

    public function deselectAll()
    {
        $this->checked = [];
        $this->selectAll = false;
    }

    public function updatedShowColumns($value)
    {
        \Cookie::queue('productShowColumns', json_encode($this->showColumns));
    }

    public function updatedChecked($value)
    {
        if (count($this->checked) == count($this->products->items())) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectAll();
        } else {
            $this->deselectAll();
        }
    }

    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->products->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    public function multipleMoveToCategory()
    {
        $this->emit('multipleMoveToCategory',$this->checked );
    }

    public function multiplePublish()
    {
        $this->emit('multiplePublish',$this->checked );
    }

    public function multipleUnpublish()
    {
        $this->emit('multipleUnpublish',$this->checked );
    }

    public function multipleDelete()
    {
        $this->emit('multipleDelete',$this->checked );
    }

    public function render()
    {
        return view('product::admin.product.livewire.table', [
            'products'=>$this->products,
            'appliedFilters'=>$this->appliedFilters,
            'appliedFiltersFriendlyNames'=>$this->appliedFiltersFriendlyNames,
        ]);
    }

    public function getProductsProperty()
    {
        return $this->productsQuery->paginate($this->paginate);
    }

    public function getProductsQueryProperty()
    {
        $query = Product::query();
        $query->disableCache(true);

        $this->appliedFilters = [];
        $this->appliedFiltersFriendlyNames = [];
        foreach ($this->filters as $filterKey=>$filterValue) {

            if (empty($filterValue)) {
                continue;
            }

            $this->appliedFilters[$filterKey] = $filterValue;
            $filterFriendlyValue = $filterValue;

            if (is_string($filterValue)) {
                if (strpos($filterValue, ',') !== false) {
                    $filterValueExp = explode(',', $filterValue);
                    if (!empty($filterValueExp)) {
                        $filterFriendlyValue = [];
                        foreach ($filterValueExp as $resourceId) {

                            if ($filterKey == 'page') {
                                $resourceId = intval($resourceId);
                                $getPage = Page::where('id', $resourceId)->first();
                                if ($getPage != null) {
                                    $filterFriendlyValue[] = $getPage->title;
                                }
                            } else if ($filterKey == 'category') {
                                $resourceId = intval($resourceId);
                                $getCategory = Category::where('id', $resourceId)->first();
                                if ($getCategory != null) {
                                    $filterFriendlyValue[] = $getCategory->title;
                                }
                            } else {
                                $filterFriendlyValue[] = $resourceId;
                            }

                        }
                    }
                }
            }

            $this->appliedFiltersFriendlyNames[$filterKey] = $filterFriendlyValue;
        }

        $query->filter($this->appliedFilters);

        return $query;
    }

    public function mount()
    {
        $columnsCookie = \Cookie::get('productShowColumns');
        if (!empty($columnsCookie)) {
            $this->showColumns = json_decode($columnsCookie, true);
        }
    }
}

