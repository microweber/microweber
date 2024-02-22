<?php

namespace MicroweberPackages\MetaTags\tests\Browser;

use MicroweberPackages\Content\tests\TestHelpers;
use Tests\DuskTestCase;

class MetaTagsFrontendTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    use TestHelpers;


    public function testCustomHeadTagsDisplayedInFrontend()
    {
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

        $this->browse(function ($browser) use ($siteUrl, $customHeadTags, $customHeadTagsFooter) {
            $browser->visit($siteUrl);


            $browser->assertPresent('#test-custom-head-tags');
            $browser->assertPresent('#test-custom-head-tags-footer');
            $browser->assertPresent('#mw-template-settings');
            $browser->assertPresent('#mw-js-core-scripts');
            $browser->assertPresent('#mw-system-default-css');

        });




    }

}
