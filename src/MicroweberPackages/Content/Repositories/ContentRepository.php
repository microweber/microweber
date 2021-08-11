<?php


namespace MicroweberPackages\Content\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Content\ContentField;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

/**
 * @mixin AbstractRepository
 */
class ContentRepository extends AbstractRepository
{

    protected $searchable = [
        'id',
        'title',
        'content',
        'content_body',
        'content_type',
        'content_subtype',
        'description',
        'is_home',
        'is_shop',
        'is_deleted',
        'subtype',
        'subtype_value',
        'parent',
        'layout_file',
        'active_site_template',
        'url',
        'content_meta_title',
        'content_meta_keywords',
    ];

    protected $filterMethods = [
        'tags'=>'whereTagsNames',
        'categories'=>'whereCategoryIds',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Content::class;


    public function getByParams($params = [])
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {

            $this->newQuery();

            foreach ($this->searchable as $field) {
                if (!isset($this->filterMethods[$field])) {
                    $fieldCamelCase = str_replace('_', ' ', $field);
                    $fieldCamelCase = ucwords($fieldCamelCase);
                    $fieldCamelCase = str_replace(' ', '', $fieldCamelCase);
                    $this->filterMethods[$field] = 'where' . $fieldCamelCase;
                }
            }
            foreach ($params as $paramKey => $paramValue) {
                if (isset($this->filterMethods[$paramKey])) {
                    $whereMethodName = $this->filterMethods[$paramKey];
                    $this->query->$whereMethodName($paramValue);
                }
            }
        
            if (isset($params['limit']) and ($params['limit'] == 'nolimit' or $params['limit'] == 'no_limit')) {
                unset($params['limit']);
            }
            if (isset($params['limit']) and $params['limit']) {
                $this->query->limit($params['limit']);
            }

            if (isset($params['count']) and $params['count']) {
                $exec = $this->query->count();
            } else if (isset($params['single']) || isset($params['one'])) {
                $exec = $this->query->first();
            } else {
                $exec = $this->query->get();
            }

            $result = [];
            if ($exec != null) {
                if (is_numeric($exec)) {
                    $result = $exec;
                } else {
                    $result = $exec->toArray();
                }
            }

            return $result;
        });
    }

    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getMedia($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            $item = $this->findById($id);
            if ($item) {
                $get = $item->media;
                if ($get) {
                    return $get->toArray();
                }
            }
            return [];

        });
    }


    /**
     * Find categories for content
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getCategories($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {
            $cats = [];


            $item = $this->findById($id);
            if ($item) {
                $get = $item->categories;
                if ($get) {
                    $cats1 = $get->toArray();
                    if ($cats1) {
                        foreach ($cats1 as $cat) {
                            if (isset($cat["parent"])) {
                                unset($cat["parent"]);
                                $cats[] = $cat;
                            }
                            $cats[] = $cat;
                        }
                    }

                    if (is_array($cats) and !empty($cats)) {
                        $cats = array_unique_recursive($cats);
                    }
                    $ready = [];
                    if ($cats) {
                        foreach ($cats as $cat) {

                            $cat_exists = get_category_by_id($cat['parent_id']);
                            if ($cat_exists) {
                                $ready[] = $cat_exists;
                            }
                        }
                    }


                    return $ready;
                }
            }
            return [];


        });
    }


    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getContentData($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {


            $item = $this->findById($id);
            if ($item) {
                $get = $item->contentData;
                if ($get) {
                    return $get->toArray();
                }
            }
            return [];


        });
    }

    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getCustomFields($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {


            $item = $this->findById($id);
            if ($item) {

                $get = $item->customField;
                $ready = [];

                if ($get) {
                    foreach ($get as $item) {
                        $cf_item1 = $item->fieldValue;

                        $cf_item = $item->toArray();
                        $cf_item['value'] = false;
                        $cf_item['values'] = [];
                        if (isset($cf_item['field_value'])) {
                            $vals = [];
                            if (!empty($cf_item['field_value'])) {
                                foreach ($cf_item['field_value'] as $itemv) {
                                    if ($itemv['value']) {
                                        $vals [] = $itemv['value'];
                                    }

                                }
                            }
                            if ($vals) {
                                $cf_item['values'] = $vals;
                                $cf_item['value'] = array_pop($vals);
                            }

                            unset($cf_item['field_value']);

                        }

                        $ready[] = $cf_item;

                    }

                }

                return $ready;
            }
            return [];


        });
    }

    public function getCustomFieldsByType($id, $type)
    {
        $fields = $this->getCustomFields($id);
        if ($fields) {
            foreach ($fields as $k => $field) {
                if (isset($field['type']) and $field['type'] == $type) {

                } else {
                    unset($fields[$k]);
                }

            }
        }

        return $fields;

    }


    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getRelatedContentIds($id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            $related_ids = [];
            $item = $this->findById($id);
            if ($item) {
                $get = $item->related;
                if ($get) {
                    $related = $get->toArray();

                    if ($related) {
                        foreach ($related as $related_cont) {
                            if (isset($related_cont['related_content_id'])) {
                                $related_ids[] = $related_cont['related_content_id'];
                            }
                        }
                        return $related_ids;
                    }

                }
            }
            return [];


        });
    }


    public function getEditField($field, $rel_type, $rel_id = false)
    {


        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($field, $rel_type, $rel_id) {

            $check = ContentField::where('field', $field);
            $check->where('rel_type', $rel_type);
            if ($rel_id) {
                $check->where('rel_id', $rel_id);
            }

            $check->limit(1);

            $check = $check->first();
            if ($check and !empty($check)) {
                return $check->toArray();
            }

            return false;
        });

    }




//
//    /**
//     * Get content parents.
//     *
//     * @param mixed $id
//     *
//     * @return array
//     */
//    public function getParents($id, $without_main_parrent = false)
//    {
//        $without_main_parrent = 0;
//
//        $ids = [];
//        $query = $this->getModel()->newQuery();
//        $query->select(['id', 'parent']);
//
//        $query->where('parent', $id);
//        if ($without_main_parrent) {
//        //   $query->where('parent', '!=', 0);
//        }
//
//        $get = $query->get();
//       // dump($id);
//
//        if ($get) {
//            $ids_result = $get->toArray();
//            if ($ids_result) {
//                foreach ($ids_result as $ids_item) {
//                    if (!in_array($ids_item['id'], $ids)) {
//                        if (!$without_main_parrent) {
//                            $ids[] = $ids_item['id'];
//                        }
//                      //  $sub = $this->getParents($ids_item['id'], $without_main_parrent);
////                        if ($sub) {
////                            $ids = array_merge($ids, $sub);
////                        }
//                    }
//                }
//
//            }
//        }
//
//        if ($ids) {
//            $ids = array_unique($ids);
//
//        }
//
//        return $ids;
//
//
//    }
//


//
//
//    /**
//     * Filter by is_shop attribute
//     *
//     * @return self
//     */
//    public function scopeIsShop()
//    {
//        return $this->addScopeQuery(function ($query) {
//            return $query->where('is_shop', '=', 1);
//        });
//    }

}
