<?php

namespace MicroweberPackages\Modules\SiteStats\Models;


class Log extends Base
{
    protected $table = 'stats_visits_log';
    protected $fillable = [

        'session_id_key',
        'url_id',
        'referrer_id',
//        'content_id',
//        'category_id',
        'updated_at',
        'view_count',
    ];


    public function url()
    {
        return $this->belongsTo('MicroweberPackages\Modules\SiteStats\Models\StatsUrl');
    }

}
