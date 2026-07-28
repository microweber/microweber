<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Unit;

use MicroweberPackages\Minifier\Minifiers\CssMinifier;
use MicroweberPackages\Minifier\Services\CssMinify;
use MicroweberPackages\Minifier\Tests\TestCase;

class CssMinifyTest extends TestCase
{
    public function test_removes_comments_and_whitespace(): void
    {
        $css = <<<'CSS'
/* This is a comment */
.test-class {
    color: red;
    background: blue;
    margin: 10px 20px;
    padding: 0;
}

.another-class {
    font-size: 14px;
}
CSS;

        $minified = CssMinifier::minify($css);

        $this->assertStringNotContainsString('/* This is a comment */', $minified);
        $this->assertStringContainsString('.test-class', $minified);
        $this->assertStringContainsString('color:red', $minified);
        $this->assertLessThan(strlen($css), strlen($minified));
    }

    public function test_preserves_quoted_strings(): void
    {
        $css = '.a{content:"/* not a comment */";}';
        $minified = CssMinifier::minify($css);
        $this->assertStringContainsString('/* not a comment */', $minified);
    }

    public function test_shortens_zero_values(): void
    {
        $css = '.a{margin:0px;}';
        $minified = CssMinifier::minify($css, ['shorten_zeros' => true]);
        $this->assertStringContainsString('margin:0', $minified);
        $this->assertStringNotContainsString('0px', $minified);
    }

    public function test_empty_input(): void
    {
        $service = new CssMinify();
        $this->assertSame('', $service->minify(''));
    }

    public function test_service_minify(): void
    {
        $service = new CssMinify();
        $result = $service->minify('.a { color: #fff; }');
        $this->assertStringContainsString('color:#fff', $result);
    }
}
