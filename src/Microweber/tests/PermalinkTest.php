<?php
/**
 * Created by PhpStorm.
 * Author: Bozhidar Slaveykov
 * Date: 5/19/2020
 * Time: 3:40 PM
 */

namespace Microweber\tests;

class PermalinkTest extends TestCase
{
    public function testSimplePostVariant()
    {
        // Set format
        $option = array();
        $option['option_value'] = 'page_category_post';
        $option['option_key'] = 'permalink_structure';
        $option['option_group'] = 'website';
        save_option($option);

        $time = time();

        $categorySlug = 'категория-писана-на-бг'.$time;
        $categoryName = 'Категория писана на бг'.$time;

        $pageSlug = 'уникален-блог'.$time;
        $pageName = 'Уникален Блог'.$time;

        $pageId = $this->_generatePage($pageSlug, $pageName);

        $categoryId = $this->_generateCategory($categorySlug, $categoryName, $pageId);
        $categoryUrl = category_link($categoryId);

        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->parseLink($categoryUrl, 'category');
        $this->assertEquals($categorySlug, $getCategoryNameFromUrl);

         // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($categoryUrl, 'page');
        $this->assertEquals($pageSlug, $getPageNameFromUrl);

    }

    private function _generateCategory($url, $title, $pageId)
    {

        $params = array(
            'content_id'=>$pageId,
            'title' => $title,
            'url' => $url,
            'is_active' => 1,
        );

        return save_category($params);
    }

    private function _generatePost($url, $title, $pageId)
    {

        $params = array(
            'parent'=>$pageId,
            'title' =>$title,
            'url' =>$url,
            'content_type' => 'post',
            'is_active' => 1,
        );
        $savePost = save_content($params);

        return $savePost;
    }

    private function _generatePage($url, $title){

        $params = array(
            'title' => $title,
            'url'=> $url,
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'is_active' => 1,
        );
        return save_content($params);
    }
}