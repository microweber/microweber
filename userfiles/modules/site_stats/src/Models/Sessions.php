<?php

namespace Microweber\SiteStats\Models;


class Sessions extends Base
{
    protected $table = 'stats_sessions';
    protected $fillable = [
        'session_id',
        'referrer_id',
        'referrer_domain_id',
        'referrer_path_id',
        'user_ip',
        'user_id',
        'geoip_id',
        'browser_id',
        'language',
        'session_hostname',
        'updated_at',
    ];


    public function views()
    {
        return $this->hasMany('Microweber\SiteStats\Models\Log','session_id_key');
    }

    public function browser()
    {
        return $this->belongsTo('Microweber\SiteStats\Models\Browsers');
    }

    public function geoip()
    {
        return $this->belongsTo('Microweber\SiteStats\Models\Geoip');
    }

    public function referrer()
    {
        return $this->belongsTo('Microweber\SiteStats\Models\Referrers');
    }

}