<?php

namespace MicroweberPackages\MetaTags\Facades;

use Illuminate\Support\Facades\Facade;



/**
 * Class AdminMetaTags
 *
 * @method static string getHeadMetaTags()
 * @method static string getFooterMetaTags()
 *
 * @see \MicroweberPackages\MetaTags\AdminMetaTagsRenderer
 * @mixin \MicroweberPackages\MetaTags\AdminMetaTagsRenderer
 */
class AdminMetaTags extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MicroweberPackages\MetaTags\AdminMetaTagsRenderer::class;
    }
}
