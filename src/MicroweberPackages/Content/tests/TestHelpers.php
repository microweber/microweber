<?php
namespace MicroweberPackages\Content\tests;

use MicroweberPackages\Product\Models\Product;

trait TestHelpers {
    private function _generateCategory($url, $title, $pageId)
    {
        $exists = get_category_by_url($url);
        if($exists){
            $url = $url . uniqid();
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

    private function _generateProduct($url, $title, $pageId, $categoryIds = array())
    {
        $params = array(
            'parent' => $pageId,
            'categories' => $categoryIds,
            'title' => $title,
            'url' => $url,
            'content_type' => 'product',
            'subtype' => 'product',
            'is_active' => 1,
        );
        $saveProduct = save_content($params);

        return $saveProduct;
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
    private function _generateShopPage($url, $title)
    {

        $params = array(
            'title' => $title,
            'url' => $url,
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'layout_file' => 'layouts/shop.php',
            'is_shop' => 1,
            'is_active' => 1,
        );
        return save_content($params);
    }
}
