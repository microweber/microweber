<?php
namespace MicroweberPackages\View\tests;

use MicroweberPackages\Core\tests\TestCase;

class BasicTest extends TestCase
{
    public function testConstructorThrowsExceptionForNonexistentFile()
    {
        $this->expectException(\Exception::class);

        new \MicroweberPackages\View\View('/path/to/nonexistent/file');
    }
}
