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

    public function generateLink($content)
    {
        $outputContent = $content;
        $premalinkStructure = get_option('permalink_structure', 'website');

        if ($content['content_type'] == 'post' || $content['content_type'] == 'product') {

            $generateUrl = '';

            if (strpos($premalinkStructure, 'page_') !== false) {
                $parentPage = get_pages('id=' . $content['parent'] . '&single=1');
                if ($parentPage) {
                    $generateUrl .= $parentPage['url'] . '/';
                }
            }

            if (strpos($premalinkStructure, 'category') !== false) {
                $categories = get_categories_for_content($content['id']);
                if ($categories) {
                    if (strpos($premalinkStructure, 'category_sub_categories') !== false) {
                        foreach ($categories as $category) {
                            $generateUrl .= $category['url'] . '/';
                        }
                    } else {
                        $generateUrl .= $categories[0]['url'] . '/';
                    }
                }
            }

            $outputContent['url'] = $generateUrl . $outputContent['url'];
        }

        return $outputContent;
    }

    public function getStructures()
    {
        return array(
            'post'=> 'sample-post',
            'page_post'=> 'page/sample-post',
            'category_post'=> 'sample-category/sample-post',
            'category_sub_categories_post'=> 'sample-category/sub-category/sample-post',
            'page_category_post'=> 'sample-page/sample-category/sample-post',
            'page_category_sub_categories_post'=> 'sample-page/sample-category/sub-category/sample-post'
        );
    }
}
