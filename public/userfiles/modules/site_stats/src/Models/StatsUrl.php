<?php
namespace MicroweberPackages\Modules\SiteStats\Models;

use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class StatsUrl extends Base
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
