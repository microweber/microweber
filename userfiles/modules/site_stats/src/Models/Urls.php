<?php

namespace Microweber\SiteStats\Models;


class Urls extends Base
{
    protected $table = 'stats_urls';
    protected $fillable = [
        'content_id',
        'category_id',
        'url_hash',
        'url',
        'updated_at',
    ];

}