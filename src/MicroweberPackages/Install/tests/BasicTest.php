<?php
namespace MicroweberPackages\Install\tests;

use PHPUnit\Framework\Attributes\Test;

use MicroweberPackages\Core\tests\TestCase;

class BasicTest extends TestCase
{
    #[Test]
    public function it_basic(): void {
        $this->assertTrue(true);
    }
}
