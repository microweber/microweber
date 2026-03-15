<?php

namespace Tests\Feature\Filament\Pages;

use Livewire\Livewire;
use Modules\Comments\Filament\Pages\CommentsModuleSettingsAdmin;
use Modules\CookieNotice\Filament\Pages\CookieNoticeModuleSettingsAdmin;
use Modules\GoogleAnalytics\Filament\Pages\AdminGoogleAnalyticsSettingsPage;
use Modules\Multilanguage\Filament\Pages\MultilanguageSettingsAdmin;
use Modules\WhiteLabel\Filament\Pages\WhiteLabelSettingsAdminSettingsPage;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

#[RunTestsInSeparateProcesses]
class ModuleSettingsPagesLoadTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_can_render_comments_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(CommentsModuleSettingsAdmin::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_cookie_notice_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(CookieNoticeModuleSettingsAdmin::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_google_analytics_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminGoogleAnalyticsSettingsPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_multilanguage_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(MultilanguageSettingsAdmin::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_white_label_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(WhiteLabelSettingsAdminSettingsPage::class)
            ->assertSuccessful();
    }
}
