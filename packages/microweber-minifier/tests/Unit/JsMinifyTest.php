<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Unit;

use MicroweberPackages\Minifier\Minifiers\JsMinifier;
use MicroweberPackages\Minifier\Services\JsMinify;
use MicroweberPackages\Minifier\Tests\TestCase;

class JsMinifyTest extends TestCase
{
    public function test_minifies_simple_function(): void
    {
        $js = <<<'JS'
// This is a comment
function testFunction() {
    var x = 1;
    var y = 2;
    return x + y;
}

var result = testFunction();
JS;

        $minified = JsMinifier::minify($js, ['flaggedComments' => false]);

        $this->assertIsString($minified);
        $this->assertStringNotContainsString('// This is a comment', $minified);
        $this->assertStringContainsString('function', $minified);
        $this->assertLessThan(strlen($js), strlen($minified));
    }

    public function test_preserves_string_contents(): void
    {
        $js = "var s = 'hello // not a comment';";
        $minified = JsMinifier::minify($js, ['flaggedComments' => false]);
        $this->assertStringContainsString('hello // not a comment', $minified);
    }

    public function test_handles_regex_literals(): void
    {
        $js = "var r = /test\\/path/g; var x = 1;";
        $minified = JsMinifier::minify($js, ['flaggedComments' => false]);
        $this->assertStringContainsString('/test\\/path/g', $minified);
    }

    public function test_handles_template_literals(): void
    {
        $js = "var t = `hello \${name}`;";
        $minified = JsMinifier::minify($js, ['flaggedComments' => false]);
        $this->assertStringContainsString('`hello', $minified);
    }

    public function test_empty_input(): void
    {
        $service = new JsMinify();
        $this->assertSame('', $service->minify(''));
    }

    public function test_service_does_not_throw_on_bad_input(): void
    {
        $service = new JsMinify();
        // Unclosed string would throw from JsMinifier; service should return original
        $bad = "var x = 'unclosed";
        $result = $service->minify($bad);
        $this->assertSame($bad, $result);
    }

    public function test_does_not_infinite_loop_on_large_script(): void
    {
        $parts = [];
        for ($i = 0; $i < 200; $i++) {
            $parts[] = "function f{$i}(a,b){return a+b+{$i};}";
        }
        $js = implode("\n", $parts);

        $start = microtime(true);
        $minified = JsMinifier::minify($js, ['flaggedComments' => false]);
        $elapsed = microtime(true) - $start;

        $this->assertLessThan(5.0, $elapsed, 'Minification took too long — possible infinite loop');
        $this->assertStringContainsString('function f0', $minified);
        $this->assertStringContainsString('function f199', $minified);
    }

    public function test_return_regex_pattern(): void
    {
        $js = "function a(){return /abc/;}";
        $minified = JsMinifier::minify($js, ['flaggedComments' => false]);
        $this->assertStringContainsString('/abc/', $minified);
    }

    public function test_plus_plus_lock(): void
    {
        $js = "var x=1; x + ++y;";
        $minified = JsMinifier::minify($js, ['flaggedComments' => false]);
        $this->assertStringContainsString('+', $minified);
    }

    public function test_flagged_comments_option(): void
    {
        $js = "/*! license */\nvar x=1;";
        $with = JsMinifier::minify($js, ['flaggedComments' => true]);
        $without = JsMinifier::minify($js, ['flaggedComments' => false]);
        $this->assertStringContainsString('license', $with);
        $this->assertStringNotContainsString('license', $without);
    }

    public function test_multiline_comment_removed(): void
    {
        $js = "/* multi\nline */var x=1;";
        $minified = JsMinifier::minify($js, ['flaggedComments' => false]);
        $this->assertStringNotContainsString('multi', $minified);
        $this->assertStringContainsString('var x', $minified);
    }
}
