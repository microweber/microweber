<?php
namespace MicroweberPackages\Queue\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class BasicTest extends TestCase
{
    #[Test]
    public function it_basic(): void {
        $this->assertTrue(true);
    }
}
