<?php
namespace MicroweberPackages\View\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class BasicTest extends TestCase
{
    #[Test]
    public function it_constructor_throws_exception_for_nonexistent_file(): void {
        $this->expectException(\Exception::class);

        new \MicroweberPackages\View\View('/path/to/nonexistent/file');
    }
}
