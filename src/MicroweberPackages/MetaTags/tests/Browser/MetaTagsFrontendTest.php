<?php

namespace MicroweberPackages\MetaTags\tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use MicroweberPackages\Content\tests\TestHelpers;
use Tests\Browser\Components\AdminLogin;
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


        $pageId = $this->_generatePage('my-page-for-meta-tags-test', 'My page for meta tags test');

        $siteUrl = content_link($pageId);

        $customHeadTags = '<script id="test-custom-head-tags">console.log("test custom head tags");</script>';
        $customHeadTagsFooter = '<script id="test-custom-head-tags-footer">console.log("test custom head tags footer");</script>';

        save_option('website_head', $customHeadTags, 'website');
        save_option('website_footer', $customHeadTagsFooter, 'website');

        $website_head_option = get_option('website_head', 'website');
        $website_footer_option = get_option('website_footer', 'website');

        $this->assertEquals($customHeadTags, $website_head_option);
        $this->assertEquals($customHeadTagsFooter, $website_footer_option);


        $this->browse(function ($browser) use ($siteUrl) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->visit($siteUrl . '?editmode=n');


            $this->performMetaTagsAssertions($browser);



            $browser->visit($siteUrl . '?editmode=y');

            $browser->within(new LiveEditWaitUntilLoaded(), function ($browser) {
                $browser->waitUntilLoaded();
            });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);
            $browser->assertPresent('#mw-iframe-page-data-script');

            $this->performMetaTagsAssertions($browser);

        });


    }

    private function performMetaTagsAssertions($browser)
    {
        $browser->assertPresent('#test-custom-head-tags');
        $browser->assertPresent('#test-custom-head-tags-footer');
        $browser->assertPresent('#mw-template-settings');
        $browser->assertPresent('#mw-js-core-scripts');
        $browser->assertPresent('#mw-system-default-css');

        $browser->assertPresent('#meta-tags-test-inserted-from-event-site_header');

        $browser->assertPresent('#mw-meta-tags-test-inserted-from-template_head');
        $browser->assertPresent('#mw-meta-tags-test-inserted-from-template_foot');
    }

}
