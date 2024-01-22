<?php

namespace MicroweberPackages\Multilanguage\tests;

use \MicroweberPackages\Multilanguage\MultilanguageApi;

/**
 * @runTestsInSeparateProcesses
 */
class MultilanguageTest extends MultilanguageTestBase
{

    public function testSupportedLanguages()
    {
       // \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(1);
        $lang = app()->getLocale();

        add_supported_language($lang, 'English');

        // Set default lang
      //  $lang = 'en_US';
        $option = array();
        $option['option_value'] = $lang;
        $option['option_key'] = 'language';
        $option['option_group'] = 'website';

        save_option($option);

        mw()->lang_helper->set_current_lang($lang);
        $this->assertEquals($lang, mw()->lang_helper->current_lang());

        $languages = get_supported_languages();

        // Check default lang is exists on supported languages
        $locales = array();
        foreach ($languages as $language) {
            $locales[] = $language['locale'];
        }
        $default_lang = mw()->lang_helper->default_lang();

        $this->assertEquals(true, in_array($default_lang, $locales));
        $this->assertEquals(true, is_lang_supported($lang));
        $this->assertEquals(true, is_lang_correct($lang));





    }

    public function testAddNewLanguage()
    {

        $locale = 'bg_BG';
        $language = 'Bulgarian';

        // Add language
        add_supported_language($locale, $language);

        $languages = get_supported_languages();

        $locales = array();
        foreach ($languages as $language) {
            $locales[] = $language['locale'];
        }

        $this->assertEquals(true, in_array($locale, $locales));

    }

    public function testSwitchLanguage()
    {
        mw()->lang_helper->set_current_lang('bg_BG');
        $this->assertEquals('bg_BG', mw()->lang_helper->current_lang());
    }

    public function testTranslateNewOption()
    {
        $this->_addNewMultilanguageOption('bozhidar', 'Bozhidar', 'Божидар');
        $this->_addNewMultilanguageOption('slaveykov', 'Slaveykov', 'Славейков');
        $this->_addNewMultilanguageOption('health', 'Health', 'Здраве');
        $this->_addNewMultilanguageOption('love', 'Love', 'Любов');
        $this->_addNewMultilanguageOption('happy', 'Happy', 'Щастие');
        $this->_addNewMultilanguageOption('rich', 'Rich', 'Богат');
    }

    private function _addNewMultilanguageOption($option_key, $en_option_value, $bg_option_value)
    {

        // Switch to english language
        mw()->lang_helper->set_current_lang('en_US');
        $this->assertEquals('en_US', mw()->lang_helper->current_lang());

        $option_group = 'new_option_test';

        // Add new english option
        $option = array();
        $option['option_value'] = $en_option_value;
        $option['option_key'] = $option_key;
        $option['option_group'] = $option_group;
        save_option($option);
        // Get option
        $this->assertEquals($en_option_value, get_option($option_key, $option_group));

        /**
         * TEST BULGARIAN LANGUAGE
         * Switch to bulgarian language
         */
        mw()->lang_helper->set_current_lang('bg_BG');
        $this->assertEquals('bg_BG', mw()->lang_helper->current_lang());

        // Update english option
        $option = array();
        $option['option_value'] = $bg_option_value;
        $option['option_key'] = $option_key;
        $option['option_group'] = $option_group;
        save_option($option);
        // Get bg option
        $this->assertEquals($bg_option_value, get_option($option_key, $option_group));

    }

    public function testTranslateNewMenu()
    {

        // Switch to english language
        mw()->lang_helper->set_current_lang('en_US');
        $this->assertEquals('en_US', mw()->lang_helper->current_lang());

        $menu = array();
        $menu['title'] = 'Richest people in the world';
        $menu['url'] = 'richest-people-in-the-world';
        mw()->menu_manager->menu_create($menu);

        $get_menu = mw()->menu_manager->get_menu('url=richest-people-in-the-world&single=1');

        $this->assertEquals($get_menu['title'], $menu['title']);
        $this->assertEquals($get_menu['url'], $menu['url']);


        /**
         * TEST BULGARIAN LANGUAGE
         * Switch to bulgarian language
         */
        mw()->lang_helper->set_current_lang('bg_BG');
        $this->assertEquals('bg_BG', mw()->lang_helper->current_lang());

        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'bg_BG'
        ]);

        $update = array();
        $update['menu_id'] = $get_menu['id'];
        $update['title'] = 'Най-богатите хора в света';
        $update['url'] = 'nai-bogatite-xora-v-sveta';

        mw()->menu_manager->menu_create($update);

        $get_menu = mw()->menu_manager->get_menu('id=' . $get_menu['id'] . '&single=1');

        $this->assertEquals($get_menu['title'], $update['title']);
        $this->assertEquals($get_menu['url'], $update['url']);

    }

    public function testDetectLangFromUrl()
    {
        $url = 'bg_BG/tova-e-strahotniq-post.html';
        $detect = detect_lang_from_url($url);

        $this->assertEquals('bg_BG', $detect['target_lang']);
        $this->assertEquals('tova-e-strahotniq-post.html', $detect['target_url']);

        $url = 'en_US/tova-e-strahotniq-post.html';
        $detect = detect_lang_from_url($url);

        $this->assertEquals('en_US', $detect['target_lang']);
        $this->assertEquals('tova-e-strahotniq-post.html', $detect['target_url']);

        $url = 'one-level/two-level/three-level/tova-e-strahotniq-post.html';
        $detect = detect_lang_from_url($url);

        $this->assertEquals('en_US', $detect['target_lang']);
        $this->assertEquals('one-level/two-level/three-level/tova-e-strahotniq-post.html', $detect['target_url']);

    }

    public function testCheckLanguageIsCorrect()
    {
        $check = is_lang_correct('en_US');
        $this->assertEquals(true, $check);

        $check = is_lang_correct('bg_BG');
        $this->assertEquals(true, $check);

        $check = is_lang_correct('mnogokesh');
        $this->assertEquals(false, $check);
    }

    public function testMultilanguageApi()
    {
        // Ad Greek
        $api = new MultilanguageApi();
        $output = $api->addLanguage([
            'locale' => 'el_GR',
            'language' => 'Greek'
        ]);

        $this->assertEquals(true, is_int($output));

        $languages = get_supported_languages();

        // Check default lang is exists on supported languages
        $locales = array();
        foreach ($languages as $language) {
            $locales[] = $language['locale'];
        }

        $this->assertEquals(true, in_array('el_GR', $locales));

        // Delete greek
        $output = $api->deleteLanguage(['id' => $output]);

        $languages = get_supported_languages();

        // Check default lang is exists on supported languages
        $locales = array();
        foreach ($languages as $language) {
            $locales[] = $language['locale'];
        }

        $this->assertEquals(false, in_array('el_GR', $locales));
    }

    public function testChangeLanguageApi()
    {
        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'bobi-money'
        ]);

        $this->assertEquals(true, is_string($output['error']));

        $output = $api->changeLanguage([
            'locale'=> 'bg_BG'
        ]);

        $this->assertEquals(true, $output['refresh']);
    }

    private function aaaatestGetContentFunction()
    {

        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'en_EN'
        ]);

        $get = get_content('title=ml-test-page-1&single=1');
        if (empty($get)) {
            save_content([
                'title'=>'ml-test-page-1',
                'description' => 'ml-test-description-1',
                'content_body' => 'ml-test-content-body-1',
            ]);
            $get = get_content('title=ml-test-page-1&single=1');
        }

        $this->assertEquals('ml-test-page-1', $get['title']);
        $this->assertEquals('ml-test-description-1', $get['description']);
        $this->assertEquals('ml-test-content-body-1', $get['content_body']);

        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'bg_BG'
        ]);

        save_content([
            'id'=>$get['id'],
            'title'=>'bg-ml-test-page-1',
            'description' => 'bg-ml-test-description-1',
            'content_body' => 'bg-ml-test-content-body-1',
        ]);
        $get2 = get_content('id='.$get['id'].'&single=1');

        $this->assertEquals($get['id'], $get2['id']);
        $this->assertEquals('bg-ml-test-page-1', $get2['title']);
        $this->assertEquals('bg-ml-test-description-1', $get2['description']);
        $this->assertEquals('bg-ml-test-content-body-1', $get2['content_body']);
    }

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
