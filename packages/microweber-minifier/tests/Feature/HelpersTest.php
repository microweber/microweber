<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Tests\Feature;

use MicroweberPackages\Minifier\Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_js_minify_helper_exists_and_works(): void
    {
        $this->assertTrue(function_exists('js_minify'));
        $result = js_minify("function a(){ return 1; }");
        $this->assertIsString($result);
        $this->assertStringContainsString('function', $result);
    }

    public function test_css_minify_helper_exists_and_works(): void
    {
        $this->assertTrue(function_exists('css_minify'));
        $result = css_minify(".a { color: red; }");
        $this->assertStringContainsString('color:red', $result);
    }

    public function test_aliases_exist(): void
    {
        $this->assertTrue(function_exists('minify_js'));
        $this->assertTrue(function_exists('minify_css'));
        $this->assertSame(js_minify('var x=1;'), minify_js('var x=1;'));
    }

    public function test_stats_and_enabled_helpers(): void
    {
        $this->assertTrue(function_exists('minifier_stats'));
        $this->assertTrue(function_exists('minifier_enabled'));
        $stats = minifier_stats();
        $this->assertIsArray($stats);
        $this->assertIsBool(minifier_enabled());
    }
}
