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
        // Extension-less names: the standalone denylist allows them, but when
        // the CMS is present the checker delegates to mw_filesystem()->
        // isAllowedFile(), which applies a strict allow-list and rejects
        // unknown/extension-less names (the safer choice for zip extraction).
        // Guard on the SAME condition the checker branches on (function_exists)
        // so the expectation matches the code path actually taken.
        if (!function_exists('mw_filesystem')) {
            $this->assertTrue($checker->isAllowed('readme'));
        }
    }
}
