<?php

namespace Modules\Settings\Tests\Filament;

use Livewire\Livewire;
use Modules\Settings\Filament\Pages\AdminGeneralPage;
use Modules\Settings\Filament\Pages\AdminEmailPage;
use Modules\Settings\Filament\Pages\AdminSeoPage;
use Modules\Settings\Filament\Pages\AdminTemplatePage;
use Modules\Settings\Filament\Pages\AdminAdvancedPage;
use Modules\Settings\Filament\Pages\AdminLanguagePage;
use Modules\Settings\Filament\Pages\AdminLoginRegisterPage;
use Modules\Settings\Filament\Pages\AdminMaintenanceModePage;
use Modules\Settings\Filament\Pages\AdminPrivacyPolicyPage;
use Modules\Settings\Filament\Pages\AdminShopGeneralPage;
use Modules\Settings\Filament\Pages\AdminShopOtherPage;
use Modules\Settings\Filament\Pages\AdminCustomTagsPage;
use Modules\Settings\Filament\Pages\AdminExperimentalPage;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class SettingsPagesTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_can_render_general_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminGeneralPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_email_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminEmailPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_seo_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminSeoPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_template_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminTemplatePage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_advanced_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminAdvancedPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_language_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminLanguagePage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_login_register_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminLoginRegisterPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_maintenance_mode_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminMaintenanceModePage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_privacy_policy_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminPrivacyPolicyPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_shop_general_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminShopGeneralPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_shop_other_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminShopOtherPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_custom_tags_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminCustomTagsPage::class)->assertSuccessful();
    }

    #[Test]
    public function it_can_render_experimental_settings(): void
    {
        $this->actingAsAdmin();
        Livewire::test(AdminExperimentalPage::class)->assertSuccessful();
    }
}
