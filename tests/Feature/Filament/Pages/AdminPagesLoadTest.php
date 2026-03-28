<?php

namespace Tests\Feature\Filament\Pages;

use Livewire\Livewire;
use Modules\FileManager\Filament\Pages\FileManagerPageAdmin;
use Modules\Menu\Filament\Admin\Pages\AdminMenusPage;
use Modules\Profile\Filament\Pages\EditProfile;
use Modules\Updater\Filament\Pages\UpdaterPage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class AdminPagesLoadTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_can_render_file_manager_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(FileManagerPageAdmin::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_menus_page_class_exists(): void
    {
        $this->assertTrue(class_exists(AdminMenusPage::class));
    }

    #[Test]
    public function it_edit_profile_page_class_exists(): void
    {
        $this->assertTrue(class_exists(EditProfile::class));
    }

    #[Test]
    public function it_can_render_updater_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(UpdaterPage::class)
            ->assertSuccessful();
    }
}
