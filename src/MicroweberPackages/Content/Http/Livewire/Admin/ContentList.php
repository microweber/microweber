<?php

namespace MicroweberPackages\Content\Http\Livewire\Admin;

use Livewire\WithPagination;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Export\Formats\XlsxExport;

class ContentList extends AdminComponent
{
    use WithPagination;

    public $openLinksInModal = false;
    public $displayTypesViews = [];
    public $noActiveContentView = 'content::admin.content.livewire.no-active-content';
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
        }
        if (empty($value)) {
            unset($this->filters[$key]);
            return;
        }
        $this->filters[$key] = $value;
    }

    public function refreshContentListAndDeselectAll()
    {
        $this->deselectAll();
        $this->dispatch('refreshContentList');
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

        $pageData = get_content_by_id($pageId);

        $showFromPageAndParent = false;
        if (isset($pageData['content_type']) && $pageData['content_type'] == 'page') {
            if (isset($pageData['subtype']) && $pageData['subtype'] == 'static') {
                $showFromPageAndParent = true;
            }

        }
        if ($showFromPageAndParent) {
            $this->filters['pageAndParent'] = $pageId;
            if(isset($this->filters['page'])){
                unset($this->filters['page']);
            }
         } else {
            $this->filters['page'] = $pageId;
             if(isset($this->filters['pageAndParent'])){
                unset($this->filters['pageAndParent']);
            }
        }

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

    public function updateShowFilters()
    {
        $this->showFilters = array_filter($this->showFilters);
        if (!empty($this->showFilters)) {
            foreach ($this->showFilters as $filterKey => $filterValue) {
                session()->flash('showFilter' . ucfirst($filterKey), '1');
            }
        }
        $this->dispatch('$refresh')->self();
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
        $this->dispatch('multipleMoveToCategory', $this->checked);
    }

    public function multiplePublish()
    {
        $this->dispatch('multiplePublish', $this->checked);
    }

    public function multipleUnpublish()
    {
        $this->dispatch('multipleUnpublish', $this->checked);
    }

    public function multipleDelete()
    {
        $this->dispatch('multipleDelete', $this->checked);
    }

    public function multipleUndelete()
    {
        $this->dispatch('multipleUndelete', $this->checked);
    }
    public function multipleDeleteForever()
    {
        $this->dispatch('multipleDeleteForever', $this->checked);
    }

    public function unpublish($id)
    {
        $findContent = $this->model::where('id', $id)->first();
        if ($findContent) {
            $findContent->is_active = 0;
            $findContent->save();
            $this->dispatch('refreshContentList');
        }
    }

    public function publish($id)
    {
        $findContent = $this->model::where('id', $id)->first();
        if ($findContent) {
            $findContent->is_active = 1;
            $findContent->save();
            $this->dispatch('refreshContentList');
        }
    }

    public function setPaginationFirstPage()
    {
        $this->setPage(1);
    }

    public function render()
    {
        $renderData = $this->getRenderData();

        return view($renderData['view'], $renderData['data']);
    }

    public function getRenderData()
    {
        $currentCategory = false;

        $currentPageData = false;
        if (isset($this->filters['page'])) {
            $currentPageData = get_content_by_id($this->filters['page']);
        }
        if (isset($this->filters['pageAndParent'])) {
            $currentPageData = get_content_by_id($this->filters['pageAndParent']);
        }
        $currentCategory = [];
        if (isset($this->filters['category'])) {
            $currentCategory = get_category_by_id($this->filters['category']);
            if ($currentPageData === false and $currentCategory) {
                $currentCategory = array_filter($currentCategory);
                $pageForCategory = get_page_for_category($currentCategory['id']);
                if ($pageForCategory) {
                     $currentPageData = $pageForCategory;
                 }
            }
        }

        $currentPageId = false;
        $currentCategoryId = false;
        if($currentPageData and isset($currentPageData['id'])){
             $currentPageId = $currentPageData['id'];
        }
        if($currentCategory and isset($currentCategory['id'])){
            $currentCategoryId = $currentCategory['id'];
        }
         $isInTrashed  = false;
        if (isset($this->showFilters['trashed']) && $this->showFilters['trashed']) {
            $isInTrashed  = true;
        }
        $contentCount =  $this->contents->count() ;
        if ($isInTrashed && (count($this->filters)==1) && $contentCount== 0) {
            return [
                'view'=>'content::admin.content.livewire.no-content-in-trash',
                'data'=>[
                    'isInTrashed' => $isInTrashed,
                    'currentCategoryId'=>$currentCategoryId,
                    'currentPageId' => $currentPageId,
                    'total' => $contentCount,

                ]
            ];
        } else {
            $this->countActiveContents =$contentCount;
        }

        $displayFilters = true;
        $showNoActiveContentsScreen = false;

        $contentTypeForAddButton = $this->contentType;

        if ($this->countActiveContents == 0) {
            $showNoActiveContentsScreen = true;
        }


        if($currentPageData and isset($currentPageData['subtype']) and $currentPageData['subtype'] == 'dynamic'){
            $contentTypeForAddButton = 'post';
        }
        if($currentPageData and isset($currentPageData['is_shop']) and $currentPageData['is_shop']){
            $contentTypeForAddButton = 'product';
        }

//        if ($showNoActiveContentsScreen) {
//            return [
//                'view'=>$this->noActiveContentView,
//                'data'=>[
//                    'contentType'=>$contentTypeForAddButton,
//                    'isInTrashed' => $isInTrashed,
//                    'currentCategoryId'=>$currentCategoryId,
//                    'currentPageId' => $currentPageId,
//                    'total' => $this->countActiveContents,
//                ]
//            ];
//        }
//
        if ($currentCategory && (count($this->filters)==1) && $this->contents->count() == 0) {
            return [
                'view'=>$this->noActiveContentView,
                'data'=>[
                    'contentType'=>$contentTypeForAddButton,
                    'currentCategoryId'=>$currentCategoryId,
                    'currentCategory' => $currentCategory,
                    'currentPage' => $currentPageData,
                    'currentPageId' => $currentPageId,
                    'inCategory'=>true,
                    'isInTrashed' => $isInTrashed,
                    'total' => $this->countActiveContents,

                ]
            ];
        }

        $currentPageDataId = false;
        if (isset($this->filters['page'])) {
            $currentPageDataId = $this->filters['page'];
        }

        if ($currentPageDataId && (count($this->filters)==1) && $this->contents->count() == 0) {
            return [
                'view'=>$this->noActiveContentView,
                'data'=>[
                    'contentType'=>$contentTypeForAddButton,
                    'currentCategoryId'=>$currentCategoryId,
                    'currentCategory' => $currentCategory,
                    'currentPage' => $currentPageData,
                    'currentPageId' => $currentPageId,
                    'inPage'=>true,
                    'isInTrashed' => $isInTrashed,
                    'total' => $this->countActiveContents,

                ]
            ];
        }

        return [
            'view'=>'content::admin.content.livewire.table',
            'data'=>[
                'displayTypesViews' => $this->displayTypesViews,
                'dropdownFilters' => $this->dropdownFilters,
                'contentType' => $contentTypeForAddButton,

                'displayFilters' => $displayFilters,
                'currentCategoryId' => $currentCategoryId,
                'currentCategory' => $currentCategory,
                'currentPage' => $currentPageData,
                'currentPageId' => $currentPageId,
                'isInTrashed' => $isInTrashed,
                'contents' => $this->contents,
                'countActiveContents' => $this->countActiveContents,
                'total' => $this->countActiveContents,
                'appliedFilters' => $this->appliedFilters,
                'cardStats'=>$this->getCardsStats()
            ]
        ];
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
                $this->dispatch('tagsResetProperties');
            }
            if ($key == 'userId') {
                $this->dispatch('usersResetProperties');
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

    public function getDropdownFiltersProperty()
    {
        $dropdownFilters = [];

        $taxonomiesFields = $this->getDropdownFiltersTaxonomies();
        $dropdownFilters = array_merge($dropdownFilters, $taxonomiesFields);

        $datesFields = $this->getDropdownFiltersDates();
        $dropdownFilters = array_merge($dropdownFilters, $datesFields);

        $otherFields = $this->getDropdownFiltersOthers();
        $dropdownFilters = array_merge($dropdownFilters, $otherFields);

        $templateFields = $this->getDropdownFiltersTemplateSettings();
        $dropdownFilters = array_merge($dropdownFilters, $templateFields);

        $templateFields = $this->getDropdownFiltersTemplateFields();
        $dropdownFilters = array_merge($dropdownFilters, $templateFields);

        return $dropdownFilters;
    }

    public function getDropdownFiltersOthers()
    {
        $dropdownFilters = [];
        $dropdownFilters[] = [
            'groupName' => 'Other',
            'class'=> 'col-md-12',
            'filters'=> [
                [
                    'name' => 'Visible',
                    'key' => 'visible',
                ],
                [
                    'name' => 'Author',
                    'key' => 'userId',
                ]
            ]
        ];
        return $dropdownFilters;
    }

    public function getDropdownFiltersDates()
    {
        $dropdownFilters = [];
        $dropdownFilters[] = [
            'groupName' => 'Dates',
            'class'=> 'col-md-12',
            'filters'=> [
                [
                    'name' => 'Date Range',
                    'key' => 'dateBetween',
                ],
                [
                    'name' => 'Created at',
                    'key' => 'createdAt',
                ],
                [
                    'name' => 'Updated at',
                    'key' => 'updatedAt',
                ]
            ]
        ];
        return $dropdownFilters;
    }

    public function getDropdownFiltersTaxonomies()
    {
        $dropdownFilters = [];
        $dropdownFilters[] = [
            'groupName' => 'Taxonomies',
            'class'=> 'col-md-12',
            'filters'=> [
                [
                    'name' => 'Tags',
                    'key' => 'tags'
                ],[
                    'name' => 'Category',
                    'key' => 'category'
                ]
            ]
        ];
        return $dropdownFilters;
    }

    public function getDropdownFiltersTemplateSettings()
    {
        $dropdownFilters = [];
        $templateFields = app()->template_manager->get_data_fields($this->contentType);
        if (!empty($templateFields)) {
            $filters = [];
            foreach ($templateFields as $templateFieldKey => $templateFieldName) {
                $filters[] = [
                    'name' => $templateFieldName,
                    'key' => 'contentData.' . $templateFieldKey,
                ];
            }
            $dropdownFilters[] = [
                'groupName' => 'Template settings',
                'class'=>'col-md-12',
                'filters'=>$filters
            ];
        }

        return $dropdownFilters;
    }

    public function getDropdownFiltersTemplateFields()
    {
        $dropdownFilters = [];
        $templateFields = app()->template_manager->get_edit_fields($this->contentType);
        if (!empty($templateFields)) {
            $filters = [];
            foreach ($templateFields as $templateFieldKey => $templateFieldName) {
                $filters[] = [
                    'name' => $templateFieldName,
                    'key' => 'contentFields.' . $templateFieldKey,
                ];
            }
            $dropdownFilters[] = [
                'groupName' => 'Template fields',
                'class'=>'col-md-12',
                'filters'=>$filters
            ];
        }

        return $dropdownFilters;
    }

    public function export() {

        $exportingData = $this->contentsQuery->get();

        $exportExcel = new XlsxExport();
        $exportExcel->data['mw_export_' . date('Y-m-d-H-i-s')] = $exportingData->toArray();
        $exportExcel = $exportExcel->start();
        $exportExcelFile = $exportExcel['files']['0']['filepath'];

        return response()->download($exportExcelFile);

    }

    public function getCardsStats()
    {
        return [];

        // if you want statistic cards on the top of the page
        return [
            [
                'name' => 'All',
                'value' => $this->contentsQuery->count(),
                'icon' => 'mdi mdi-account-multiple',
                'bgClass' => 'bg-primary',
                'textClass' => 'text-white'
            ],
            [
                'name' => 'Active',
                'value' => $this->contentsQuery->active()->count(),
                'icon' => 'mdi mdi-account-multiple',
                'bgClass' => 'bg-success',
                'textClass' => 'text-white'
            ],
            [
                'name' => 'Inactive',
                'value' => $this->contentsQuery->inactive()->count(),
                'icon' => 'mdi mdi-account-multiple',
                'bgClass' => 'bg-warning',
                'textClass' => 'text-white'
            ],
            [
                'name' => 'Trashed',
                'value' => $this->contentsQuery->trashed()->count(),
                'icon' => 'mdi mdi-account-multiple',
                'bgClass' => 'bg-danger',
                'textClass' => 'text-white'
            ]
        ];
    }
}
