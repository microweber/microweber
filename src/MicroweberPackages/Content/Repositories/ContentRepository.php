<?php


namespace MicroweberPackages\Content\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Content\Models\Content;
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
    public $model = \MicroweberPackages\Content\Models\Content::class;

    /**
     * Find the media for content by contentId.
     *
     * @param mixed $contentId
     *
     * @return array
     */
    public function getMedia($contentId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($contentId) {
            $item = $this->findById($contentId);

            if ($item && $item->media) {
                return $item->media->toArray();
            }

            return [];
        });
    }


    /**
     * Retrieve the categories associated with a given content ID.
     *
     * @param mixed $id The ID of the content to retrieve categories for.
     *
     * @return array An array of categories associated with the content ID.
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

    /**
     * Find content data values by content id.
     *
     * @param int $id
     *
     * @return array
     */
    public function getContentDataValues(int $id): array
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {

            $getContentData = DB::table('content_data')
                ->select(['field_name', 'field_value'])
                ->where('rel_type', 'content')
                ->where('rel_id', $id)
                ->get();

            $res = [];

            foreach ($getContentData as $item) {
                $res[$item->field_name] = $item->field_value;
            }

            return $res;
        });
    }

    /**
     * Find content data by content id.
     *
     * @param mixed $id
     *
     * @return array
     */
    public function getContentData($relId) : array
    {

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($relId) {

            $getContentData = DB::table('content_data')
                ->where('rel_type', 'content');

            if (is_array($relId)) {
                $getContentData->whereIn('rel_id', $relId);
            } else {
                $getContentData->where('rel_id', $relId);
            }

            $getContentData = $getContentData->get();

            if(!$getContentData) {
                return [];
            }

            $contentData = collect($getContentData)->map(function ($item) {
                return (array)$item;
            })->toArray();

            return $contentData;
        });
    }


    /**
     * Get custom fields by relId.
     *
     * @param mixed $relId
     *
     * @return array
     */
    public function getCustomFields($relId) : array
    {

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($relId) {

            $customFields = [];

            $getCustomFields = DB::table('custom_fields')
                ->where('rel_type', 'content')
                ->where('rel_id', $relId)
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

                $customField['value'] = $customFieldValues[0] ?? false;
                $customField['values'] = $customFieldValues;
                $customField['values_plain'] = implode('|', $customFieldValues);// for the offers module

                $customFields[] = $customField;
            }

            return $customFields;
        });
    }

    /**
     * Retrieve custom fields of a specific type for a given relationship ID.
     *
     * @param int    $relId The relationship ID.
     * @param string $type  The type of custom fields to retrieve.
     *
     * @return array An array containing the custom fields of the specified type.
     */
    public function getCustomFieldsByType($relId, $type) : array
    {
        $fields = $this->getCustomFields($relId);
        if ($fields) {
            foreach ($fields as $k => $field) {
                if (isset($field['type']) and $field['type'] == $type) {
                    // keep the field
                } else {
                    unset($fields[$k]);
                }

            }
        }

        return $fields;

    }


    /**
     * Find related content IDs by content ID.
     *
     * @param mixed $id
     *
     * @return array
     */
    public function getRelatedContentIds($id): array
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {
            $item = $this->findById($id);
            if (!$item) {
                return [];
            }
            /** @var Content $item */
            $related = $item->related;
            if ($related) {
                $related = $related->toArray();
                return array_column($related, 'related_content_id');
            }
            return [];
        });
    }

    /**
     * Returns the HTML for an editable field.
     *
     * @param string $field The field name.
     * @param string $rel_type The related type.
     * @param mixed $rel_id The related ID (optional).
     *
     * @return array|false The HTML for the editable field.
     */
    public function getEditField($field, $rel_type, $rel_id = false): bool|array
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
                $check = (array)$check;
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

    /**
     * Retrieves the tags associated with the specified content.
     *
     * @param bool|int $contentId The ID of the content to retrieve tags for.
     * @param bool $returnFullTagsData Whether to retrieve the full tag data or just the tag names.
     * @return array|false An array of tags associated with the content, or false if there are no tags.
     */
    public function tags($contentId = false, $returnFullTagsData = false)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($contentId, $returnFullTagsData) {

            $query = DB::table('tagging_tagged');
            $query->where('taggable_type', 'content');
            if ($contentId) {
                $query->where('taggable_id', $contentId);
            }

            $getTagged = $query->get();
            $getTagged = collect($getTagged)->map(function ($item) {
                return (array)$item;
            })->toArray();

            if ($returnFullTagsData) {
                return $getTagged;
            }
            $tagNames = [];
            foreach ($getTagged as $tagged) {
                $tagNames[] = $tagged['tag_name'];
            }

            return $tagNames;

        });
    }

    /**
     * Retrieves the first shop page.
     *
     * @return array|null Returns an array containing the first shop page data if found, or null if not found.
     */
    public function getFirstShopPage()
    {
        return get_pages('content_type=page&is_shop=1&is_deleted=0&single=1');

    }
    public function getAllShopPages()
    {
        return get_pages('content_type=page&is_deleted=0&is_shop=1');

    }

    public function getAllBlogPages()
    {
        return get_pages('content_type=page&subtype=dynamic&is_deleted=0&is_shop=0');

    }

    public function getFirstBlogPage()
    {
        return get_pages('content_type=page&subtype=dynamic&is_shop=0&single=1');
    }



    /**
     * Get the filename of the first media related to the content item,
     * and return a thumbnail for it if possible.
     *
     * @param int $contentId The ID of the content item.
     * @param int|false $width Optional. The width of the thumbnail.
     * @param int|false $height Optional. The height of the thumbnail.
     * @param bool|string $crop Optional. Whether to crop the thumbnail.
     *
     * @return string The URL of the thumbnail, or a placeholder image if no media found.
     */
    public function getThumbnail($contentId, $width = false, $height = false, $crop = false): string
    {
        $media_filename = $this->cacheCallback(__FUNCTION__ . '_media__filename', func_get_args(), function () use ($contentId) {
            $check = DB::table('media')
                ->select('filename')
                ->where('rel_id', $contentId)
                ->where('rel_type', 'content')
                ->orderBy('position', 'asc')
                ->limit(1);
            $media = $check->first();
            if ($media) {
                return $media->filename;
            }
            return false;
        });

        if ($media_filename && is_string($media_filename)) {
            return thumbnail($media_filename, $width, $height, $crop);
        }

        return pixum($width, $height);
    }


    /**
     * Get the parents of a given content ID.
     *
     * @param int $id The ID to retrieve the parents for.
     * @return array|false An array of parent IDs, or false if no parents are found.
     */
    public function getParents($id): array|false
    {
        $id = intval($id);
        if ($id <= 0) {
            return false;
        }
        $parentIds = [];

        $params = [
            'id' => $id,
            'limit' => 1,
            'fields' => 'id,parent',
        ];

        $content = $this->getByParams($params);

        if (empty($content)) {
            return false;
        }

        foreach ($content as $item) {
            $parentId = $item['parent'];
            if ($parentId != $item['id'] && $parentId > 0) {
                $parentIds[] = $parentId;
                $previousParents = $this->getParents($parentId);
                if ($previousParents) {
                    $parentIds = array_merge($parentIds, $previousParents);
                }
            }
        }

        $parentIds = array_filter(array_unique($parentIds));

        return $parentIds ?: false;
    }


    /**
     * Get the ID of the first parent content that has a layout template selected.
     *
     * This function is used to retrieve the ID of the parent content that has both
     * 'active_site_template' and 'layout_file' values set in the database field.
     *
     * @param int $id The ID of the content.
     * @return int|false The ID of the parent content that has a layout, or false if none found.
     */
    public function getInheritedParent($id): int|false
    {
        $inheritFrom = $this->getParents($id);

        if (empty($inheritFrom)) {
            return false;
        }
        foreach ($inheritFrom as $value) {
            $parentContent = $this->getById($value);
            if ($parentContent and isset($parentContent['id']) && isset($parentContent['active_site_template']) && isset($parentContent['layout_file']) && $parentContent['layout_file'] != 'inherit') {
                return intval($parentContent['id']);
            }
        }
        return false;

    }


    /**
     * Get the children of a given content.
     *
     * @param int $id The ID of the node to get the children of.
     * @return array|false The IDs of the children of the node or false if no children were found.
     */
    public function getChildren($id = 0)
    {
        if (!intval($id)) {
            return false;
        }

        $ids = array($id);

        $content_ids = $this->getByParams('fields=id&no_limit=1&parent=' . $id);
        foreach ($content_ids as $n) {
            if ($id != $n['id']) {
                $ids[] = $n['id'];
            }
        }

        $taxonomies = $this->getByParams(array('parent' => $id));
        foreach ($taxonomies as $item) {
            $ids[] = $item['id'];
            if ($item['parent'] != $item['id'] && intval($item['parent'])) {
                $ids = array_merge($ids, $this->getChildren($item['id']));
            }
        }

        return $ids ? array_unique($ids) : false;
    }





}
