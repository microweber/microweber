<?php

namespace MicroweberPackages\Security\Tests;

use MicroweberPackages\Security\HtmlSanitizer\MwHtmlSanitizerReference;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerReferenceTest extends TestCase
{
    #[Test]
    public function it_has_not_allowed_attributes(): void
    {
        $attributes = MwHtmlSanitizerReference::getNotAllowedAttributes();
        $this->assertIsArray($attributes);
        $this->assertContains('onclick', $attributes);
        $this->assertContains('onerror', $attributes);
        $this->assertContains('onload', $attributes);
        $this->assertContains('onmousedown', $attributes);
    }

    #[Test]
    public function it_has_mw_elements(): void
    {
        $this->assertArrayHasKey('module', MwHtmlSanitizerReference::MW_ELEMENTS);
        $this->assertArrayHasKey('div', MwHtmlSanitizerReference::MW_ELEMENTS);
    }

    #[Test]
    public function it_has_mw_attributes(): void
    {
        $this->assertArrayHasKey('id', MwHtmlSanitizerReference::MW_ATTRIBUTES);
        $this->assertArrayHasKey('class', MwHtmlSanitizerReference::MW_ATTRIBUTES);
        $this->assertArrayHasKey('style', MwHtmlSanitizerReference::MW_ATTRIBUTES);
        $this->assertArrayHasKey('type', MwHtmlSanitizerReference::MW_ATTRIBUTES);
    }
}