<?php
namespace MicroweberPackages\Blog\FrontendFilter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use MicroweberPackages\Blog\FrontendFilter\Traits\CategoriesTrait;
use MicroweberPackages\Blog\FrontendFilter\Traits\LimitTrait;
use MicroweberPackages\Blog\FrontendFilter\Traits\PaginationTrait;
use MicroweberPackages\Blog\FrontendFilter\Traits\SearchTrait;
use MicroweberPackages\Blog\FrontendFilter\Traits\SortTrait;
use MicroweberPackages\Blog\FrontendFilter\Traits\TagsTrait;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;
use MicroweberPackages\Page\Models\Page;

class BaseFilter
{
    use CategoriesTrait, LimitTrait, PaginationTrait, SearchTrait, SortTrait, TagsTrait;

    public $allCustomFieldsForResults = [];
    //public $allCategoriesForResults = [];
    public $allTagsForResults = [];

    public $params = array();
    public $queryParams = array();
    protected $query;
    protected $model;

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function activeFilters($template = false) {

        //dd($this->allCustomFieldsForResults);

        $activeFilters = [];

        $moduleId = $this->params['moduleId'];

        return view($template, compact('activeFilters','moduleId'));
    }

    public function getMainPageId()
    {
        $contentFromId = get_option('content_from_id', $this->params['moduleId']);
        if ($contentFromId) {
            return $contentFromId;
        }

        $findFirtBlog = Page::where('content_type', 'page')
            ->where('subtype','dynamic')
            ->where('is_shop', 0)
            ->first();

        if ($findFirtBlog) {
            return $findFirtBlog->id;
        }

        return 0;
    }


    public function buildFilter()
    {
        $cacheTags = $this->model->getTable();
        $cacheId = 'buildFilter' . $this->getMainPageId() . $this->params['moduleId'];

        $checkCache =  Cache::tags($cacheTags)->get($cacheId);

        if (!empty($checkCache)) {
            if (isset($checkCache['allCustomFieldsForResults'])) {
                $this->allCustomFieldsForResults = $checkCache['allCustomFieldsForResults'];
            }
           return true;
        }

        $query = $this->model::query();
        $query->select(['id']);

        // $query->with('tagged');
        $query->where('parent', $this->getMainPageId());

        $query->with('customField', function ($query) {
            $query->with('fieldValue',function ($query) {
                $query->whereNotNull('value');
                $query->groupBy('value');
            });
        });

        $results = $query->get();

        $allCustomFieldsForResults = [];

        if (!empty($results)) {
            foreach ($results as $result) {

                $resultCustomFields = $result->customField;

                if (!empty($resultCustomFields)) {
                    foreach ($resultCustomFields as $resultCustomField) {

                        $customFieldOptionName = 'filtering_by_custom_fields_' . $resultCustomField->name_key;
                        if (get_option($customFieldOptionName, $this->params['moduleId']) != '1') {
                            continue;
                        }

                        $customFieldValuesClean = [];
                        $customFieldValues = $resultCustomField->fieldValue;
                        if (!empty($customFieldValues)) {
                            foreach($customFieldValues as $customFieldValue) {

                                $customFieldValueClean = new \stdClass();
                                $customFieldValueClean->id = $customFieldValue->id;
                                $customFieldValueClean->custom_field_id = $customFieldValue->custom_field_id;
                                $customFieldValueClean->value = $customFieldValue->value;

                                $customFieldValuesClean[] = $customFieldValueClean;
                            }
                        }

                        $resultCustomFieldClean = new \stdClass();
                        $resultCustomFieldClean->type = $resultCustomField->type;
                        $resultCustomFieldClean->name = $resultCustomField->name;
                        $resultCustomFieldClean->name_key = $resultCustomField->name_key;

                        if (!empty($customFieldValuesClean)) {
                            $allCustomFieldsForResults[$resultCustomField->id] = [
                                'customField'=>$resultCustomFieldClean,
                                'customFieldValues'=>$customFieldValuesClean,
                            ];
                        }
                    }
                }
            }
        }

        $this->allCustomFieldsForResults = $allCustomFieldsForResults;

        Cache::tags($cacheTags)->put($cacheId, ['allCustomFieldsForResults'=>$allCustomFieldsForResults] );
    }

    public function filters($template = false)
    {
        $show = get_option('filtering_by_custom_fields', $this->params['moduleId']);
        if (!$show) {
            return false;
        }

        $requestFilters = $this->getRequest()->get('filters', false);

        $filters = [];

        if (!empty($this->allCustomFieldsForResults)) {
            $filterOptions = [];
            $customFieldsGrouped = [];
            foreach ($this->allCustomFieldsForResults as $result) {

                $customFieldsGrouped[$result['customField']->name_key] = $result['customField'];

                foreach ($result['customFieldValues'] as $customFieldValue) {

                    $customFieldValue->active = 0;

                    // Mark as active
                    if (!empty($requestFilters)) {
                        foreach ($requestFilters as $requestFilterKey => $requestFilterValues) {
                            if (is_array($requestFilterValues)) {
                                if ($requestFilterKey == $result['customField']->name_key) {
                                    foreach ($requestFilterValues as $requestFilterValue) {
                                        if ($requestFilterValue == $customFieldValue->value) {
                                            $customFieldValue->active = 1;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $filterOptions[$result['customField']->name_key][$customFieldValue->value] = $customFieldValue;
                }
            }

            foreach($customFieldsGrouped as $customFieldNameKey=>$customField) {
                if (isset($filterOptions[$customFieldNameKey])) {

                    $readyFilterOptions = $filterOptions[$customFieldNameKey];

                    $controlType = get_option('filtering_by_custom_fields_control_type_' . $customFieldNameKey, $this->params['moduleId']);
                    if (empty($controlType)) {
                        $controlType = 'checkbox';
                    }

                    $filter = new \stdClass();
                    $filter->type = $customField->type;
                    $filter->controlType = $controlType;
                    $filter->name = $customField->name;
                    $filter->options = $readyFilterOptions;

                    if ($customField->type == 'price') {

                        $allPrices = [];
                        foreach($readyFilterOptions as $priceVal=>$priceOption) {
                            $allPrices[] = $priceVal;
                        }

                        $minPrice = 0;
                        $maxPrice = 0;
                        if (isset($allPrices[0])) {
                            $sortedPrices = [];
                            asort($allPrices, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
                            foreach($allPrices as $sortPrice) {
                                $sortedPrices[] = $sortPrice;
                            }
                            $minPrice = $sortedPrices[0];
                            $maxPrice = end($sortedPrices);
                        }

                        $filter->minPrice = round($minPrice);
                        $filter->maxPrice = round($maxPrice);

                        $filter->fromPrice = $filter->minPrice;
                        $filter->toPrice = $filter->maxPrice;

                        if (isset($requestFilters['from_price'])) {
                            $filter->fromPrice = $requestFilters['from_price'];
                        }

                        if (isset($requestFilters['to_price'])) {
                            $filter->toPrice = $requestFilters['to_price'];
                        }

                    }

                    $filters[$customFieldNameKey] = $filter;

                }
            }

        }

        $readyOrderedFilters = [];
        $orderFiltersOption = get_option('filtering_by_custom_fields_order', $this->params['moduleId']);
        if (!empty($orderFiltersOption)) {
            $orderFilters = parse_query($orderFiltersOption);
            foreach ($orderFilters as $filter) {
                if (isset($filters[$filter])) {
                    $readyOrderedFilters[$filter] = $filters[$filter];
                }
            }
            $filters = $readyOrderedFilters;
        }

        $moduleId = $this->params['moduleId'];

        return view($template, compact( 'filters','moduleId'));
    }

    public function getRequest()
    {
        $request = new \Illuminate\Http\Request($_REQUEST);

        $ajaxFilter = $request->get('ajax_filter');
        if (!empty($ajaxFilter)) {
            parse_str($ajaxFilter, $ajaxFilterDecoded);
            $request->merge($ajaxFilterDecoded);
        }

        return $request;
    }

    public function apply()
    {
        $request = $this->getRequest();

        $limit = $request->get('limit', false);
        if ($limit) {
            $this->queryParams['limit'] = $limit;
        }

        $page = $request->get('page', false);
        if ($page) {
            $this->queryParams['page'] = $page;
        }

        $this->query->where('parent', $this->getMainPageId());

        // Search
        $search = $request->get('search');
        if (!empty($search)) {
            $this->query->where('title','LIKE','%'.$search.'%');
        }

        // Sort & Order
        $sort = $request->get('sort', false);
        $order = $request->get('order', false);

        if ($sort && $order) {

            $this->queryParams['sort'] = $sort;
            $this->queryParams['order'] = $order;

            $this->query->orderBy($sort, $order);
        }

        // Tags
        $this->query->with('tagged');
        $tags = $request->get('tags', false);

        if (!empty($tags)) {
            $this->queryParams['tags'] = $tags;
            $this->query->withAllTags($tags);
        }

        // Categories
        $category = $request->get('category');
        if (!empty($category)) {
            $this->queryParams['category'] = $category;
            $this->query->whereHas('categoryItems', function ($query) use($category) {
                $query->where('parent_id', '=', $category);
            });
        }

        $this->buildFilter();

        // filters
        $filters = $customFieldFilters = $request->get('filters');

        // except keys
        if (isset($customFieldFilters['from_price'])) {
            unset($customFieldFilters['from_price']);
        }
        if (isset($customFieldFilters['to_price'])) {
            unset($customFieldFilters['to_price']);
        }

        if (!empty($customFieldFilters)) {
            $this->queryParams['filters'] = $customFieldFilters;
            $this->query->whereCustomField($customFieldFilters);
        }

        if (isset($filters['from_price']) && isset($filters['to_price'])) {
            $this->query->filter([
                'priceBetween'=> $filters['from_price'] . ',' . $filters['to_price']
            ]);
        }

        $this->query->select(['id','parent', 'url','title','content','content_body']);
/*
        $this->query->limit(50);

        dd($this->query->get());*/

        $this->pagination = $this->query->paginate($limit)->withQueryString();

        return $this;
    }
}
