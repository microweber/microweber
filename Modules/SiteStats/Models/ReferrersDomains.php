<?php

namespace Modules\SiteStats\Models;


class ReferrersDomains extends Base
{
    protected $table = 'stats_referrers_domains';
    protected $fillable = [
        'id',
        'referrer_domain',
        'updated_at',
    ];

    public function paths()
    {
        return $this->hasMany('\Modules\SiteStats\Models\ReferrersPaths','referrer_domain_id' );
    }

}
