<?php

namespace MicroweberPackages\SiteStats;

use MicroweberPackages\SiteStats\Models\UserAttribute;

class DispatchLocalTracking
{
    public function dispatch()
    {
        if (is_logged()) {
            if (isset($_COOKIE['_ga'])) {
                UserAttribute::saveAttribute('_ga', $_COOKIE['_ga']);
            }
            if (isset($_COOKIE['utm_campaign'])) {
                UserAttribute::saveAttribute('utm_campaign', $_COOKIE['utm_campaign']);
            }
            if (isset($_COOKIE['utm_medium'])) {
                UserAttribute::saveAttribute('utm_medium', $_COOKIE['utm_medium']);
            }
            if (isset($_COOKIE['utm_source'])) {
                UserAttribute::saveAttribute('utm_source', $_COOKIE['utm_source']);
            }
            if (isset($_COOKIE['utm_content'])) {
                UserAttribute::saveAttribute('utm_content', $_COOKIE['utm_content']);
            }
        }
    }
}
