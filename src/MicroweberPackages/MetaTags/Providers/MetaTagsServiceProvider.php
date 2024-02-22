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

        if(app()->environment() === 'testing') {
            $this->app->register(MetaTagsUnitTestServiceProvider::class);
        }

    }

    protected function packages()
    {

        PackageManager::create('frontend', function (Package $package) {
            $package->requires([
                'core',
                'custom_user_css',
                'custom_user_meta_tags',
            ]);
        });

        PackageManager::create('admin', function (Package $package) {
            $package->requires('core');
        });


        PackageManager::create('core', function (Package $package) {
            $package->addTag(
                'favicon_head_tag',
                new \MicroweberPackages\MetaTags\Entities\FaviconHeadTag()
            );
            $package->addTag(
                'system_default_css_head_tags',
                new \MicroweberPackages\MetaTags\Entities\SystemDefaultCssHeadTags()
            );
            $package->addTag(
                'generator_head_tag',
                new \MicroweberPackages\MetaTags\Entities\GeneratorHeadTag()
            );
            $package->addTag(
                'apijs',
                new \MicroweberPackages\MetaTags\Entities\ApijsScriptTag()
            );
            $package->addTag(
                'live_wire_head_tags',
                new \MicroweberPackages\MetaTags\Entities\LivewireHeadTags()
            );

        });


        PackageManager::create('custom_user_css', function (Package $package) {
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


        PackageManager::create('custom_user_meta_tags', function (Package $package) {
            $package->addTag(
                'custom_user_head_tags',
                new \MicroweberPackages\MetaTags\Entities\CustomUserHeadTags()
            );

        });
    }


}
