<?php

namespace Modules\Newsletter\Console\Commands;

use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use Modules\Newsletter\Models\NewsletterCampaignPixel;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Throwable;
use Illuminate\Console\Command;
use Modules\Newsletter\Jobs\ProcessCampaignSubscriber;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterTemplate;
use Modules\Newsletter\Senders\NewsletterMailSender;

class ProcessCampaignsPerformanceTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:process-campaigns-performance-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process campaigns performance test';

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
//
        $findList = NewsletterList::first();

        for ($i = 0; $i < 200000; $i++) {
            $new = new NewsletterSubscriber();
            $new->email = 'test' . $i . '@test.com';
            $new->name = 'test' . $i;
            $new->is_subscribed = 1;
            $new->save();

            $saveInList = new NewsletterSubscriberList();
            $saveInList->list_id = $findList->id;
            $saveInList->subscriber_id = $new->id;
            $saveInList->save();
        }




    }
}
