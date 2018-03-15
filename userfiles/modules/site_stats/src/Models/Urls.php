<?php

namespace Microweber\SiteStats\Models;


class Urls extends Base
{
    protected $table = 'stats_urls';
    protected $fillable = [
        'url_hash',
        'url',
        'updated_at',
    ];

}