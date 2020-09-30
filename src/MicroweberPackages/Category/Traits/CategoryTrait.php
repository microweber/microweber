<?php

namespace MicroweberPackages\Category\Traits;

/**
 * Trait CategoryTrait
 * @package MicroweberPackages\Category\Traitsde
 * @deprecated
 */
trait CategoryTrait {

    public $addContentToCategory = [];

    public function addToCategory($contentId)
    {
        $this->addContentToCategory[] = $contentId;
    }


    public static function bootCategoryTrait()
    {
        static::saved(function ($model)  {
            // append content to categories


        });
    }


}