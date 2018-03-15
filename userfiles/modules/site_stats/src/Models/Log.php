<?php

namespace Microweber\SiteStats\Models;


class Log extends Base
{
    protected $table = 'stats_visits_log';
    protected $fillable = [

        'session_id_key',
        'url_id',
       // 'visit_url',
     //   'referrer',
        'referrer_id',
      //  'user_ip',
      //  'user_id',
        'content_id',
        'category_id',
       // 'browser_agent_id',
       // 'language',
        'updated_at',
        'view_count',
    ];

}