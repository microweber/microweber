<?php

namespace MicroweberPackages\Console\Commands;

use Illuminate\Console\Command;

class ReloadDatabaseCommand extends Command
{
    protected $name = 'microweber:reload-database';
    protected $description = 'Reload Microweber Database';

    public function handle()
    {
        //echo 1;
        mw_post_update();
    }
}
