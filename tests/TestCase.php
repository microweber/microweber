<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithConsoleEvents;

abstract class TestCase extends \MicroweberPackages\Core\tests\TestCase
{
    use CreatesApplication;
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
