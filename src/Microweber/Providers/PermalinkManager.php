<?php

namespace Microweber\Providers;


class PermalinkManager
{

    public $categoryTreeLevel = 3;

    public function parseLink($link, $type = 'page')
    {
        if (!$link) {
            $link = mw()->url_manager->current();
        }
        $link = urldecode($link);
        $linkSegments = url_segment(-1, $link);

        $premalinkStructure = get_option('permalink_structure', 'website');
        if ($premalinkStructure == 'page_category_post') {

            if (isset($linkSegments[0]) && $type == 'page') {
                return $linkSegments[0];
            }

            if (isset($linkSegments[1]) && $type == 'category') {
                return $linkSegments[1];
            }

            if (isset($linkSegments[2]) && $type == 'post') {
                return $linkSegments[2];
            }
        }
        if ($premalinkStructure == 'page_category_sub_categories_post') {
            if (isset($linkSegments[0]) && $type == 'page') {
                return $linkSegments[0];
            }
            if (isset($linkSegments[0]) && $type == 'post') {
                return last($linkSegments);
            }
            if (isset($linkSegments[1]) && $type == 'categories') {
                $categories = array();
                unset($linkSegments[0]);
                foreach ($linkSegments as $segment) {
                    $categories[] = $segment;
                }
                return $categories;
            }
        }

        return last($linkSegments);
    }

    public function generateLink($content)
    {
        $outputContent = $content;
        $premalinkStructure = get_option('permalink_structure', 'website');

        if ($content['content_type'] == 'post' || $content['content_type'] == 'page' || $content['content_type'] == 'product') {

            $generateUrl = '';

            if (strpos($premalinkStructure, 'page_') !== false) {
                $parentPage = get_pages('id=' . $content['parent'] . '&single=1');
                if ($parentPage) {
                    $generateUrl .= $parentPage['url'] . '/';
                }
            }

          /*  if (strpos($premalinkStructure, 'category') !== false) {
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
            }*/

            $outputContent['url'] = $generateUrl . $outputContent['url'];
        }

        return $outputContent;
    }

    public function generateContentLink()
    {

    }

    public function generateCategoryLink($categoryId)
    {
        if (intval($categoryId) == 0) {
            return false;
        }

        $categoryId = intval($categoryId);
        $categoryInfo = mw()->category_manager->get_by_id($categoryId);

        if (!isset($categoryInfo['rel_type'])) {
            return;
        }

        if (trim($categoryInfo['rel_type']) != 'content') {
            return;
        }

        $generateUrl = '';

        $content = mw()->category_manager->get_page($categoryId);
        if ($content) {
            $generateUrl .= mw()->app->content_manager->link($content['id']).'/';
        }

        if (!$content  && defined('PAGE_ID')) {
            $generateUrl .= mw()->app->content_manager->link(PAGE_ID).'/';
        }

        $parentCategoryInfo = mw()->category_manager->get_by_id($categoryInfo['parent_id']);
        if ($parentCategoryInfo) {
            $generateUrl .= $parentCategoryInfo['url'] . '/';
        }

        $generateUrl .= $categoryInfo['url'];

        return $generateUrl;

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
