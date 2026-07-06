<?php

namespace MicroweberPackages\Filesystem\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use MicroweberPackages\Filesystem\FilesystemService;

abstract class TestCase extends PHPUnitTestCase
{
    protected FilesystemService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FilesystemService();
    }
}
