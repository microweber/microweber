<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExamplePestTest extends TestCase
{
    public function test_basic_assertion(): void
    {
        $this->assertTrue(true);
    }

    public function test_basic_arithmetic(): void
    {
        $this->assertEquals(4, 2 + 2);
    }

    public function test_string_contains(): void
    {
        $this->assertStringContainsString('weber', 'microweber');
    }
}
