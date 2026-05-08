<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LiveEditMobileRightRailCssContractTest extends TestCase
{
    #[Test]
    public function mobile_right_rail_uses_in_view_geometry_instead_of_off_canvas_transforms(): void
    {
        $content = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/css/index.css'
        ));

        $this->assertStringContainsString('@media (max-width: 767px) {', $content);
        $this->assertStringContainsString('right: 0 !important;', $content);
        $this->assertStringContainsString('transform: none !important;', $content);
    }

    #[Test]
    public function mobile_right_rail_hides_inactive_panels_without_leaving_them_off_screen(): void
    {
        $content = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/css/index.css'
        ));

        $this->assertStringContainsString('html .mw-control-box-right {', $content);
        $this->assertStringContainsString('visibility: hidden;', $content);
        $this->assertStringContainsString('pointer-events: none;', $content);
        $this->assertStringContainsString('html .mw-control-box-right.active {', $content);
        $this->assertStringContainsString('visibility: visible;', $content);
        $this->assertStringContainsString('pointer-events: auto;', $content);
    }
}
