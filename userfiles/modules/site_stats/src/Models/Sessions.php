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

}