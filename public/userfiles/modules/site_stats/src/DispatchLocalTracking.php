<?php

namespace MicroweberPackages\Modules\SiteStats;

use MicroweberPackages\Modules\SiteStats\Models\UserAttribute;

class DispatchLocalTracking
{
    public $userId;

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function dispatch()
    {
        if ($this->userId) {
            if (isset($_COOKIE['utm_id'])) {
                UserAttribute::saveAttribute($this->userId,'utm_id', $_COOKIE['utm_id']);
            }
            if (isset($_COOKIE['utm_term'])) {
                UserAttribute::saveAttribute($this->userId,'utm_term', $_COOKIE['utm_term']);
            }
            if (isset($_COOKIE['utm_campaign'])) {
                UserAttribute::saveAttribute($this->userId,'utm_campaign', $_COOKIE['utm_campaign']);
            }
            if (isset($_COOKIE['utm_medium'])) {
                UserAttribute::saveAttribute($this->userId,'utm_medium', $_COOKIE['utm_medium']);
            }
            if (isset($_COOKIE['utm_source'])) {
                UserAttribute::saveAttribute($this->userId,'utm_source', $_COOKIE['utm_source']);
            }
            if (isset($_COOKIE['utm_content'])) {
                UserAttribute::saveAttribute($this->userId,'utm_content', $_COOKIE['utm_content']);
            }
        }
    }
}
