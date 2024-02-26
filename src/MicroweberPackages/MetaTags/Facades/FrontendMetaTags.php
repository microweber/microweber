<?php

namespace MicroweberPackages\MetaTags\Facades;

use Illuminate\Support\Facades\Facade;



/**
 * Class FrontendMetaTags
 *
 * @method static string getHeadMetaTags()
 * @method static string getFooterMetaTags()
 *
 * @see \MicroweberPackages\MetaTags\FrontendMetaTagsRenderer
 * @mixin \MicroweberPackages\MetaTags\FrontendMetaTagsRenderer
 */
class FrontendMetaTags extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MicroweberPackages\MetaTags\FrontendMetaTagsRenderer::class;
    }
}
