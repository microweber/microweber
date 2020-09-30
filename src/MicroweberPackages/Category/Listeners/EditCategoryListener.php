<?php
namespace MicroweberPackages\Category\Listeners;

use MicroweberPackages\Category\CategoryItem;

class EditCategoryListener
{
    public function handle($event)
    {
        $categoryIds = $event->getRequest()['categories'];
        if (empty($categoryIds)) {
            return;
        }
        if (is_string($categoryIds)) {
            $categoryIds = explode(',', $categoryIds);
        }

        foreach($categoryIds as $categoryId) {

            $categoryItem = CategoryItem::where('rel_id', $event->getProduct()->id)->where('parent_id', $categoryId)->first();
            if (!$categoryItem) {
                $categoryItem = new CategoryItem();
            }

            $categoryItem->rel_id = $event->getProduct()->id;
            $categoryItem->rel_type = 'content';
            $categoryItem->parent_id = $categoryId;
            $categoryItem->save();
        }
    }
}
