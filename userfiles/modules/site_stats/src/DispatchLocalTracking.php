<?php

namespace MicroweberPackages\SiteStats;

use MicroweberPackages\SiteStats\Models\UserAttribute;

class DispatchLocalTracking
{
    public function dispatch()
    {
        if (is_logged()) {
            if (isset($_COOKIE['utm_id'])) {
                UserAttribute::saveAttribute('utm_id', $_COOKIE['utm_id']);
            }
            if (isset($_COOKIE['utm_term'])) {
                UserAttribute::saveAttribute('utm_term', $_COOKIE['utm_term']);
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
