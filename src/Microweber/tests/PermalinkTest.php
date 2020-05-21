<?php
/**
 * Created by PhpStorm.
 * Author: Bozhidar Slaveykov
 * Date: 5/19/2020
 * Time: 3:40 PM
 */

namespace Microweber\tests;

use Microweber\Controllers\DefaultController;

class PermalinkTest extends TestCase
{
    // Ids
    public static $pageId;
    public static $categoryId;
    public static $subCategoryId;
    public static $postId;

    // Slugs
    public static $pageSlug;
    public static $postSlug;
    public static $categorySlug;
    public static $subCategorySlug;

    public function testPageCategoryPost()
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

        $subCategorySlug = 'подкатегория-писана-на-бг'.$time;
        $subCategoryName = 'Подкатегория писана на бг'.$time;

        $postSlug = 'пост-писана-на-бг'.$time;
        $postName = 'пост-писана на бг'.$time;

        // PAGE TEST
        $pageId = $this->_generatePage($pageSlug, $pageName);
        $pageUrl = page_link($pageId);
        $expectedPageUrl = site_url() . $pageSlug;

        $this->assertEquals($expectedPageUrl, $pageUrl);

        /**
         * PARSE LINK FROM THE PAGE
         */
        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->parseLink($pageUrl, 'category');
        $this->assertEquals(false, $getCategoryNameFromUrl);

        // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($pageUrl, 'page');
        $this->assertEquals($pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($pageUrl, 'post');
        $this->assertEquals(false, $getPageNameFromUrl);


        // CATEGORY TEST
        $categoryId = $this->_generateCategory($categorySlug, $categoryName, $pageId);
        $categoryUrl = category_link($categoryId);

        $subCategoryId = $this->_generateCategory($subCategorySlug, $subCategoryName, $categoryId);

        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->parseLink($categoryUrl, 'category');
        $this->assertEquals($categorySlug, $getCategoryNameFromUrl);

         // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($categoryUrl, 'page');
        $this->assertEquals($pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($categoryUrl, 'post');
        $this->assertEquals(false, $getPageNameFromUrl);


        // POST TEST
        $postId = $this->_generatePost($postSlug, $postName, $pageId, [$categoryId,$subCategoryId]);
        $postUrl = content_link($postId);

        /**
         * PARSE LINK FROM THE POST URL
         */
        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->parseLink($postUrl, 'category');
        $this->assertEquals($categorySlug, $getCategoryNameFromUrl);

        // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($postUrl, 'page');
        $this->assertEquals($pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($postUrl, 'post');
        $this->assertEquals($postSlug, $getPageNameFromUrl);

        // Set Ids
        self::$categoryId = $categoryId;
        self::$postId = $postId;
        self::$pageId = $pageId;

        // Set Category Ids
        self::$categorySlug = $categorySlug;
        self::$pageSlug = $pageSlug;
        self::$postSlug = $postSlug;
    }

    public function testCategoryPost()
    {
        // Set format
        $option = array();
        $option['option_value'] = 'category_post';
        $option['option_key'] = 'permalink_structure';
        $option['option_group'] = 'website';
        save_option($option);


        /*
         * TESTING CATEGORY
         */
        $categoryUrl = category_link(self::$categoryId);
        $expectedCategoryUrl = site_url() . self::$categorySlug;

        $this->assertEquals($expectedCategoryUrl, $categoryUrl);


        /*
         * TESTING PAGE
         */
        /**
         * PARSE LINK FROM THE PAGE
         */
        $pageUrl = page_link(self::$pageId);

        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->parseLink($pageUrl, 'category');
        $this->assertEquals(false, $getCategoryNameFromUrl);

        // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($pageUrl, 'page');
        $this->assertEquals(self::$pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($pageUrl, 'post');
        $this->assertEquals(false, $getPageNameFromUrl);


        /**
         * TESTING POST
         */
        $postUrl = content_link(self::$postId);
        /**
         * PARSE LINK FROM THE POST URL
         */
        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->parseLink($postUrl, 'category');
        $this->assertEquals(self::$categorySlug, $getCategoryNameFromUrl);

        // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($postUrl, 'page');
        $this->assertEquals(self::$pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPageNameFromUrl = mw()->permalink_manager->parseLink($postUrl, 'post');
        $this->assertEquals(self::$postSlug, $getPageNameFromUrl);

       /* $defaultController = new DefaultController();
        $postHtml = $defaultController->frontend();

        var_dump($postHtml);
        die();*/
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

    private function _generatePost($url, $title, $pageId, $categoryIds = array())
    {
        $params = array(
            'parent'=>$pageId,
            'categories'=>$categoryIds,
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