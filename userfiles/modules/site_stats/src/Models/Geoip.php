<?php

namespace MicroweberPackages\Modules\SiteStats\Models;


class Geoip extends Base
{
    protected $table = 'stats_geoip';
    protected $fillable = [
        'country_code',
        'country_name',
        'region',
        'city',
        'latitude',
        'language',
        'longitude',
    ];
}
