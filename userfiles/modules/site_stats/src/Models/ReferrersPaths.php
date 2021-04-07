<?php

namespace MicroweberPackages\SiteStats\Models;


class ReferrersPaths extends Base
{
    protected $table = 'stats_referrers_paths';
    protected $fillable = [
        'referrer_path',
        'referrer_domain_id',
        'updated_at',
    ];

}