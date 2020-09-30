<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Category;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Product\Events\ProductWasCreated;
use MicroweberPackages\Product\Events\ProductWasUpdated;

class CategoryManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Category\Category    $category_manager
         */
        $this->app->singleton('category_manager', function ($app) {
            return new CategoryManager();
        });


        Event::listen(ProductWasCreated::class, function (ProductWasCreated $event) {

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

        });

        Event::listen(ProductWasUpdated::class, function (ProductWasUpdated $event) {

            $categoryIds = $event->getRequest()['categories'];
            if (empty($categoryIds)) {
                return;
            }
            if (is_string($categoryIds)) {
                $categoryIds = explode(',', $categoryIds);
            }

            foreach($categoryIds as $categoryId) {
                $categoryItem = new CategoryItem();
                $categoryItem->rel_id = $event->getProduct()->id;
                $categoryItem->rel_type = 'content';
                $categoryItem->parent_id = $categoryId;
                $categoryItem->save();
            }

        });

    }
}

