<?php

namespace MicroweberPackages\Database\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ReplaceSiteUrlCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        if (is_string($value)) {
            $site = site_url();
            $value = str_replace('{SITE_URL}', $site, $value);
        }
        return $value;
    }

    public function set($model, $key, $value, $attributes)
    {
        if (is_string($value)) {
            $site = site_url();
            $value = str_replace($site, '{SITE_URL}', $value);
        }
        return [$key => $value];
    }
}