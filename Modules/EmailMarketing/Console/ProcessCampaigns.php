<?php

namespace Modules\EmailMarketing\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProcessCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'email-marketing:process-campaigns';

    /**
     * The console command description.
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo 'Email marketing campaigns processed.';
    }

}
