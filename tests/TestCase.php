<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithConsoleEvents;
use MicroweberPackages\Core\tests\TestCase as MicroweberTestCase;

/**
 * Application test case — extends the CMS Testbench base.
 *
 * Orchestra Testbench boots the full Microweber application (via
 * MicroweberPackages\Core\tests\TestCase::applicationBasePath()).
 */
abstract class TestCase extends MicroweberTestCase
{
    use WithConsoleEvents;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
