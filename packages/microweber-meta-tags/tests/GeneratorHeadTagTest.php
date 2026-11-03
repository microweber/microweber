<?php

declare(strict_types=1);

namespace MicroweberPackages\MetaTags\Tests;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;
use MicroweberPackages\MetaTags\Entities\GeneratorHeadTag;
use PHPUnit\Framework\Attributes\Test;

class GeneratorHeadTagTest extends TestCase
{
    #[Test]
    public function it_implements_the_butschster_tag_contract(): void
    {
        $tag = new GeneratorHeadTag();

        $this->assertInstanceOf(TagInterface::class, $tag);
        $this->assertInstanceOf(\Stringable::class, $tag);
    }

    #[Test]
    public function placement_is_the_document_head(): void
    {
        $tag = new GeneratorHeadTag();

        $this->assertSame(Meta::PLACEMENT_HEAD, $tag->getPlacement());
    }

    #[Test]
    public function to_array_exposes_the_tag_type(): void
    {
        $tag = new GeneratorHeadTag();

        $this->assertSame(['type' => 'generator_head_tag'], $tag->toArray());
    }

    #[Test]
    public function renders_empty_without_the_mw_version_constant(): void
    {
        // Outside the CMS runtime MW_VERSION is undefined, so the generator
        // meta tag renders to an empty string rather than fataling.
        $tag = new GeneratorHeadTag();

        $this->assertSame('', $tag->toHtml());
        $this->assertSame('', (string) $tag);
    }
}
