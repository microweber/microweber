<?php

declare(strict_types=1);

namespace MicroweberPackages\LaravelHelperFunctions\Tests;

use PHPUnit\Framework\Attributes\Test;

class HelperFunctionsTest extends TestCase
{
    #[Test]
    public function global_helper_functions_are_autoloaded(): void
    {
        $this->assertTrue(function_exists('str_random'), 'str_random() should be autoloaded via composer files.');
        $this->assertTrue(function_exists('strleft'), 'strleft() should be autoloaded via composer files.');
        $this->assertTrue(function_exists('character_limiter'), 'character_limiter() should be autoloaded via composer files.');
    }

    #[Test]
    public function str_random_returns_string_of_requested_length(): void
    {
        $this->assertSame(16, strlen(str_random(16)));
        $this->assertSame(40, strlen(str_random()), 'default length should be 40');
    }

    #[Test]
    public function strleft_returns_substring_before_needle(): void
    {
        $this->assertSame('hello', strleft('hello.world', '.'));
        $this->assertSame('a', strleft('a/b/c', '/'));
    }

    #[Test]
    public function character_limiter_returns_short_strings_unchanged(): void
    {
        $this->assertSame('short text', character_limiter('short text', 500));
    }
}
