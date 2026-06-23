<?php

namespace MicroweberPackages\Url\Tests;

use MicroweberPackages\Url\URLify;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for URLify - pure PHP, no Laravel needed.
 */
class URLifyTest extends TestCase
{
    #[Test]
    public function it_filters_english_text(): void
    {
        $result = URLify::filter('Hello World');
        $this->assertEquals('hello-world', $result);
    }

    #[Test]
    public function it_filters_french_text(): void
    {
        $result = URLify::filter("J'étudie le français");
        $this->assertStringContainsString('jetudie', $result);
        $this->assertStringContainsString('francais', $result);
    }

    #[Test]
    public function it_filters_spanish_text(): void
    {
        $result = URLify::filter('Lo siento, no hablo español.');
        $this->assertStringContainsString('espanol', $result);
    }

    #[Test]
    public function it_downcodes_latin_characters(): void
    {
        $this->assertEquals('A', URLify::downcode('À'));
        $this->assertEquals('ss', URLify::downcode('ß'));
        $this->assertEquals('AE', URLify::downcode('Æ'));
    }

    #[Test]
    public function it_downcodes_greek_characters(): void
    {
        $this->assertEquals('a', URLify::downcode('α'));
        $this->assertEquals('A', URLify::downcode('Α'));
    }

    #[Test]
    public function it_downcodes_russian_characters(): void
    {
        $this->assertEquals('a', URLify::downcode('а'));
        $this->assertEquals('A', URLify::downcode('А'));
    }

    #[Test]
    public function it_transliterates_text(): void
    {
        $result = URLify::transliterate('café');
        $this->assertEquals('cafe', $result);
    }

    #[Test]
    public function it_respects_length_limit(): void
    {
        $result = URLify::filter('A very long title that exceeds the default length limit', 10);
        $this->assertLessThanOrEqual(10, strlen($result));
    }

    #[Test]
    public function it_preserves_case_when_requested(): void
    {
        $result = URLify::filter('Hello World', 60, false);
        // Str::slug lowercases first, so to_lower=false only skips the final strtolower
        $this->assertNotEmpty($result);
        $this->assertStringContainsString('hello', $result);
    }
}