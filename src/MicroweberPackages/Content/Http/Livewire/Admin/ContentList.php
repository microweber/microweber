<?php

namespace MicroweberPackages\Content\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Content\Models\Content;

class ContentList extends Component
{
    use WithPagination;

    public $whitelistedEmptyKeys = [];
    public $paginate = 10;
    protected $paginationTheme = 'bootstrap';

    public $model = Content::class;

    public $filters = [];
    protected $listeners = [
        'refreshContentList' => '$refresh',
        'refreshContentListAndDeselectAll' => 'refreshContentListAndDeselectAll',
        'setFirstPageContentList' => 'setPaginationFirstPage',
        'autoCompleteSelectItem' => 'setFilter',
        'hideFilterItem' => 'hideFilter',
        'applyFilterItem' => 'applyFilterItem',
        'resetFilter' => 'clearFilters',
        'showTrashed' => 'showTrashed',
        'showFromCategory' => 'showFromCategory',
        'showFromPage' => 'showFromPage',
        'deselectAll' => 'deselectAll',
    ];
    protected $queryString = ['filters', 'showFilters', 'paginate'];

    public $showColumns = [
        'id' => true,
        'image' => true,
        'title' => true,
        'author' => true
    ];

    public $showFilters = [];

    public $checked = [];
    public $selectAll = false;

    public $displayType = 'card';

    public function setDisplayType($type)
    {
        $this->displayType = $type;
        \Cookie::queue('orderDisplayType', $type);
    }

    public function clearFilters()
    {
        $this->filters = [];
        $this->showFilters = [];
        $this->setPaginationFirstPage();
    }

    public function setFilter($key, $value)
    {
        if (is_array($key)) {
            foreach ($key as $keyName=>$keyValue) {
                $this->filters[$keyName][$keyValue] = $value;
            }
            return;
        }

        if (is_array($value)) {
            $value = implode(',', $value);
        };
        $this->filters[$key] = $value;
    }

    public function refreshContentListAndDeselectAll()
    {
        $this->deselectAll();
        $this->emit('refreshContentList');


    }
    public function deselectAll()
    {
        $this->checked = [];
        $this->selectAll = false;

    }

    public function updatedShowColumns($value)
    {
        \Cookie::queue($this->getComponentName() . 'ShowColumns', json_encode($this->showColumns));
    }

    public function getComponentName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    public function hideFilter($key)
    {
        if (is_array($key)) {
            foreach ($key as $keyName=>$keyValue) {
                if (isset($this->showFilters[$keyName][$keyValue])) {
                    unset($this->showFilters[$keyName][$keyValue]);
                }
                if (isset($this->filters[$keyName][$keyValue])) {
                    unset($this->filters[$keyName][$keyValue]);
                }
            }
            return;
        }

        if (isset($this->showFilters[$key])) {
            unset($this->showFilters[$key]);
        }

        if (isset($this->filters[$key])) {
            unset($this->filters[$key]);
        }
    }

    public function applyFilterItem($filter, $filterValue)
    {
        $this->removeTrashedFilter();
        $this->filters[$filter] = $filterValue;
        $this->showFilters[$filter] = true;

    }

    public function removeTrashedFilter()
    {
        if (isset($this->filters['trashed'])) {
            unset($this->filters['trashed']);
        }
        if (isset($this->showFilters['trashed'])) {
            unset($this->showFilters['trashed']);
        }

    }

    public function showFromPage($pageId)
    {
        $this->removeTrashedFilter();
        $this->deselectAll();

        if (isset($this->filters['keyword'])) {
            unset($this->filters['keyword']);
        }
        if (isset($this->filters['category'])) {
            unset($this->filters['category']);
        }
        if (isset($this->showFilters['category'])) {
            unset($this->showFilters['category']);
        }
        $this->filters['page'] = $pageId;
        $this->setPaginationFirstPage();
    }


    public function showFromCategory($categoryId)
    {
        $this->deselectAll();

        $this->filters = [];
        $this->showFilters = [];

        $this->filters['category'] = $categoryId;
        $this->setPaginationFirstPage();
    }


    public function showTrashed($showTrashed = false)
    {
        $this->filters['trashed'] = $showTrashed;
        $this->showFilters['trashed'] = true;
        $this->setPaginationFirstPage();
    }

    public function updatedShowFilters($value)
    {
        $this->showFilters = array_filter($this->showFilters);
        if (!empty($this->showFilters)) {
            foreach ($this->showFilters as $filterKey => $filterValue) {
                session()->flash('showFilter' . ucfirst($filterKey), '1');
            }
        }
    }

    public function updatedChecked($value)
    {
        if (count($this->checked) == count($this->contents->items())) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    public function updatedPaginate($limit)
    {
        $this->setPaginationFirstPage();
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
        $this->checked = $this->contents->pluck('id')->map(fn($item) => (string)$item)->toArray();
    }

    public function multipleMoveToCategory()
    {
        $this->emit('multipleMoveToCategory', $this->checked);
    }

    public function multiplePublish()
    {
        $this->emit('multiplePublish', $this->checked);
    }

    public function multipleUnpublish()
    {
        $this->emit('multipleUnpublish', $this->checked);
    }

    public function multipleDelete()
    {
        $this->emit('multipleDelete', $this->checked);
    }

    public function multipleUndelete()
    {
        $this->emit('multipleUndelete', $this->checked);
    }
    public function multipleDeleteForever()
    {
        $this->emit('multipleDeleteForever', $this->checked);
    }

    public function setPaginationFirstPage()
    {
        $this->setPage(1);
    }

    public function render()
    {
        $isInTrashed  = false;
        if (isset($this->showFilters['trashed']) && $this->showFilters['trashed']) {
            $isInTrashed  = true;
        }
        if ($isInTrashed && $this->contents->count() == 0) {
            return view('content::admin.content.livewire.no-content-in-trash');
        }

        $displayFilters = true;
        $showNoActiveContentsScreen = false;
        if ($this->countActiveContents == 0) {
            $showNoActiveContentsScreen = true;
        }

        if ($showNoActiveContentsScreen) {
            return view('content::admin.content.livewire.no-active-content', [
                'contentType'=>$this->contentType
            ]);
        }


        $currentCategory = false;
        if (isset($this->filters['category'])) {
            $currentCategory = get_category_by_id($this->filters['category']);
        }

        if ($currentCategory && (count($this->filters)==1) && $this->contents->count() == 0) {
            return view('content::admin.content.livewire.no-active-content', [
                'contentType'=>$this->contentType,
                'currentCategory'=>$currentCategory,
                'inCategory'=>true
            ]);
        }

        $currentPage = false;
        if (isset($this->filters['page'])) {
            $currentPage = $this->filters['page'];
        }

        if ($currentPage && (count($this->filters)==1) && $this->contents->count() == 0) {
            return view('content::admin.content.livewire.no-active-content', [
                'contentType'=>$this->contentType,
                'inPage'=>true,
            ]);
        }

        return view('content::admin.content.livewire.table', [
            'displayFilters' => $displayFilters,
            'currentCategory' => $currentCategory,
            'isInTrashed' => $isInTrashed,
            'contents' => $this->contents,
            'countActiveContents' => $this->countActiveContents,
            'appliedFilters' => $this->appliedFilters
        ]);
    }

    public function getContentsProperty()
    {
        return $this->contentsQuery->paginate($this->paginate);
    }

    public function getCountActiveContentsProperty()
    {
        return $this->model::select('id')->active()->count();
    }

    public function getContentTypeProperty()
    {
        $contentType = 'content';
        if (strpos($this->model, 'Page') !== false) {
            $contentType = 'page';
        }
        if (strpos($this->model, 'Post') !== false) {
            $contentType = 'post';
        }
        if (strpos($this->model, 'Product') !== false) {
            $contentType = 'product';
        }
        return $contentType;
    }

    public function removeFilter($key)
    {
        if (isset($this->filters[$key])) {
            if ($key == 'tags') {
                $this->emit('tagsResetProperties');
            }
            if ($key == 'userId') {
                $this->emit('usersResetProperties');
            }
            unset($this->filters[$key]);
        }
    }

    public function orderBy($value)
    {
        $this->filters['orderBy'] = $value;
    }

    public function getContentsQueryProperty()
    {
        $query = $this->model::query();
        $query->disableCache(true);

        if (get_option('shop_disabled', 'website') == 'y') {
            $query->where('subtype', '!=', 'product');
            $query->where('is_shop', '!=', '1');
        }

        $this->appliedFilters = [];

        foreach ($this->filters as $filterKey => $filterValue) {

            if (!in_array($filterKey, $this->whitelistedEmptyKeys)) {
                if (empty($filterValue)) {
                    continue;
                }
            }

            $this->appliedFilters[$filterKey] = $filterValue;
        }

        $applyFiltersToQuery = $this->appliedFilters;
        if (!isset($applyFiltersToQuery['orderBy'])) {
            $applyFiltersToQuery['orderBy'] = 'position,desc';
        }
        if (!isset($applyFiltersToQuery['trashed'])) {
            $applyFiltersToQuery['trashed'] = 0;
        }

        $query->filter($applyFiltersToQuery);

        return $query;
    }

    public function mount()
    {
        $displayType = \Cookie::get('orderDisplayType');
        if (!empty($displayType)) {
            $this->displayType = $displayType;
        }

        $columnsCookie = \Cookie::get($this->getComponentName() . 'ShowColumns');
        if (!empty($columnsCookie)) {
            $this->showColumns = json_decode($columnsCookie, true);
        }
    }
}
