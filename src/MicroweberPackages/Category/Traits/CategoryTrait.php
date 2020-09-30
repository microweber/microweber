<?php

namespace MicroweberPackages\Category\Traits;


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