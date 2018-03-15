<?php

namespace Microweber\SiteStats\Models;


class Browsers extends Base
{
    protected $table = 'stats_browser_agents';
    protected $fillable = [
        'browser_agent_hash',
        'browser_agent',
        'updated_at',
    ];

}