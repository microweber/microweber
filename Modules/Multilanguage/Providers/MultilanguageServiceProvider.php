<?php

namespace Modules\Multilanguage\Providers;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Multilanguage\Filament\MultilanguageSettings;
use Modules\Multilanguage\Livewire\LanguagesTable;
use Modules\Multilanguage\Microweber\MultilanguageModule;

class MultilanguageServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Multilanguage';
    protected string $moduleNameLower = 'multilanguage';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(MultilanguageSettings::class);

        // Register Microweber module
        Microweber::module(MultilanguageModule::class);
        Livewire::component('modules.multilanguage::languages-table', LanguagesTable::class);

    }

    /**
     * Boot the application events.
     */
    public function boot(): void
    {

        // Register Livewire components

        // Register event bindings
        $this->registerEventBindings();
    }

    /**
     * Register event bindings for the module.
     */
    protected function registerEventBindings(): void
    {
        // Admin header toolbar
        if (function_exists('event_bind')) {
            event_bind('mw.admin.header.toolbar.ul', function () {
                echo '<module type="multilanguage" show_settings_link="true" template="admin"></module>';
            });

            // Live edit toolbar
            event_bind('live_edit_toolbar_action_buttons', function () {
                echo '<module type="multilanguage/change_language"></module>';
            });

            event_bind('mw.front', function () {

            $supportedLanguages = get_supported_languages();
            if (!empty($supportedLanguages)) {

                    template_head(function () use ($supportedLanguages) {

                        /**
                         * Links must look like
                         *
                        <link rel="canonical" href="https://localhost/microweber/ar_SA/others/apple-computer" />
                        <link rel="alternate" href="https://localhost/microweber/bg_BG/others/apple-computer" hreflang="bg-BG" />
                        <link rel="alternate" href="https://localhost/microweber/ar_SA/others/apple-computer" hreflang="ar-SA" />
                         */

                        /**
                         * 1.Canonical links is current page url
                         */

                        $metaTagsHtml = '';


                        // In homepage logis
                        if (is_home()) {

                            $currentLocale = app()->getLocale();
                            $homepageLanguage = get_option('homepage_language', 'website');
                            if (empty($homepageLanguage)) {
                                $homepageLanguage = $currentLocale;
                            }

                            $canonicalLink = url_current(true);
                            $canonicalLink = urldecode($canonicalLink);
                            $metaTagsHtml .= '<link rel="canonical" href="'.$canonicalLink.'" />' . PHP_EOL;

                            $metaTagsHtml .= '<link rel="alternate" href="' . site_url() . '" hreflang="' . $homepageLanguage . '" />' . "\n";

                            foreach ($supportedLanguages as $locale) {
                                $pm = new \MicroweberPackages\Multilanguage\MultilanguagePermalinkManager($locale['locale']);
                                $contentLink = $pm->link(content_id(), 'content');
                                $contentLink = trim($contentLink);

                                if (empty($contentLink)) {
                                    continue;
                                }
                                if ($homepageLanguage == $locale['locale']) {
                                    // Skip this alternate links
                                    continue;
                                }

                                $metaLocale = $locale['locale'];
                                $expMetaLocale = explode('_', $metaLocale);
                                if (count($expMetaLocale) > 1) {
                                    $metaLocale = $expMetaLocale[0];
                                }

                                $metaTagsHtml .= '<link rel="alternate" href="' . $contentLink . '" hreflang="' . $metaLocale . '" />' . "\n";
                            }

                            return $metaTagsHtml;
                        }

                        // In other pages
                        $canonicalLink = url_current(true);
                        $canonicalLink = urldecode($canonicalLink);
                        $metaTagsHtml .= '<link rel="canonical" href="'.$canonicalLink.'" />' . PHP_EOL;

                        $inPage = false;
                        $inProduct = false;
                        $inCategory = false;
                        if (page_id() == content_id()) {
                            $inPage = true;
                        } else {
                            $inProduct = true;
                        }

                        if ($inPage && category_id() !== 0) {
                            $inCategory = true;
                        }

                        foreach ($supportedLanguages as $locale) {
                            $pm = new \MicroweberPackages\Multilanguage\MultilanguagePermalinkManager($locale['locale']);

                            if ($inCategory) {
                                $contentLink = $pm->link(category_id(), 'category');
                            } else {
                                $contentLink = $pm->link(content_id(), 'content');
                            }

                            if (empty($contentLink)) {
                                continue;
                            }

                            $metaLocale = $locale['locale'];
                            $metaLocale = str_replace('_', '-', $metaLocale);
//                $expMetaLocale = explode('_', $metaLocale);
//                if (count($expMetaLocale) > 1) {
//                    $metaLocale = $expMetaLocale[0];
//                }


                            $metaTagsHtml .= '<link rel="alternate" href="' . $contentLink . '" hreflang="' . $metaLocale . '" />' . "\n";
                        }

                        return $metaTagsHtml;
                    });

            }
            });




        }
    }


}
