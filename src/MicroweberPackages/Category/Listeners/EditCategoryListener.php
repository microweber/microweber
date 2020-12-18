<?php

namespace MicroweberPackages\Category\Listeners;

use MicroweberPackages\Category\Models\CategoryItem;

/**
 * Class EditCategoryListener
 * @package MicroweberPackages\Category\Listeners
 * @deprecated
 */
class EditCategoryListener
{
    public function handle($event)
    {
        $data = $event->getData();

        if (isset($data['categories'])) {
            $categoryIds = $data['categories'];
            if (empty($categoryIds)) {
                return;
            }
            if (is_string($categoryIds)) {
                $categoryIds = explode(',', $categoryIds);
            }

            if (empty($categoryIds)) {
                return;
            }

            $entityCategories = CategoryItem::where('rel_id', $event->getModel()->id)->get();
            if ($entityCategories) {
                foreach ($entityCategories as $entityCategory) {
                    if (!in_array($entityCategory->parent_id, $categoryIds)) {
                        $entityCategory->delete();
                    }
                }
            }

            foreach ($categoryIds as $categoryId) {

                $categoryItem = CategoryItem::where('rel_id', $event->getModel()->id)->where('parent_id', $categoryId)->first();
                if (!$categoryItem) {
                    $categoryItem = new CategoryItem();
                }

                $categoryItem->rel_id = $event->getModel()->id;
                $categoryItem->rel_type = 'content';
                $categoryItem->parent_id = $categoryId;
                $categoryItem->save();
            }
        }
    }
}
