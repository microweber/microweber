<?php

namespace MicroweberPackages\Template\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void addScript(string $id, string $src, array $attributes = [], string $placement = 'head')
 * @method static void removeScript(string $id)
 * @method static void addStyle(string $id, string $src, array $attributes = [], string $placement = 'head')
 * @method static void removeStyle(string $id)
 * @method static void addCustomHeadTag(string $html)
 * @method static void addCustomFooterTag(string $html)
 * @method static string headTags()
 * @method static string footerTags()
 * @method static string styles(string $placement = 'head')
 * @method static string scripts(string $placement = 'head')
 * @method static string customHeadTags()
 * @method static string customFooterTags()
 * @see \MicroweberPackages\Template\Repositories\TemplateMetaTagsRepository
 * @mixin \MicroweberPackages\Template\Repositories\TemplateMetaTagsRepository
 */
class TemplateMetaTags extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'template_meta_tags';
    }
}
