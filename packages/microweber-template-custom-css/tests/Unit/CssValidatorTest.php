<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Unit;

use MicroweberPackages\TemplateCustomCss\Exceptions\InvalidCssException;
use MicroweberPackages\TemplateCustomCss\Services\CssValidator;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

class CssValidatorTest extends TestCase
{
    public function test_valid_css_passes(): void
    {
        $v = new CssValidator(true);
        $result = $v->validate('.hero { color: #ff0000; background: url("../../media/x.png"); }');
        $this->assertTrue($result['valid']);
        $this->assertSame([], $result['errors']);
        $this->assertTrue($v->isValid('body { margin: 0; }'));
    }

    public function test_empty_css_allowed_by_default(): void
    {
        $v = new CssValidator(true);
        $this->assertTrue($v->validate('')['valid']);
        $this->assertTrue($v->validate('   ')['valid']);
    }

    public function test_empty_css_rejected_when_disallowed(): void
    {
        $v = new CssValidator(false);
        $result = $v->validate('');
        $this->assertFalse($result['valid']);
        $this->assertNotEmpty($result['errors']);
    }

    public function test_broken_css_fails(): void
    {
        $v = new CssValidator(true);
        // Unclosed block / token errors
        $result = $v->validate('.broken { color: ');
        $this->assertFalse($result['valid']);
        $this->assertNotEmpty($result['errors']);
    }

    public function test_assert_valid_throws(): void
    {
        $v = new CssValidator(true);
        $this->expectException(InvalidCssException::class);
        $v->assertValid('.x { color: ');
    }

    public function test_assert_valid_passes_for_good_css(): void
    {
        $v = new CssValidator(true);
        $v->assertValid('a:hover { text-decoration: underline; }');
        $this->assertTrue(true);
    }

    public function test_css_variables_and_media_queries(): void
    {
        $v = new CssValidator(true);
        $css = <<<'CSS'
:root {
  --mw-primary: #0d6efd;
}
@media (max-width: 768px) {
  .hero { font-size: 14px; }
}
CSS;
        $this->assertTrue($v->isValid($css));
    }
}
