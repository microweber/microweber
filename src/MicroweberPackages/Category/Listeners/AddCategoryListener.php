<?php

namespace MicroweberPackages\Category\Listeners;


use MicroweberPackages\Category\CategoryItem;

class AddCategoryListener
{
    public function handle($event)
    {
        $request = $event->getRequest();
        if (isset($request['categories'])) {
            $categoryIds = $event->getRequest()['categories'];
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
