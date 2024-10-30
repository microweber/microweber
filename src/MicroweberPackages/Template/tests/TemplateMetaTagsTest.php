<?php

namespace MicroweberPackages\Template\tests;

use MicroweberPackages\Template\Facades\TemplateMetaTags;
use MicroweberPackages\Core\tests\TestCase;

class TemplateMetaTagsTest extends TestCase
{
    public function testAddScript()
    {
        TemplateMetaTags::addScript('test-script', 'test.js', ['async' => true], 'head');
        $scripts = TemplateMetaTags::scripts('head');
        $this->assertStringContainsString('test.js', $scripts);
        $this->assertStringContainsString('async="1"', $scripts);
    }

    public function testRemoveScript()
    {
        TemplateMetaTags::addScript('test-script', 'test.js', ['async' => true], 'head');
        TemplateMetaTags::removeScript('test-script');
        $scripts = TemplateMetaTags::scripts('head');
        $this->assertStringNotContainsString('test.js', $scripts);
    }

    public function testAddStyle()
    {
        TemplateMetaTags::addStyle('test-style', 'test.css', ['media' => 'all'], 'head');
        $styles = TemplateMetaTags::styles('head');
        $this->assertStringContainsString('test.css', $styles);
        $this->assertStringContainsString('media="all"', $styles);
    }

    public function testRemoveStyle()
    {
        TemplateMetaTags::addStyle('test-style', 'test.css', ['media' => 'all'], 'head');
        TemplateMetaTags::removeStyle('test-style');
        $styles = TemplateMetaTags::styles('head');
        $this->assertStringNotContainsString('test.css', $styles);
    }

    public function testAddCustomHeadTag()
    {
        TemplateMetaTags::addCustomHeadTag('<meta name="description" content="Example">');
        $headTags = TemplateMetaTags::customHeadTags();
        $this->assertStringContainsString('meta name="description"', $headTags);
    }

    public function testAddCustomFooterTag()
    {
        TemplateMetaTags::addCustomFooterTag('<script src="footer.js"></script>');
        $footerTags = TemplateMetaTags::customFooterTags();
        $this->assertStringContainsString('footer.js', $footerTags);
    }

    public function testHeadTags()
    {
        TemplateMetaTags::addStyle('test-style', 'test.css', ['media' => 'all'], 'head');
        TemplateMetaTags::addScript('test-script', 'test.js', ['async' => true], 'head');
        TemplateMetaTags::addCustomHeadTag('<meta name="description" content="Example">');
        $headTags = TemplateMetaTags::headTags();
        $this->assertStringContainsString('test.css', $headTags);
        $this->assertStringContainsString('test.js', $headTags);
        $this->assertStringContainsString('meta name="description"', $headTags);
    }

    public function testFooterTags()
    {
        TemplateMetaTags::addStyle('test-style', 'test.css', ['media' => 'all'], 'footer');
        TemplateMetaTags::addScript('test-script', 'test.js', ['async' => true], 'footer');
        TemplateMetaTags::addCustomFooterTag('<script src="footer.js"></script>');
        $footerTags = TemplateMetaTags::footerTags();
        $this->assertStringContainsString('test.css', $footerTags);
        $this->assertStringContainsString('test.js', $footerTags);
        $this->assertStringContainsString('footer.js', $footerTags);
    }
}
