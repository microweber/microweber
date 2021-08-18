<?php


namespace MicroweberPackages\Content\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Content\ContentField;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

/**
 * @mixin AbstractRepository
 */
class ContentRepository extends AbstractRepository
{
    protected $filterMethods = [
        'tags' => 'whereTagsNames',
        'category' => 'whereCategoryIds',
        'categories' => 'whereCategoryIds',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Content::class;

    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getMedia($id)
    {
        $existingIds = $this->getIdsThatHaveRelation('media', 'content');
        if (!in_array($id, $existingIds)) {
            return [];
        }

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
    public function getCategoriesByContentId($id)
    {
        $categoryIds = $this->cacheCallback(__FUNCTION__ . 'categories_items', func_get_args(), function () use ($id) {

            $categoryIds = [];
            $getCategoryItems = DB::table('categories_items')
                ->select('parent_id')
                ->where('rel_type', 'content')
                ->where('rel_id', $id)
                ->groupBy('parent_id')
                ->get();
            if ($getCategoryItems) {
                foreach ($getCategoryItems as $categoryItem) {
                    $categoryIds[] = $categoryItem->parent_id;
                }
            }
            return $categoryIds;

        });


        $ready = [];
        if ($categoryIds) {

            foreach ($categoryIds as $k => $v) {
                $ready[] = app()->category_repository->getById($v);
            }


        }

        return $ready;


    }
//    public function getCategoriesByContentId11($id)
//    {
//        return $this->cacheCallback(__FUNCTION__.'categories_items', func_get_args(), function () use ($id) {
//
//            $categoryIds = [];
//            $getCategoryItems = DB::table('categories_items')
//                ->select('parent_id')
//                ->where('rel_type', 'content')
//                ->where('rel_id', $id)
//                ->groupBy('parent_id')
//                ->get();
//
//            foreach ($getCategoryItems as $categoryItem) {
//                $categoryIds[] = $categoryItem->parent_id;
//            }
//
//            $getCategories = DB::table('categories')->whereIn('id', $categoryIds)->get();
//            $getCategories = collect($getCategories)->map(function ($item) {
//                return (array)$item;
//            })->toArray();
//
//            return $getCategories;
//        });
//    }

    /**
     * Find content by id.
     *
     * @param mixed $id
     *
     * @return Model|Collection
     */
    public function getContentDataByContentId($id)
    {

        $existingIds = $this->getIdsThatHaveRelation('content_data', 'content');
        if (!in_array($id, $existingIds)) {
            return [];
        }

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            $getContentData = DB::table('content_data')
                ->where('rel_type', 'content')
                ->where('rel_id', $id)
                ->get();

            $contentData = collect($getContentData)->map(function ($item) {
                return (array)$item;
            })->toArray();

            return $contentData;
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
        $existingIds = $this->getIdsThatHaveRelation('custom_fields', 'content');
        if (!in_array($id, $existingIds)) {
            return [];
        }

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            $customFields = [];
            $getCustomFields = DB::table('custom_fields')
                ->where('rel_type', 'content')
                ->where('rel_id', $id)
                ->get();
            foreach ($getCustomFields as $customField) {
                $customField = (array)$customField;

                $getCustomFieldValues = DB::table('custom_fields_values')
                    ->select(['value', 'position'])
                    ->where('custom_field_id', $customField['id'])
                    ->get();

                $customFieldValues = [];
                foreach ($getCustomFieldValues as $customFieldValue) {
                    $customFieldValues[] = $customFieldValue->value;
                }

                if (isset($customFieldValues[0])) {
                    $customField['value'] = $customFieldValues[0];
                    $customField['values'] = $customFieldValues;
                } else {
                    $customField['value'] = false;
                    $customField['values'] = [];
                }

                $customFields[] = $customField;
            }

            return $customFields;

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
