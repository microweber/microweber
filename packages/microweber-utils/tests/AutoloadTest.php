<?php

declare(strict_types=1);

namespace MicroweberPackages\Utils\Tests;

use PHPUnit\Framework\Attributes\Test;

class AutoloadTest extends TestCase
{
    #[Test]
    public function package_classes_are_psr4_autoloadable(): void
    {
        $this->assertTrue(
            class_exists(\MicroweberPackages\Utils\Misc\Sitemap::class),
            'Sitemap should autoload from src/.'
        );
        $this->assertTrue(
            class_exists(\MicroweberPackages\Utils\Misc\ContentExport::class),
            'ContentExport should autoload from src/.'
        );
        $this->assertTrue(
            class_exists(\MicroweberPackages\Utils\Mail\MailSubscriber::class),
            'MailSubscriber should autoload from src/.'
        );
    }
}
