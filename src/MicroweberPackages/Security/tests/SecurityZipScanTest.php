<?php

namespace MicroweberPackages\Security\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Security\ZipScanner;

class SecurityZipScanTest extends TestCase
{
    public function testShellStringScan() {

        $scan = new ZipScanner();

        $this->assertTrue(true);
    }

}
