<?php

namespace MicroweberPackages\Modules\SiteStats\Models;


class Browsers extends Base
{
    protected $table = 'stats_browser_agents';
    protected $fillable = [
        'browser_agent_hash',
        'browser_agent',
        'updated_at',


        'platform',
        'platform_version',
        'browser',
        'browser_version',
        'device',


        'is_desktop',
        'is_mobile',
        'is_phone',
        'is_tablet',
        'is_robot',
        'robot_name',
        'language',

    ];


}
