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
//        $existingIds = $this->getIdsThatHaveRelation('media', 'content');
//        if (!in_array($id, $existingIds)) {
//            return [];
//        }

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
                $is_cat = app()->category_repository->getById($v);
                if ($is_cat) {
                    $ready[] = $is_cat;
                }
            }
        }

        return $ready;


    }
    public function getContentDataValues($id)
    {
        $id = intval($id);
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            $getContentData = DB::table('content_data')
                ->select(['field_name', 'field_value', 'rel_type', 'rel_id'])
                ->where('rel_type', 'content')
                ->where('rel_id', $id)
                ->get();

            $get = collect($getContentData)->map(function ($item) {
                return (array)$item;
            })->toArray();

            if (!empty($get)) {
                $res = array();
                foreach ($get as $item) {
                    if (isset($item['field_name']) and isset($item['field_value'])) {
                        $res[$item['field_name']] = $item['field_value'];
                    }
                }


                return $res;
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
    public function getContentData($relId)
    {
//        $existingIds = $this->getIdsThatHaveRelation('content_data', 'content');
//        if (!in_array($id, $existingIds)) {
//            return [];
//        }

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($relId) {

            $getContentData = DB::table('content_data')
                ->where('rel_type', 'content');

            if (is_array($relId)) {
                $getContentData->whereIn('rel_id', $relId);
            } else {
                $getContentData->where('rel_id', $relId);
            }

            $getContentData = $getContentData->get();

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
//        $existingIds = $this->getIdsThatHaveRelation('custom_fields', 'content');
//        if (!in_array($id, $existingIds)) {
//            return [];
//        }

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
        $cacheResponse = $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($field, $rel_type, $rel_id) {

            $check = DB::table('content_fields');
            $check->where('field', $field);
            $check->where('rel_type', $rel_type);
            if ($rel_id) {
                $check->where('rel_id', $rel_id);
            }
            $check = $check->first();

            if ($check and !empty($check)) {
                $check = (array) $check;
                return $check;
            }

            return false;
        });


        if (!empty($cacheResponse)) {
            $hookParams = [];
            $hookParams['getEditField'] = true;
            $hookParams['data'] = $cacheResponse;
            $hookParams['hook_overwrite_type'] = 'single';
            $overwrite = app()->event_manager->response(get_class($this) . '\\' . __FUNCTION__, $hookParams);
            if (isset($overwrite['data'])) {
                $cacheResponse = $overwrite['data'];
            }
        }

        return $cacheResponse;
    }

    public function tags($content_id = false, $return_full = false)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use($content_id, $return_full) {

            $query = \Illuminate\Support\Facades\DB::table('tagging_tagged');
            $query->where('taggable_type', 'content');
            if ($content_id) {
                $query->where('taggable_id', $content_id);
            }

            $getTagged = $query->get();
            $getTagged = collect($getTagged)->map(function ($item) {
                return (array)$item;
            })->toArray();

            if ($return_full) {
                return $getTagged;
            }
            $tagNames = [];
            foreach ($getTagged as $tagged) {
                $tagNames[] = $tagged['tag_name'];
            }

            return $tagNames;

        });
    }

    public function getFirstShopPage()
    {
        $shop_page = $this->cacheCallback(__FUNCTION__, func_get_args(), function () {
            $check = DB::table('content')
                ->select('id')
                ->where('content_type', '=', 'page')
                ->where('is_shop', '=', 1)
                ->limit(1)
                ->first();
            if ($check and !empty($check)) {
                return (array)$check;
            }
            return false;
        });

        if ($shop_page and isset($shop_page['id'])) {
            return $this->getById($shop_page['id']);
        }

    }

    public function getThumbnail($contentId, $width = false, $height = false, $crop = false)
    {

        $media_filename = $this->cacheCallback(__FUNCTION__ . '_media__filename', func_get_args(), function () use ($contentId, $width, $height, $crop) {

            $check = DB::table('media');
            $check->select('filename');
            $check->where('rel_id', $contentId);
            $check->where('rel_type', 'content');
            $check->orderBy('position', 'asc');
            $check->limit(1);

            $media = $check->first();
            if ($media) {
                return $media->filename;
            }
            return false;

        });

        if ($media_filename and is_string($media_filename)) {
            return thumbnail($media_filename, $width, $height, $crop);
        }

        return pixum($width, $height);


    }


}
