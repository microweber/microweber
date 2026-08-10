<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Tests;

use MicroweberPackages\Zip\Support\FilesystemAllowanceChecker;
use PHPUnit\Framework\Attributes\Test;

class FilesystemAllowanceCheckerTest extends TestCase
{
    #[Test]
    public function it_blocks_dangerous_extensions_standalone(): void
    {
        $checker = new FilesystemAllowanceChecker();

        $this->assertFalse($checker->isAllowed('shell.php'));
        $this->assertFalse($checker->isAllowed('bin/run.exe'));
        $this->assertFalse($checker->isAllowed('.htaccess'));
    }

    #[Test]
    public function it_allows_safe_extensions(): void
    {
        $checker = new FilesystemAllowanceChecker();

        $this->assertTrue($checker->isAllowed('photo.jpg'));
        $this->assertTrue($checker->isAllowed('doc.pdf'));
        $this->assertTrue($checker->isAllowed('data.json'));
        // Extension-less names: standalone denylist allows them; when the CMS
        // mw_filesystem() is bound it applies its own allow-list (which may reject).
        if (!function_exists('mw_filesystem') || !app()->bound(\MicroweberPackages\Filesystem\FilesystemService::class)) {
            $this->assertTrue($checker->isAllowed('readme'));
        }
    }
}
