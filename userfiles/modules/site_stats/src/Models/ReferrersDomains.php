<?php

namespace Microweber\SiteStats\Models;


class ReferrersDomains extends Base
{
    protected $table = 'stats_referrers_domains';
    protected $fillable = [
        'referrer_domain',
        'updated_at',
    ];

}