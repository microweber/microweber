<?php

namespace MicroweberPackages\Category\Listeners;


use MicroweberPackages\Category\Models\CategoryItem;

/**
 * Class AddCategoryListener
 * @package MicroweberPackages\Category\Listeners
 * @deprecated
 */
class AddCategoryListener
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

            foreach ($categoryIds as $categoryId) {
                $categoryItem = new CategoryItem();
                $categoryItem->rel_id = $event->getModel()->id;
                $categoryItem->rel_type = 'content';
                $categoryItem->parent_id = $categoryId;
                $categoryItem->save();
            }
        }
    }
}
