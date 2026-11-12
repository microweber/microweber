<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * icon_html() maps Font Awesome 5/6 icon names to their FA4 equivalent, because
 * the site ships FA 4.7 — an unmapped FA5 name (e.g. "fa-chart-line") renders as
 * an empty box. AI agents frequently emit FA5 names, so this guard keeps their
 * icons visible. Regression for the agent-built mega-menu "missing icon" bug.
 */
class IconHtmlFa5AliasTest extends TestCase
{
    #[Test]
    public function it_maps_common_fa5_names_to_fa4(): void
    {
        // FA5 name => the FA4 glyph class it must render as.
        $cases = [
            'fa-chart-line' => 'fa-line-chart',
            'fa-chart-bar' => 'fa-bar-chart',
            'fa-shield-alt' => 'fa-shield',
            'fa-xmark' => 'fa-times',
            'fa-house' => 'fa-home',
            'fa-gear' => 'fa-cog',
            'fa-magnifying-glass' => 'fa-search',
            'fa-pen-to-square' => 'fa-pencil-square-o',
        ];

        foreach ($cases as $fa5 => $fa4) {
            $html = icon_html($fa5);
            $this->assertStringContainsString(
                'fa ' . $fa4 . '"',
                $html,
                "icon_html('{$fa5}') must render the FA4 class '{$fa4}', got: {$html}"
            );
            $this->assertStringNotContainsString(
                $fa5 . '"',
                $html,
                "icon_html('{$fa5}') must not emit the unmapped FA5 class."
            );
        }
    }

    #[Test]
    public function it_passes_through_valid_fa4_and_unknown_names(): void
    {
        // A name already valid in FA4 is left untouched.
        $this->assertSame('<i class="fa fa-bolt"></i>', icon_html('fa-bolt'));
        $this->assertSame('<i class="fa fa-rocket"></i>', icon_html('fa-rocket'));
        // An unknown name passes through (better a wrong-but-present class than
        // silently dropping the icon).
        $this->assertSame('<i class="fa fa-totally-made-up"></i>', icon_html('fa-totally-made-up'));
    }
}
