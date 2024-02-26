<?php

namespace MicroweberPackages\MetaTags\tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use MicroweberPackages\Content\tests\TestHelpers;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\LiveEditWaitUntilLoaded;
use Tests\DuskTestCase;

class MetaTagsFrontendTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    use TestHelpers;


    public function testCustomHeadTagsDisplayedInFrontend()
    {
        if (app()->environment() !== 'testing') {
            $this->markTestSkipped('This test can be run only in testing environment');
            return;
        }
        $this->browse(function (Browser $browser) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

        });

        $pageId = $this->_generatePage('my-page-for-meta-tags-test', 'My page for meta tags test');

        $siteUrl = content_link($pageId);

        $contentItem = get_content_by_id($pageId);
        $this->assertEquals($contentItem['created_by'], user_id());

        $customHeadTags = '<script id="test-custom-head-tags">console.log("test custom head tags");</script>';
        $customHeadTagsFooter = '<script id="test-custom-head-tags-footer">console.log("test custom head tags footer");</script>';

        save_option('website_head', $customHeadTags, 'website');
        save_option('website_footer', $customHeadTagsFooter, 'website');
        save_option('favicon_image', site_url() . 'favicon.ico', 'website');
        save_option('google-site-verification-code', 'google-site-verification-code', 'website');
        save_option('bing-site-verification-code', 'bing-site-verification-code', 'website');
        save_option('alexa-site-verification-code', 'alexa-site-verification-code', 'website');
        save_option('pinterest-site-verification-code', 'pinterest-site-verification-code', 'website');
        save_option('yandex-site-verification-code', 'yandex-site-verification-code', 'website');


        $website_head_option = get_option('website_head', 'website');
        $website_footer_option = get_option('website_footer', 'website');

        $this->assertEquals($customHeadTags, $website_head_option);
        $this->assertEquals($customHeadTagsFooter, $website_footer_option);


        $profileUrl = 'https://www.facebook.com/microweber';
        $saveUserData = save_user([
            'id' => user_id(),
            'profile_url' => $profileUrl
        ]);

        $getUser = get_user();

        $this->assertEquals($getUser['profile_url'], $profileUrl);
        $this->assertEquals($saveUserData, user_id());


        $this->browse(function (Browser $browser) use ($siteUrl) {


            $browser->visit($siteUrl . '?editmode=n');


            $this->performMetaTagsAssertions($browser);


            $browser->visit($siteUrl . '?editmode=y');

            $browser->within(new LiveEditWaitUntilLoaded(), function (Browser $browser) {
                $browser->waitUntilLoaded();
            });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);
            $browser->assertPresent('#mw-iframe-page-data-script');

            $this->performMetaTagsAssertions($browser);

        });


    }

    private function performMetaTagsAssertions(Browser $browser)
    {
        $browser->within(new ChekForJavascriptErrors(), function ($browser) {
            $browser->validate();
        });



        $selectors = [
            'link[rel="shortcut icon"]',
            'link[rel="author"]',
            'meta[name="google-site-verification"]',
            'meta[name="msvalidate.01"]',
            'meta[name="alexaVerifyID"]',
            'meta[name="p:domain_verify"]',
            'meta[name="yandex-verification"]',
        ];
        foreach ($selectors as $selector) {
            $output = $browser->script("
            //check its only 1 of this selector
            var  isTrue = document.querySelectorAll('{$selector}').length === 1;
            return isTrue;
            ");
            $this->assertEquals(true, $output[0], 'Meta tags Selector ' . $selector . ' must be only 1');
        }

        $output = $browser->script("
            //check it must not have divs in head tags
            var  isTrue = document.querySelectorAll('head > div').length === 0;
            return isTrue;
            ");
        $this->assertEquals(true, $output[0], 'Meta tags must not have divs in head tags');


        $output = $browser->script("
            //check window.livewire exists
            var  isTrue = typeof window.livewire !== 'undefined';
            return isTrue;
            ");
        $this->assertEquals(true, $output[0], 'window.livewire must exists');

        $selectors = [

            '#test-custom-head-tags',
            '#test-custom-head-tags-footer',
            '#mw-template-settings',
            '#mw-js-core-scripts',
            '#mw-system-default-css',
            '#meta-tags-test-inserted-from-event-site_header',
            '#mw-meta-tags-test-inserted-from-template_head',
            '#mw-meta-tags-test-inserted-from-template_foot',
            '#mw-meta-tags-test-inserted-from-template_head_as_string',
            '#mw-meta-tags-test-inserted-from-template_foot_as_string',
        ];

        foreach ($selectors as $selector) {
            $browser->assertPresent($selector);


            $output = $browser->script("
            //check its only 1 of this selector
            var  isTrue = document.querySelectorAll('{$selector}').length === 1;
            return isTrue;
            ");
            $this->assertEquals(true, $output[0], 'Meta tags Selector ' . $selector . ' must be only 1');


        }


    }

}
