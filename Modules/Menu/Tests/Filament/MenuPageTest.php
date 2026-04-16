<?php

namespace Modules\Menu\Tests\Filament;

use Livewire\Livewire;
use Modules\Menu\Filament\Admin\Pages\AdminMenusPage;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MenuPageTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_can_render_menus_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminMenusPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_non_admin_cannot_access_menus_page(): void
    {
        $this->actingAsUser();

        $response = $this->get(AdminMenusPage::getUrl());
        $response->assertForbidden();
    }
}
