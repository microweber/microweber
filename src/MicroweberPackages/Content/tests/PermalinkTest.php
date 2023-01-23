<?php
/**
 * Created by PhpStorm.
 * Author: Bozhidar Slaveykov
 * Date: 5/19/2020
 * Time: 3:40 PM
 */

namespace MicroweberPackages\Content\tests;

use MicroweberPackages\Core\tests\TestCase;

class PermalinkTest extends TestCase
{
    // Ids
    public static $pageId;
    public static $categoryId;
    public static $subCategoryId;
    public static $postId;
    public static $postWithoutCategoryId;

    // Slugs
    public static $pageSlug;
    public static $postSlug;
    public static $postWithoutCategorySlug;
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

        $pageSlug = 'уникален-блог' . $time;
        $pageName = 'Уникален Блог' . $time;

        $categorySlug = 'категория-писана-на-бг' . $time;
        $categoryName = 'Категория писана на бг' . $time;

        $subCategorySlug = 'подкатегория-писана-на-бг' . $time;
        $subCategoryName = 'Подкатегория писана на бг' . $time;

        $subChildCategorySlug = 'дете-на-подкатегорията-бг' . $time;
        $subChildCategoryName = 'Дете на подкатегорията бг' . $time;

        $postSlug = 'пост-писана-на-бг' . $time;
        $postName = 'пост писана на бг' . $time;

        $postWithoutCategorySlug = 'пост-без-категория-на-бг' . $time;
        $postWithoutCategoryName = 'пост без категория на бг' . $time;

        // PAGE TEST
        $pageId = $this->_generatePage($pageSlug, $pageName);
        $pageUrl = page_link($pageId);
        $expectedPageUrl = site_url() . $pageSlug;

        $this->assertEquals($expectedPageUrl, $pageUrl);

        /**
         * PARSE LINK FROM THE PAGE
         */
        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->slug($pageUrl, 'category');
        $this->assertEquals(false, $getCategoryNameFromUrl);

        // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->slug($pageUrl, 'page');
        $this->assertEquals($pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPageNameFromUrl = mw()->permalink_manager->slug($pageUrl, 'post');
        $this->assertEquals(false, $getPageNameFromUrl);


        // CATEGORY TEST
        $categoryId = $this->_generateCategory($categorySlug, $categoryName, $pageId);
        $categoryUrl = category_link($categoryId);

        $subCategoryId = $this->_generateCategory($subCategorySlug, $subCategoryName, $categoryId);
        $subChildCategoryId = $this->_generateCategory($subChildCategorySlug, $subChildCategoryName, $subCategoryId);

        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->slug($categoryUrl, 'category');


        $this->assertEquals($categorySlug, $getCategoryNameFromUrl);

        // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->slug($categoryUrl, 'page');
        $this->assertEquals($pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPageNameFromUrl = mw()->permalink_manager->slug($categoryUrl, 'post');
        $this->assertEquals(false, $getPageNameFromUrl);


        // POST TEST
        $postId = $this->_generatePost($postSlug, $postName, $pageId, [$categoryId, $subCategoryId, $subChildCategoryId]);
        $postUrl = content_link($postId);

        // POST WITHOUT CATEGORY TEST TEST
        $postWithoutCategoryId = $this->_generatePost($postWithoutCategorySlug, $postWithoutCategoryName, $pageId, false);
        $postWithoutCategoryUrl = content_link($postWithoutCategoryId);

        /**
         * PARSE LINK FROM THE POST URL
         */
        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->slug($postUrl, 'category');
        $this->assertEquals($categorySlug, $getCategoryNameFromUrl);

        // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->slug($postUrl, 'page');
        $this->assertEquals($pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPostNameFromUrl = mw()->permalink_manager->slug($postUrl, 'post');
        $this->assertEquals($postSlug, $getPostNameFromUrl);

        // Set Ids
        self::$categoryId = $categoryId;
        self::$postId = $postId;
        self::$postWithoutCategoryId = $postWithoutCategoryId;
        self::$pageId = $pageId;

        // Set Category Ids
        self::$categorySlug = $categorySlug;
        self::$pageSlug = $pageSlug;
        self::$postSlug = $postSlug;
        self::$postWithoutCategorySlug = $postWithoutCategorySlug;
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
        $getCategoryNameFromUrl = mw()->permalink_manager->slug($pageUrl, 'category');

        $this->assertEquals(false, $getCategoryNameFromUrl);

        // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->slug($pageUrl, 'page');
        $this->assertEquals(self::$pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPageNameFromUrl = mw()->permalink_manager->slug($pageUrl, 'post');
        $this->assertEquals(false, $getPageNameFromUrl);


        /**
         * TESTING POST
         */
        $postUrl = content_link(self::$postId);
        /**
         * PARSE LINK FROM THE POST URL
         */
        // Match the parse link category
        $getCategoryNameFromUrl = mw()->permalink_manager->slug($postUrl, 'category');
        $this->assertEquals(self::$categorySlug, $getCategoryNameFromUrl);

        // Match the parse link page
        $getPageNameFromUrl = mw()->permalink_manager->slug($postUrl, 'page');
        // var_dump(['post_url'=>$postUrl, 'response'=>$getPageNameFromUrl]);
        $this->assertEquals(self::$pageSlug, $getPageNameFromUrl);

        // Match the parse link post
        $getPageNameFromUrl = mw()->permalink_manager->slug($postUrl, 'post');
        $this->assertEquals(self::$postSlug, $getPageNameFromUrl);

    }

    public function testPostWithoutCategory()
    {
        // Set format
        $option = array();
        $option['option_value'] = 'category_post';
        $option['option_key'] = 'permalink_structure';
        $option['option_group'] = 'website';
        save_option($option);

        $postWithoutCategoryUrl = post_link(self::$postWithoutCategoryId);
        $postWithoutCategoryUrlExpected = site_url(self::$postWithoutCategorySlug);

        $this->assertEquals($postWithoutCategoryUrlExpected, $postWithoutCategoryUrl);

        $getPageNameFromUrl = mw()->permalink_manager->slug($postWithoutCategoryUrl, 'page');
        $this->assertEquals(self::$pageSlug, $getPageNameFromUrl);

        $getCategoryNameFromUrl = mw()->permalink_manager->slug($postWithoutCategoryUrl, 'category');
        $this->assertEquals(false, $getCategoryNameFromUrl);

    }

    public function testPost()
    {
        // Set format
        $option = array();
        $option['option_value'] = 'post';
        $option['option_key'] = 'permalink_structure';
        $option['option_group'] = 'website';
        save_option($option);

        $postUrl = post_link(self::$postId);
        $postUrlExpected = site_url(self::$postSlug);
        $this->assertEquals($postUrlExpected, $postUrl);

        $getPageNameFromUrl = mw()->permalink_manager->slug($postUrl, 'page');
        $this->assertEquals(self::$pageSlug, $getPageNameFromUrl);

        $getCategoryNameFromUrl = mw()->permalink_manager->slug($postUrl, 'category');
        $this->assertEquals(false, $getCategoryNameFromUrl);

    }

    /*  public function testFrontControllerPage()
      {
          $pageUrl = page_link(self::$pageId);

          // Set Current url for font controller
          mw()->url_manager->set_current($pageUrl);

          // Test default controller with post
          $defaultController = new FrontendController();
          $pageHtml = $defaultController->frontend();

          $this->assertEquals(self::$pageId, PAGE_ID);
          $this->assertEquals(false, CATEGORY_ID);
          $this->assertEquals(false, POST_ID);
      }*/

   /* public function testFrontControllerPost()
    {
        $postUrl = content_link(self::$postId);

        // Set Current url for font controller
        mw()->url_manager->set_current($postUrl);

        // Test default controller with post
        $defaultController = new FrontendController();
        $postHtml = $defaultController->frontend();


        $findPostSlugInPostHtml = false;
        if (strpos($postHtml, self::$postSlug) !== false) {
            $findPostSlugInPostHtml = true;
        }
        $this->assertEquals(true, $findPostSlugInPostHtml);

        $findPostUrlInPostHtml = false;
        if (strpos($postHtml, $postUrl) !== false) {
            $findPostUrlInPostHtml = true;
        }
        $this->assertEquals(true, $findPostUrlInPostHtml);

        var_dump(CATEGORY_ID);
        var_dump(PAGE_ID);
        var_dump(POST_ID);

        $this->assertEquals(self::$postId, POST_ID);
        $this->assertEquals(self::$pageId, PAGE_ID);
        $this->assertEquals(self::$categoryId, CATEGORY_ID);
    }*/

    private function _generateCategory($url, $title, $pageId)
    {

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
