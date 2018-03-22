<?php

namespace Microweber\SiteStats\Models;


class Referrers extends Base
{
    protected $table = 'stats_referrers';
    protected $fillable = [
        'referrer_hash',
        'referrer',
        'referrer_domain_id',
        'referrer_path_id',
        'is_internal',
        'updated_at',
    ];


    public function domain()
    {
        return $this->belongsTo('Microweber\SiteStats\Models\ReferrersDomains' ,'referrer_domain_id' );
    }

    public function path()
    {
        return $this->belongsTo('Microweber\SiteStats\Models\ReferrersPaths','referrer_path_id');
    }


}