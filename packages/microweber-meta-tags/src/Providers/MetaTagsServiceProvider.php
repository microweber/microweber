<?php

namespace MicroweberPackages\MetaTags\Providers;

use Butschster\Head\Facades\PackageManager;
use Butschster\Head\Packages\Package;
use Illuminate\Support\ServiceProvider;

class MetaTagsServiceProvider extends \Butschster\Head\Providers\MetaTagsApplicationServiceProvider
{
    public function register(): void
    {
        parent::register();


        $this->app->singleton(\MicroweberPackages\MetaTags\FrontendMetaTagsRenderer::class);
        $this->app->singleton(\MicroweberPackages\MetaTags\AdminMetaTagsRenderer::class);


        if (app()->runningUnitTests() and is_cli()) {
            $this->app->register(MetaTagsUnitTestServiceProvider::class);
        }

    }

    protected function packages()
    {

        PackageManager::create('frontend', function (Package $package) {
            $package->requires([
                'title_head_tags',
                'core_css',
                'core',
                'livewire',
                'custom_user_css',
                'custom_user_meta_tags',
            ]);
        });

        PackageManager::create('admin', function (Package $package) {
            $package->requires([
                'core_css',
                'admin_default_css_and_js',
                'core',
                'livewire',
                'admin_custom_css_and_js',
            ]);
        });
        PackageManager::create('filament', function (Package $package) {
            $package->requires([
                'mw_settings_js',
                'admin_head_tags_filament_js_libs',

                'admin_filament_js',

                'admin_web_app_manifest',
                'mw_icon_fonts_css',

                'admin_custom_css_and_js',
            ]);
        });


        PackageManager::create('core_css', function (Package $package) {
            $package->addTag(
                'system_default_css_head_tags',
                new \MicroweberPackages\MetaTags\Entities\SystemDefaultCssHeadTags()
            );
        });
        PackageManager::create('core', function (Package $package) {
            $package->addTag(
                'favicon_head_tag',
                new \MicroweberPackages\MetaTags\Entities\FaviconHeadTag()
            );
            $package->addTag(
                'csrf_token',
                new \MicroweberPackages\MetaTags\Entities\CsrfTokenHeadTags()
            );

            $package->addTag(
                'generator_head_tag',
                new \MicroweberPackages\MetaTags\Entities\GeneratorHeadTag()
            );
            $package->addTag(
                'apijs_settings',
                new \MicroweberPackages\MetaTags\Entities\MwSettingsJsScriptTag()
            );
            $package->addTag(
                'apijs',
                new \MicroweberPackages\MetaTags\Entities\ApijsScriptTag()
            );

        });

        PackageManager::create('livewire', function (Package $package) {
            $package->addTag(
                'live_wire_head_tags',
                new \MicroweberPackages\MetaTags\Entities\LivewireHeadTags()
            );
            $package->addTag(
                'live_wire_footer_tags',
                new \MicroweberPackages\MetaTags\Entities\LivewireFooterTags()
            );

        });


        PackageManager::create('custom_user_css', function (Package $package) {
//
// loaded in frontend_append_meta_tags() before the </head> tag
            $package->addTag(
                'live_edit_css_head_tags_preload',
                new \MicroweberPackages\MetaTags\Entities\LiveEditCssHeadPrealoadTags()
            );
            $package->addTag(
                'live_edit_css_head_tags',
                new \MicroweberPackages\MetaTags\Entities\LiveEditCssHeadTags()
            );
            $package->addTag(
                'custom_fonts_css_head_tags',
                new \MicroweberPackages\MetaTags\Entities\CustomFontsCssHeadTags()
            );
            $package->addTag(
                'custom_css_head_tags',
                new \MicroweberPackages\MetaTags\Entities\CustomCssHeadTags()
            );
            $package->addTag(
                'frontend_animations_script_tag',
                new \MicroweberPackages\MetaTags\Entities\FrontendAnimationsScriptTag()
            );

        });

        PackageManager::create('title_head_tags', function (Package $package) {
            $package->addTag(
                'title_head_tags',
                new \MicroweberPackages\MetaTags\Entities\TitleHeadTags()
            );
            $package->addTag(
                'author_head_tags',
                new \MicroweberPackages\MetaTags\Entities\AuthorHeadTags()
            );

        });
        PackageManager::create('custom_user_meta_tags', function (Package $package) {


            $package->addTag(
                'custom_user_head_tags',
                new \MicroweberPackages\MetaTags\Entities\CustomUserHeadTags()
            );
            $package->addTag(
                'custom_head_tags_from_site_header_event',
                new \MicroweberPackages\MetaTags\Entities\CustomHeadTagsFromSiteHeaderEvent()
            );
            $package->addTag(
                'custom_head_tags_from_callback',
                new \MicroweberPackages\MetaTags\Entities\CustomHeadTagsFromCallback()
            );
            $package->addTag(
                'webmaster_head_tags',
                new \MicroweberPackages\MetaTags\Entities\WebmasterHeadTags()
            );

            $package->addTag(
                'template_head_tags',
                new \MicroweberPackages\MetaTags\Entities\TemplateHeadTags()
            );
            $package->addTag(
                'template_footer_tags',
                new \MicroweberPackages\MetaTags\Entities\TemplateFooterTags()
            );

            $package->addTag(
                'custom_user_footer_tags',
                new \MicroweberPackages\MetaTags\Entities\CustomUserFooterTags()
            );
            $package->addTag(
                'custom_footer_tags_from_callback',
                new \MicroweberPackages\MetaTags\Entities\CustomFooterTagsFromCallback()
            );
        });


        PackageManager::create('admin_default_css_and_js', function (Package $package) {
            $package->addTag(
                'admin_default_head_tags',
                new \MicroweberPackages\MetaTags\Entities\SystemDefaultAdminCssHeadTags()
            );
            $package->addTag(
                'admin_web_app_manifest',
                new \MicroweberPackages\MetaTags\Entities\AdminWebAppManifestTags()
            );
        });

        PackageManager::create('admin_web_app_manifest', function (Package $package) {
            $package->addTag(
                'admin_web_app_manifest',
                new \MicroweberPackages\MetaTags\Entities\AdminWebAppManifestTags()
            );
        });
        PackageManager::create('admin_custom_css_and_js', function (Package $package) {
            $package->addTag(
                'admin_head_tags_from_admin_manager',
                new \MicroweberPackages\MetaTags\Entities\AdminHeadTagsFromAdminManager()
            );
        });


        PackageManager::create('admin_head_tags_filament_js_libs', function (Package $package) {

            $package->addTag(
                'admin_head_tags_filament_js_libs',
                new \MicroweberPackages\MetaTags\Entities\AdminFilamentJsLibsScriptTag()
            );
        });


        PackageManager::create('admin_filament_js', function (Package $package) {


            $package->addTag(
                'admin_head_tags_filament_js',
                new \MicroweberPackages\MetaTags\Entities\AdminFilamentJsScriptTag()
            );
            $package->addTag(

                'admin_head_tags_filament_css',
                new \MicroweberPackages\MetaTags\Entities\AdminFilamentCssTag()
            );

            $package->addTag(
                'admin_disable_google_translate_head_tags',
                new \MicroweberPackages\MetaTags\Entities\DisableGoogleTranslateHeadTags()
            );
        });

        PackageManager::create('mw_settings_js', function (Package $package) {
            $package->addTag(
                'mw_settings_js_scripts',
                new \MicroweberPackages\MetaTags\Entities\MwSettingsJsScriptTag()
            );
        });
        PackageManager::create('mw_icon_fonts_css', function (Package $package) {
            $package->addTag(
                'mw_icon_fonts_head',
                new \MicroweberPackages\MetaTags\Entities\IconFontsHeadTag()
            );
            $package->addTag(
                'mw_icon_fonts_footer',
                new \MicroweberPackages\MetaTags\Entities\IconFontsFooterTag()
            );
        });


    }


}
