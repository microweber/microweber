<?php

namespace MicroweberPackages\Microweber\Traits;

use MicroweberPackages\Url\Facades\UrlManager;


/**
 * @deprecated
 */
trait ManagesUrl
{
    /**
     * Generates a full URL for the given path.
     *
     * @param string|bool $path The path to generate the URL for. If false, the base URL is returned.
     * @return string The generated URL.
     */
    public function siteUrl($path = false) : string
    {
        return UrlManager::site($path);
    }

    /**
     * Retrieves the hostname of the site.
     *
     * @return string The hostname of the site.
     */
    public function siteHostname() : string
    {
        return UrlManager::hostname();
    }


}
