<?php

namespace Microweber\Providers;


class PermalinkManager
{
    public $app;

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function generateLink()
    {

        $currentPremalinkStructure = get_option('permalink_structure', 'website');



    }

    public function getStructures()
    {
        return array(
            'post'=> 'sample-post',
            'category_post'=> 'sample-category/sample-post',
            'page_category_post'=> 'sample-page/sample-category/sample-post',
            'page_category_sub_categories_post'=> 'sample-page/sample-category/sub-category/sample-post'
        );
    }
}
