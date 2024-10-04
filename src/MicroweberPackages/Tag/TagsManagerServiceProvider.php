<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Tag;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Multilanguage\TranslateTables\TranslateOption;
use MicroweberPackages\Tag\TranslateTables\TranslateTaggingTagged;
use MicroweberPackages\Tag\TranslateTables\TranslateTaggingTags;
use Modules\Tag\Model\Tag;
use Modules\Tag\Model\Tagged;
use Modules\Tag\Model\TagGroup;

class TagsManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations/');

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        \Config::set('tagging.tag_model', Tag::class);
        \Config::set('tagging.tagged_model', Tagged::class);
        \Config::set('tagging.tag_group_model', TagGroup::class);

        $this->app->translate_manager->addTranslateProvider(TranslateTaggingTags::class);
        $this->app->translate_manager->addTranslateProvider(TranslateTaggingTagged::class);

        /**
         * @property \MicroweberPackages\Tag\TagsManager    $tags_manager
         */
        $this->app->singleton('tags_manager', function ($app) {
            return new TagsManager();
        });
    }
}
