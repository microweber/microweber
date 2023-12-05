<?php

namespace MicroweberPackages\Modules\Newsletter\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;

class ProcessCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panel:process-campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process campaigns';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Processing Campaigns');

        $getCampaigns = NewsletterCampaign::where('is_scheduled', 1)
                    ->where('scheduled_at', '>=', date('Y-m-d H:i:s'))
                    ->where(function ($query) {
                        $query->where('is_done', 0)
                            ->orWhereNull('is_done');
                    })
                    ->get();

        if ($getCampaigns->count() > 0) {
            foreach ($getCampaigns as $campaign) {
                $this->info('Processing Campaign: ' . $campaign->name);


            }
        }


        return 0;
    }
}
