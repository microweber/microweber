<?php


namespace Modules\Content\Repositories;

use MicroweberPackages\Repository\Repositories\AbstractRepository;
use Modules\Content\Models\Content;
use MicroweberPackages\Event\Facades\EventManager;

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
     * Specify Models class name
     *
     * @return string
     */
    public $model = \Modules\Content\Models\Content::class;

    /**
     * Find the media for content by contentId.
     *
     * @param mixed $contentId
     *
     * @return array
     */
    public function getMedia($contentId): array
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($contentId) {
            return Content::getMediaByContentId($contentId);
        });
    }


    /**
     * Retrieve the categories associated with a given content ID.
     *
     * @param mixed $id The ID of the content to retrieve categories for.
     *
     * @return array An array of categories associated with the content ID.
     */
    public function getCategories($id): array
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {
            return Content::getCategoriesForContent($id);
        });
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
            return Content::getContentDataValuesByRelId($id);
        });
    }

    /**
     * Find content data by content id.
     *
     * @param mixed $id
     *
     * @return array
     */
    public function getContentData($relId): array
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($relId) {
            return Content::getContentDataByRelId($relId);
        });
    }


    /**
     * Get custom fields by relId.
     *
     * @param mixed $relId
     *
     * @return array
     */
    public function getCustomFields($relId): array
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($relId) {
            return Content::getCustomFieldsByRelId($relId);
        });
    }

    /**
     * Retrieve custom fields of a specific type for a given relationship ID.
     *
     * @param int $relId The relationship ID.
     * @param string $type The type of custom fields to retrieve.
     *
     * @return array An array containing the custom fields of the specified type.
     */
    public function getCustomFieldsByType($relId, $type): array
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($relId, $type) {
            return Content::getCustomFieldsByRelIdAndType($relId, $type);
        });
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
            return Content::getRelatedContentIdsByContentId($id);
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
        $locale = current_lang();
        $cacheResponse = $this->cacheCallback(__FUNCTION__ . $locale, func_get_args(), function () use ($field, $rel_type, $rel_id) {
            return Content::getEditFieldData($field, $rel_type, $rel_id);
        });

        if (!empty($cacheResponse)) {
            $hookParams = [];
            $hookParams['getEditField'] = true;
            $hookParams['data'] = $cacheResponse;
            $hookParams['hook_overwrite_type'] = 'single';
            $overwrite = EventManager::response(get_class($this) . '\\' . __FUNCTION__, $hookParams);
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
    public function tags($contentId = false, $returnFullTagsData = false): array|false
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($contentId, $returnFullTagsData) {
            return Content::getTagsForContent($contentId, $returnFullTagsData);
        });
    }

    /**
     * Retrieves the first shop page.
     *
     * @return array|null Returns an array containing the first shop page data if found, or null if not found.
     */
    public function getFirstShopPage(): array|null
    {
        return Content::getFirstShopPage();
    }

    public function getAllShopPages(): array|false
    {
        return Content::getAllShopPages();
    }

    public function getAllBlogPages(): array|false
    {
        return Content::getAllBlogPages();
    }

    public function getFirstBlogPage(): array|null
    {
        return Content::getFirstBlogPage();
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
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($contentId, $width, $height, $crop) {
            return Content::getThumbnailByRelId($contentId, $width, $height, $crop);
        });
    }


    /**
     * Get the parents of a given content ID.
     *
     * @param int $id The ID to retrieve the parents for.
     * @return array|false An array of parent IDs, or false if no parents are found.
     */
    public function getParents($id): array|false
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {
            return Content::getParentIds($id);
        });
    }


    /**
     * Get the ID of the first parent content that has a layout template selected.
     *
     * @param int $id The ID of the content.
     * @return int|false The ID of the parent content that has a layout, or false if none found.
     */
    public function getInheritedParent($id): int|false
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {
            return Content::getInheritedParentId($id);
        });
    }


    /**
     * Get the children of a given content.
     *
     * @param int $id The ID of the node to get the children of.
     * @return array|false The IDs of the children of the node or false if no children were found.
     */
    public function getChildren($id = 0): array|false
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($id) {
            return Content::getChildrenIds($id);
        });
    }


    public function createDefaultShopPage(): Content|array
    {
        return Content::createDefaultShopPage();
    }

    public function createDefaultBlogPage(): Content|array|null
    {
        return Content::createDefaultBlogPage();
    }


}
