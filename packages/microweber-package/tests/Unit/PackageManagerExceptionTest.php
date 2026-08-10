<?php

declare(strict_types=1);

namespace MicroweberPackages\Package\Tests\Unit;

use MicroweberPackages\Package\PackageManagerException;
use MicroweberPackages\Package\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PackageManagerExceptionTest extends TestCase
{
    #[Test]
    public function package_name_exception_has_message(): void
    {
        $e = PackageManagerException::packageNameIsRequired();
        $this->assertInstanceOf(PackageManagerException::class, $e);
        $this->assertStringContainsString('name', $e->getMessage());
    }

    #[Test]
    public function module_type_exception_has_message(): void
    {
        $e = PackageManagerException::moduleTypeIsRequired();
        $this->assertInstanceOf(PackageManagerException::class, $e);
        $this->assertStringContainsString('type', $e->getMessage());
    }
}
