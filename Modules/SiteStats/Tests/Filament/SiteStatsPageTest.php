<?php

namespace Modules\SiteStats\Tests\Filament;

use Livewire\Livewire;
use Modules\SiteStats\Filament\Pages\SiteStatsPage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class SiteStatsPageTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_can_render_site_stats_page(): void
    {
        $this->actingAsAdmin();
        Livewire::test(SiteStatsPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_page_class_exists(): void
    {
        $this->assertTrue(class_exists(SiteStatsPage::class));
    }
}
