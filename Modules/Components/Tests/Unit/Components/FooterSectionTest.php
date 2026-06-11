<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class FooterSectionTest extends TestCase
{
    #[Test]
    public function it_renders_the_footer_container_with_a_default_copyright_and_powered_by(): void
    {
        $output = Blade::render('<x-footer-section />');

        $this->assertStringContainsString('mw-layout-container', $output);
        $this->assertStringContainsString('All Rights Reserved', $output);
        // powered_by_link() renders the "Microweber" credit link
        $this->assertStringContainsString('href', $output);
    }

    #[Test]
    public function it_renders_a_custom_copyright_slot_over_the_default(): void
    {
        $output = Blade::render('<x-footer-section>My custom footer</x-footer-section>');

        $this->assertStringContainsString('My custom footer', $output);
        $this->assertStringNotContainsString('All Rights Reserved', $output);
    }

    #[Test]
    public function it_wires_the_editable_field_when_a_copyright_field_is_given(): void
    {
        $output = Blade::render('<x-footer-section copyright-field="footer-copy" section-id="42" />');

        $this->assertStringContainsString('field="footer-copy-42"', $output);
        $this->assertStringContainsString('rel="module"', $output);
    }
}
