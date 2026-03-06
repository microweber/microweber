<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;

use MicroweberPackages\Template\Facades\TemplateMetaTags;
use Tests\TestCase;

class TemplateMetaTagsTest extends TestCase
{
    #[Test]
    public function it_add_script(): void {
        TemplateMetaTags::addScript('test-script', 'test.js', ['async' => true], 'head');
        $scripts = TemplateMetaTags::scripts('head');
        $this->assertStringContainsString('test.js', $scripts);
        $this->assertStringContainsString('async="1"', $scripts);
    }

    #[Test]

    public function it_remove_script(): void {
        TemplateMetaTags::addScript('test-script', 'test.js', ['async' => true], 'head');
        TemplateMetaTags::removeScript('test-script');
        $scripts = TemplateMetaTags::scripts('head');
        $this->assertStringNotContainsString('test.js', $scripts);
    }

    #[Test]

    public function it_add_style(): void {
        TemplateMetaTags::addStyle('test-style', 'test.css', ['media' => 'all'], 'head');
        $styles = TemplateMetaTags::styles('head');
        $this->assertStringContainsString('test.css', $styles);
        $this->assertStringContainsString('media="all"', $styles);
    }

    #[Test]

    public function it_remove_style(): void {
        TemplateMetaTags::addStyle('test-style', 'test.css', ['media' => 'all'], 'head');
        TemplateMetaTags::removeStyle('test-style');
        $styles = TemplateMetaTags::styles('head');
        $this->assertStringNotContainsString('test.css', $styles);
    }

    #[Test]

    public function it_add_custom_head_tag(): void {
        TemplateMetaTags::addCustomHeadTag('<meta name="description" content="Example">');
        $headTags = TemplateMetaTags::customHeadTags();
        $this->assertStringContainsString('meta name="description"', $headTags);
    }

    #[Test]

    public function it_add_custom_footer_tag(): void {
        TemplateMetaTags::addCustomFooterTag('<script src="footer.js"></script>');
        $footerTags = TemplateMetaTags::customFooterTags();
        $this->assertStringContainsString('footer.js', $footerTags);
    }

    #[Test]

    public function it_head_tags(): void {
        TemplateMetaTags::addStyle('test-style', 'test.css', ['media' => 'all'], 'head');
        TemplateMetaTags::addScript('test-script', 'test.js', ['async' => true], 'head');
        TemplateMetaTags::addCustomHeadTag('<meta name="description" content="Example">');
        $headTags = TemplateMetaTags::headTags();
        $this->assertStringContainsString('test.css', $headTags);
        $this->assertStringContainsString('test.js', $headTags);
        $this->assertStringContainsString('meta name="description"', $headTags);
    }

    #[Test]

    public function it_footer_tags(): void {
        TemplateMetaTags::addStyle('test-style', 'test.css', ['media' => 'all'], 'footer');
        TemplateMetaTags::addScript('test-script', 'test.js', ['async' => true], 'footer');
        TemplateMetaTags::addCustomFooterTag('<script src="footer.js"></script>');
        $footerTags = TemplateMetaTags::footerTags();
        $this->assertStringContainsString('test.css', $footerTags);
        $this->assertStringContainsString('test.js', $footerTags);
        $this->assertStringContainsString('footer.js', $footerTags);
    }
}
