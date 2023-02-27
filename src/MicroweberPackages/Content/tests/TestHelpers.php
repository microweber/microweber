<?php
namespace MicroweberPackages\Content\tests;

trait TestHelpers {
    private function _generateCategory($url, $title, $pageId)
    {
        $exists = get_category_by_url($url);
        if($exists){
            return $exists['id'];
        }

        $params = array(
            'content_id' => $pageId,
            'title' => $title,
            'url' => $url,
            'is_active' => 1,
        );

        return save_category($params);
    }

    private function _generatePost($url, $title, $pageId, $categoryIds = array())
    {
        $params = array(
            'parent' => $pageId,
            'categories' => $categoryIds,
            'title' => $title,
            'url' => $url,
            'content_type' => 'post',
            'subtype' => 'post',
            'is_active' => 1,
        );
        $savePost = save_content($params);

        return $savePost;
    }

    private function _generatePage($url, $title)
    {

        $params = array(
            'title' => $title,
            'url' => $url,
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'is_active' => 1,
        );
        return save_content($params);
    }
}
