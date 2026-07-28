<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Tests\Unit;

use MicroweberPackages\View\Support\HtmlAttributes;
use MicroweberPackages\View\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HtmlAttributesTest extends TestCase
{
    #[Test]
    public function empty_array_returns_empty_string(): void
    {
        $this->assertSame('', HtmlAttributes::toString([]));
    }

    #[Test]
    public function encodes_key_value_pairs(): void
    {
        $out = HtmlAttributes::toString(['type' => 'logo', 'id' => 'a1']);
        $this->assertSame('type="logo" id="a1"', $out);
    }

    #[Test]
    public function escapes_special_characters(): void
    {
        $out = HtmlAttributes::toString(['title' => 'A "quote" & more']);
        $this->assertStringContainsString('&quot;', $out);
        $this->assertStringContainsString('&amp;', $out);
    }

    #[Test]
    public function boolean_true_emits_attribute_name_only(): void
    {
        $out = HtmlAttributes::toString(['disabled' => true, 'hidden' => false]);
        $this->assertSame('disabled', $out);
    }
}
