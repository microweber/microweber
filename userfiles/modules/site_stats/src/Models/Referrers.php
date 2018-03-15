<?php

namespace Microweber\SiteStats\Models;


class Referrers extends Base
{
    protected $table = 'stats_referrers';
    protected $fillable = [
        'referrer_hash',
        'referrer',
        'is_internal',
        'updated_at',
    ];

}