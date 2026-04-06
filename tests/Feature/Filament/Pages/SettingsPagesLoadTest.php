<?php

namespace Tests\Feature\Filament\Pages;

use Livewire\Livewire;
use Modules\Settings\Filament\Pages\AdminGeneralPage;
use Modules\Settings\Filament\Pages\AdminSeoPage;
use Modules\Settings\Filament\Pages\AdminEmailPage;
use Modules\Settings\Filament\Pages\AdminAdvancedPage;
use Modules\Settings\Filament\Pages\AdminCustomTagsPage;
use Modules\Settings\Filament\Pages\AdminExperimentalPage;
use Modules\Settings\Filament\Pages\AdminLanguagePage;
use Modules\Settings\Filament\Pages\AdminLoginRegisterPage;
use Modules\Settings\Filament\Pages\AdminMaintenanceModePage;
use Modules\Settings\Filament\Pages\AdminPrivacyPolicyPage;
use Modules\Settings\Filament\Pages\AdminShopGeneralPage;
use Modules\Settings\Filament\Pages\AdminShopOtherPage;
use Modules\Settings\Filament\Pages\AdminShopAutoRespondEmailPage;
use Modules\Settings\Filament\Pages\AdminTemplatePage;
use Modules\Settings\Filament\Pages\AdminUpdatesPage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class SettingsPagesLoadTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_can_render_general_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminGeneralPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_seo_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminSeoPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_email_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminEmailPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_advanced_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminAdvancedPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_custom_tags_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminCustomTagsPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_experimental_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminExperimentalPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_language_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminLanguagePage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_login_register_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminLoginRegisterPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_maintenance_mode_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminMaintenanceModePage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_privacy_policy_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminPrivacyPolicyPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_shop_general_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminShopGeneralPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_shop_other_settings_page_class_exists(): void
    {
        $this->assertTrue(class_exists(AdminShopOtherPage::class));
    }

    #[Test]
    public function it_can_render_shop_auto_respond_email_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminShopAutoRespondEmailPage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_template_settings_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(AdminTemplatePage::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_updates_settings_page_class_exists(): void
    {
        $this->assertTrue(class_exists(AdminUpdatesPage::class));
    }

    #[Test]
    #[DataProvider('settingsPageUrlsProvider')]
    public function settings_page_does_not_return_500(string $url, string $label): void
    {
        $this->actingAsAdmin();

        $response = $this->get($url);

        $this->assertNotEquals(
            500,
            $response->getStatusCode(),
            "Settings page '{$label}' at {$url} returned HTTP 500"
        );
    }

    public static function settingsPageUrlsProvider(): array
    {
        $prefix = '/admin';

        return [
            'Settings Index' => ["{$prefix}/settings", 'Settings Index'],
            'General' => ["{$prefix}/settings/general", 'General'],
            'SEO' => ["{$prefix}/settings/seo-page", 'SEO'],
            'Template' => ["{$prefix}/admin-template-page", 'Template'],
            'Login & Register' => ["{$prefix}/admin-login-register-page", 'Login & Register'],
            'Privacy Policy' => ["{$prefix}/admin-privacy-policy-page", 'Privacy Policy'],
            'Custom Tags' => ["{$prefix}/admin-custom-tags-page", 'Custom Tags'],
            'Cookie Notice' => ["{$prefix}/cookie-notice-module-settings-admin", 'Cookie Notice'],
            'Email' => ["{$prefix}/admin-email-page", 'Email'],
            'Auto Respond Email' => ["{$prefix}/admin-shop-auto-respond-email-page", 'Auto Respond Email'],
            'Mail Templates' => ["{$prefix}/mail-templates", 'Mail Templates'],
            'Main Shop Settings' => ["{$prefix}/admin-shop-general-page", 'Main Shop Settings'],
            'Shop Other' => ["{$prefix}/admin-shop-other-page", 'Shop Other'],
            'Advanced' => ["{$prefix}/admin-advanced-page", 'Advanced'],
            'System Settings' => ["{$prefix}/admin-maintenance-mode-page", 'System Settings'],
            'Language' => ["{$prefix}/admin-language-page", 'Language'],
            'Multilanguage' => ["{$prefix}/multilanguage-settings-admin", 'Multilanguage'],
            'White Label' => ["{$prefix}/white-label-settings-admin-settings-page", 'White Label'],
            'Module Configuration' => ["{$prefix}/module-configuration", 'Module Configuration'],
            'Module Dependencies' => ["{$prefix}/module-dependencies", 'Module Dependencies'],
            'Kitchen Sink' => ["{$prefix}/kitchen-sink", 'Kitchen Sink'],
        ];
    }
}
