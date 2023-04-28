<?php

namespace MicroweberPackages\Content\Services;


class ContentService
{

    /**
     * Get the parents of a given content ID.
     *
     * @param mixed $id The ID to retrieve the parents for.
     * @param bool $removeRootParent Whether to exclude the main parent from the result.
     * @return array|false An array of parent IDs, or false if no parents are found.
     */
    public function getParents($id, bool $removeRootParent = false): array|false
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
        if ($removeRootParent) {
            $params['parent'] = '[neq]0';
        }

        $content = app()->content_repository->getByParams($params);

        if (empty($content)) {
            return false;
        }

        foreach ($content as $item) {
            $parentId = $item['parent'];
            if ($parentId != $item['id'] && $parentId > 0) {
                $parentIds[] = $parentId;
                $parentIds = array_merge($parentIds, $this->getParents($parentId, $removeRootParent));
            }
        }

        $parentIds = array_filter(array_unique($parentIds));

        return $parentIds ?: false;
    }
}
