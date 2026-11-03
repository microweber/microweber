<?php

declare(strict_types=1);

namespace MicroweberPackages\MetaTags\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class AutoloadTest extends TestCase
{
    public static function entityClasses(): array
    {
        return [
            [\MicroweberPackages\MetaTags\Entities\GeneratorHeadTag::class],
            [\MicroweberPackages\MetaTags\Entities\FaviconHeadTag::class],
            [\MicroweberPackages\MetaTags\Entities\CsrfTokenHeadTags::class],
            [\MicroweberPackages\MetaTags\Entities\TitleHeadTags::class],
            [\MicroweberPackages\MetaTags\FrontendMetaTagsRenderer::class],
            [\MicroweberPackages\MetaTags\AdminMetaTagsRenderer::class],
        ];
    }

    #[Test]
    #[DataProvider('entityClasses')]
    public function package_classes_are_psr4_autoloadable(string $class): void
    {
        $this->assertTrue(
            class_exists($class),
            $class . ' should autoload from src/.'
        );
    }
}
