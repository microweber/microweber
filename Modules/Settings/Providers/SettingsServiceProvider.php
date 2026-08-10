<?php

namespace Modules\Settings\Providers;

use MicroweberPackages\AiTools\Support\RegistersAiTools;
use Modules\Settings\Tools\SettingsReadTool;


use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\FileManager\Filament\Pages\FileManagerPageAdmin;
use Modules\Settings\Filament\Pages\{AdminAdvancedPage,
    AdminCustomTagsPage,
    AdminEmailPage,
    AdminExperimentalPage,
    AdminGeneralPage,
    AdminLanguagePage,
    AdminLoginRegisterPage,
    AdminMaintenanceModePage,
    AdminPrivacyPolicyPage,
    AdminSeoPage,
    AdminShopAutoRespondEmailPage,
    AdminShopGeneralPage,
    AdminShopOtherPage,
    AdminTemplateCustomizerPage,
    AdminTemplatePage,
    Settings};
use Modules\Settings\Filament\Resources\ModuleConfigurationResource;
use Modules\Settings\Filament\Resources\TranslationResource;

class SettingsServiceProvider extends BaseModuleServiceProvider
{
    use RegistersAiTools;

    protected string $moduleName = 'Settings';

    protected string $moduleNameLower = 'settings';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerAiTools([
            SettingsReadTool::class,
        ]);

        // Register Livewire components
     }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));

        // Register main settings page
        FilamentRegistry::registerPage(Settings::class);

        // Register translation resource
        FilamentRegistry::registerResource(TranslationResource::class);

        // Register website settings pages
        FilamentRegistry::registerPage(AdminAdvancedPage::class);
        FilamentRegistry::registerPage(AdminCustomTagsPage::class);
        FilamentRegistry::registerPage(AdminEmailPage::class);
        FilamentRegistry::registerPage(AdminExperimentalPage::class);
        FilamentRegistry::registerPage(AdminGeneralPage::class);
        FilamentRegistry::registerPage(AdminLanguagePage::class);
        FilamentRegistry::registerPage(AdminLoginRegisterPage::class);
        FilamentRegistry::registerPage(AdminMaintenanceModePage::class);
        FilamentRegistry::registerPage(AdminPrivacyPolicyPage::class);

        FilamentRegistry::registerPage(AdminSeoPage::class);
        FilamentRegistry::registerPage(AdminTemplatePage::class);
        FilamentRegistry::registerPage(AdminTemplateCustomizerPage::class);

        // Register shop settings pages
        FilamentRegistry::registerPage(AdminShopAutoRespondEmailPage::class);
        FilamentRegistry::registerPage(AdminShopGeneralPage::class);
        FilamentRegistry::registerPage(AdminShopOtherPage::class);

        // Register module configuration resource
        FilamentRegistry::registerResource(ModuleConfigurationResource::class);

        // ── Global search entries for the admin panel ──
        $this->registerGlobalSearchEntries();
    }

    protected function registerGlobalSearchEntries(): void
    {
        // Website settings pages
        FilamentRegistry::registerGlobalSearchEntry(
            'General Settings', '/admin/settings/general',
            ['general', 'website name', 'website title', 'website description', 'website keywords',
             'seo', 'permalink', 'date format', 'time zone', 'posts per page', 'logo', 'favicon',
             'maintenance mode', 'under construction', 'items per page', 'meta tags'],
            'Settings', ['Section' => 'Website Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'SEO Settings', '/admin/settings/seo-page',
            ['seo', 'search engine', 'google analytics', 'google site verification',
             'facebook pixel', 'bing', 'yandex', 'pinterest', 'alexa',
             'meta tags', 'meta description', 'site verification', 'tracking code',
             'google tag manager', 'analytics id'],
            'Settings', ['Section' => 'Website Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Email Settings', '/admin/settings/email',
            ['email', 'smtp', 'mail', 'mail server', 'email notifications',
             'email configuration', 'mailer', 'mail driver', 'sendmail'],
            'Settings', ['Section' => 'Website Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Language Settings', '/admin/settings/language',
            ['language', 'locale', 'translation', 'translations', 'multilanguage',
             'multi-language', 'i18n', 'localization'],
            'Settings', ['Section' => 'Website Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Login & Register Settings', '/admin/settings/login-register',
            ['login', 'register', 'registration', 'sign up', 'sign in',
             'authentication', 'user registration', 'login page', 'captcha'],
            'Settings', ['Section' => 'Website Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Privacy Policy Settings', '/admin/settings/privacy-policy',
            ['privacy', 'privacy policy', 'gdpr', 'data protection',
             'cookie policy', 'terms', 'consent'],
            'Settings', ['Section' => 'Website Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Advanced Settings', '/admin/settings/advanced',
            ['advanced', 'cache', 'debug', 'developer', 'api', 'cors',
             'performance', 'optimization', 'database', 'system'],
            'Settings', ['Section' => 'Website Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Custom Tags Settings', '/admin/settings/custom-tags',
            ['custom tags', 'html head', 'html footer', 'custom code',
             'head tags', 'footer tags', 'tracking code', 'custom html',
             'script tags', 'css tags', 'header code', 'footer code'],
            'Settings', ['Section' => 'Website Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Experimental Settings', '/admin/settings/experimental',
            ['experimental', 'beta', 'features', 'experimental features', 'labs', 'new features'],
            'Settings', ['Section' => 'Website Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'System Settings', '/admin/settings/maintenance-mode',
            ['maintenance', 'maintenance mode', 'system', 'under construction', 'site down', 'offline mode'],
            'Settings', ['Section' => 'Website Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Template Settings', '/admin/settings/template',
            ['template', 'theme', 'design', 'appearance', 'skin',
             'layout', 'template settings', 'change template'],
            'Settings', ['Section' => 'Customization Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Updates', '/admin/settings/updates',
            ['updates', 'update', 'upgrade', 'version', 'latest version',
             'check for updates', 'system update'],
            'Settings', ['Section' => 'Website Settings'],
        );

        // Shop settings pages
        FilamentRegistry::registerGlobalSearchEntry(
            'Main Shop Settings', '/admin/settings/shop-general',
            ['shop', 'store', 'ecommerce', 'e-commerce', 'currency', 'currency symbol',
             'terms and conditions', 'payment options', 'payment settings',
             'shipping settings', 'coupons', 'discount', 'discount prices',
             'shop settings', 'online store', 'usd', 'eur'],
            'Shop Settings', ['Section' => 'Shop Settings'],
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Shop Auto Respond Email Settings', '/admin/settings/shop-auto-respond-email',
            ['auto respond', 'auto reply', 'order confirmation email',
             'shop email', 'order email', 'notification email',
             'auto respond email', 'automated email'],
            'Shop Settings', ['Section' => 'Shop Settings'],
        );
    }

}
