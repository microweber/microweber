<?php

namespace MicroweberPackages\Modules\Newsletter\Console\Commands;

use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignPixel;
use Throwable;
use Illuminate\Console\Command;
use MicroweberPackages\Modules\Newsletter\Jobs\ProcessCampaignSubscriber;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;
use MicroweberPackages\Modules\Newsletter\Senders\NewsletterMailSender;

class ProcessCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:process-campaigns';

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
        // Check queue configuration
        $checkDefaultQueue = config('queue.default');
        if ($checkDefaultQueue !== 'database') {
            $this->error('Please set the default queue to database. Other queue drivers are not supported.');
            return 0;
        }

        // Check the scheduled campaigns
        $getScheduledCampaigns = NewsletterCampaign::where('status', NewsletterCampaign::STATUS_SCHEDULED)->get();
        if ($getScheduledCampaigns->count() > 0) {
            foreach ($getScheduledCampaigns as $scheduledCampaign) {
                $timeNowByTimezone = Carbon::now($scheduledCampaign->scheduled_timezone);
                $scheduledAt = Carbon::parse($scheduledCampaign->scheduled_at, $scheduledCampaign->scheduled_timezone);
                if ($timeNowByTimezone->gte($scheduledAt)) {
                    $this->info('Processing scheduled campaign: ' . $scheduledCampaign->name);
                    $this->info('Marking campaign as pending');
                    $scheduledCampaign->status = NewsletterCampaign::STATUS_PENDING;
                    $scheduledCampaign->status_log = 'Scheduled campaign is now pending';
                    $scheduledCampaign->save();
                }
            }
        }


        // We limit the number of campaigns that can be processed at once
        // You can call this command multiple times with cron job

        $campaign = NewsletterCampaign::where('status', NewsletterCampaign::STATUS_PENDING)->first();
        if (!$campaign) {
            $this->error('No campaigns to process');
            return 0;
        }

        $sender = NewsletterSenderAccount::where('id', $campaign->sender_account_id)->first();
        if (!$sender) {
            $this->error('Sender account not found');
            $campaign->status = NewsletterCampaign::STATUS_FAILED;
            $campaign->status_log = 'Sender account not found';
            $campaign->save();
            return 0;
        }

        if ($campaign->email_content_type == 'design') {
            $template = NewsletterTemplate::where('id', $campaign->email_template_id)->first();
            if (!$template) {
                $this->error('Email template not found');
                $campaign->status = NewsletterCampaign::STATUS_FAILED;
                $campaign->status_log = 'Email template not found';
                $campaign->save();
                return 0;
            }
        } else if (empty($campaign->email_content_html)) {
            $this->error('Email content not found');
            $campaign->status = NewsletterCampaign::STATUS_FAILED;
            $campaign->status_log = 'Email content not found';
            $campaign->save();
            return 0;
        }

        $subscribers = NewsletterSubscriber::whereHas('lists', function ($query) use($campaign) {
            $query->where('list_id', $campaign->list_id);
        })->get();

        if (!$subscribers) {
            $this->error('No subscribers found');
            $campaign->status = NewsletterCampaign::STATUS_FAILED;
            $campaign->status_log = 'No subscribers found';
            $campaign->save();
            return 0;
        }

        $campaign->status = NewsletterCampaign::STATUS_QUEUED;
        $campaign->save();

        $batches = [];
        foreach ($subscribers as $subscriber) {
            $batches[] = new ProcessCampaignSubscriber($subscriber->id, $campaign->id);
        }

        $batch = Bus::batch($batches)
            ->progress(function (Batch $batch) use($campaign) {

                $delaySeconds = 2;
                if ($campaign->delay_between_sending_emails > 0) {
                    if ($campaign->delay_between_sending_emails < 15) {
                        $delaySeconds = $campaign->delay_between_sending_emails;
                    }
                }

                sleep($delaySeconds);
                $campaign->jobs_progress = $batch->progress();
                $campaign->save();

            })
            ->finally(function (Batch $batch) use($campaign) {
                if ($batch->finished()) {
                    $campaign->status = NewsletterCampaign::STATUS_FINISHED;
                    $campaign->status_log = 'Batch finished';
                } else {
                    $campaign->status = NewsletterCampaign::STATUS_FAILED;
                    $campaign->status_log = 'Batch failed';
                }
                $campaign->save();
            })
            ->allowFailures()
            ->dispatch();


        if (empty($batch->id)) {
            $campaign->status = NewsletterCampaign::STATUS_FAILED;
            $campaign->status_log = 'Batch not created';
            $campaign->save();
            $this->error('Batch not created');
            return 0;
        }

        $campaign->total_jobs = count($batches);
        $campaign->jobs_batch_id = $batch->id;
        $campaign->status = NewsletterCampaign::STATUS_PROCESSING;
        $campaign->save();

        return 0;
    }
}
