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

    public function test_preserves_calc_spaces(): void
    {
        // The spaces around the operator in calc() are significant — dropping them
        // produces invalid CSS (calc(100%-10px)).
        $minified = CssMinifier::minify('.a{width:calc(100% - 10px)}');
        $this->assertStringContainsString('calc(100% - 10px)', $minified);
    }

    public function test_does_not_strip_percent_from_zero(): void
    {
        // 0% must survive inside color functions (hsl saturation/lightness) where
        // a bare 0 is invalid.
        $minified = CssMinifier::minify('.a{color:hsl(120, 0%, 50%)}');
        $this->assertStringContainsString('0%', $minified);
        $this->assertStringContainsString('50%', $minified);
    }

    public function test_does_not_glue_tokens_across_newlines(): void
    {
        // A value split across lines must not become "10px10px".
        $minified = CssMinifier::minify(".a{margin:10px\n10px}");
        $this->assertStringNotContainsString('10px10px', $minified);
        $this->assertStringContainsString('10px 10px', $minified);
    }

    public function test_keeps_brace_balance(): void
    {
        $css = ".a{color:red}\n.b{color:blue}\n.c{color:green}";
        $minified = CssMinifier::minify($css);
        $this->assertSame(substr_count($css, '{'), substr_count($minified, '{'));
        $this->assertSame(substr_count($css, '}'), substr_count($minified, '}'));
    }

    public function test_service_minify(): void
    {
        $service = new CssMinify();
        $result = $service->minify('.a { color: #fff; }');
        $this->assertStringContainsString('color:#fff', $result);
    }
}
