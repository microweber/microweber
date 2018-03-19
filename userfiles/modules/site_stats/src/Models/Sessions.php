<?php

namespace Microweber\SiteStats\Models;


class Sessions extends Base
{
    protected $table = 'stats_sessions';
    protected $fillable = [
        'session_id',
        'referrer_id',
        'user_ip',
        'user_id',
        'browser_id',
        'language',
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

}