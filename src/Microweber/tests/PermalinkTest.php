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
        mw()->database_manager->extended_save_set_permission(true);

        // Set format
        $option = array();
        $option['option_value'] = 'page_category_post';
        $option['option_key'] = 'permalink_structure';
        $option['option_group'] = 'website';
        save_option($option);

        $time = time();

        $pageSlug = 'уникален-блог'.$time;
        $pageName = 'Уникален Блог'.$time;

        $categorySlug = 'категория-писана-на-бг'.$time;
        $categoryName = 'Категория писана на бг'.$time;

        $postSlug = 'пост-писана-на-бг'.$time;
        $postName = 'пост-писана на бг'.$time;

        $pageId = $this->_generatePage($pageSlug, $pageName);


        // CATEGORY TEST
        $categoryId = $this->_generateCategory($categorySlug, $categoryName, $pageId);
        $categoryUrl = category_link($categoryId);

        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->parseLink($categoryUrl, 'category');
        $this->assertEquals($categorySlug, $getCategoryNameFromUrl);

         // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($categoryUrl, 'page');
        $this->assertEquals($pageSlug, $getPageNameFromUrl);

        // POST TEST
        $postId = $this->_generatePost($postSlug, $postName, $pageId, $categoryId);
        $postUrl = content_link($postId);

        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->parseLink($postUrl, 'category');
        $this->assertEquals($categorySlug, $getCategoryNameFromUrl);

        // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($postUrl, 'page');
        $this->assertEquals($pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($postUrl, 'post');
        $this->assertEquals($postSlug, $getPageNameFromUrl);
        
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

    private function _generatePost($url, $title, $pageId, $categoryId)
    {
        $params = array(
            'parent'=>$pageId,
            'categories'=>[$categoryId],
            'title' =>$title,
            'url' =>$url,
            'content_type' => 'post',
            'subtype' => 'post',
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