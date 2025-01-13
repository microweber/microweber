<?php

namespace MicroweberPackages\Modules\Newsletter\Console\Commands;

use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignPixel;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriberList;
use Throwable;
use Illuminate\Console\Command;
use MicroweberPackages\Modules\Newsletter\Jobs\ProcessCampaignSubscriber;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;
use MicroweberPackages\Modules\Newsletter\Senders\NewsletterMailSender;

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
