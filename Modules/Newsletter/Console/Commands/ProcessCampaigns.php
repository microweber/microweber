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
                $timeNowByTimezone
                    = Carbon::now($scheduledCampaign->scheduled_timezone);
                $scheduledAt = Carbon::parse($scheduledCampaign->scheduled_at,
                    $scheduledCampaign->scheduled_timezone);
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

        $pendingCampaign = NewsletterCampaign::where('status', NewsletterCampaign::STATUS_PENDING)->first();
        if ($pendingCampaign) {
            return $this->_processCampaign($pendingCampaign);
        }

        $processingCampaign = NewsletterCampaign::where('status', NewsletterCampaign::STATUS_PROCESSING)->first();
        if ($processingCampaign) {
            return $this->_processCampaign($processingCampaign);
        }
    }


    private function _processCampaign($campaign)
    {
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

        $limit = 100;
        $allSubscribersCount = NewsletterSubscriberList::where('list_id', $campaign->list_id)->count();

        $batchList = NewsletterSubscriberList::where('list_id', $campaign->list_id)
            ->whereDoesntHave('campaignsSendLog', function ($query) use ($campaign) {
                $query->where('campaign_id', $campaign->id);
            })
            ->limit($limit)
            ->get();

        $countSentLog = NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->count();
        $remainingSubscribersCount = $allSubscribersCount - $countSentLog;
        $delay = 1;
        foreach ($batchList as $subscriber) {
            $delay++;
            dispatch(new ProcessCampaignSubscriber($subscriber->subscriber_id, $campaign->id));
          //  dispatch(new ProcessCampaignSubscriber($subscriber->subscriber_id, $campaign->id))->delay(now()->addSeconds($delay));
        }

        $campaignProgress = ($remainingSubscribersCount / $allSubscribersCount) * 100;


        $campaign->jobs_progress = round(($limit - $campaignProgress), 2);

        if ($campaign->jobs_progress == 100) {
            $campaign->status = NewsletterCampaign::STATUS_FINISHED;
            $campaign->status_log = 'Campaign is finished';
        } else {
            $campaign->status = NewsletterCampaign::STATUS_PROCESSING;
            $campaign->status_log = 'Campaign is processing';
        }

        $campaign->save();

        return 0;
    }
}
