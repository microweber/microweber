<?php

namespace MicroweberPackages\Modules\Newsletter\Console\Commands;

use Illuminate\Console\Command;
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
            $campaign->status = NewsletterCampaign::STATUS_FINISHED;
            $campaign->save();
            return 0;
        }

        $template = NewsletterTemplate::where('id', $campaign->email_template_id)->first();
        if (!$template) {
            $this->error('Email template not found');
            $campaign->status = NewsletterCampaign::STATUS_FINISHED;
            $campaign->save();
            return 0;
        }

        $subscribers = NewsletterSubscriber::whereHas('lists', function ($query) use($campaign) {
            $query->where('list_id', $campaign->list_id);
        })->get();

        if (!$subscribers) {
            $this->error('No subscribers found');
            $campaign->status = NewsletterCampaign::STATUS_FINISHED;
            $campaign->save();
            return 0;
        }

        foreach ($subscribers as $subscriber) {

            $checkCampaignStatus = NewsletterCampaign::select(['id','status'])->where('id', $campaign->id)
                ->first();
            if ($checkCampaignStatus->status == NewsletterCampaign::STATUS_FINISHED) {
                $this->error('Campaign is finished');
                return 0;
            }
            if ($checkCampaignStatus->status == NewsletterCampaign::STATUS_PAUSED) {
                $this->error('Campaign is paused');
                return 0;
            }
            if ($checkCampaignStatus->status == NewsletterCampaign::STATUS_CANCELED) {
                $this->error('Campaign is canceled');
                return 0;
            }

            sleep(4);
            $this->info('Processing campaign ' . $campaign->id . ' for subscriber ' . $subscriber->id);
            continue;

            $findCampaignSendLog = NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)
                ->where('subscriber_id', $subscriber->id)
                ->where('is_sent', 1)
                ->first();
            if (!empty($findCampaignSendLog)) {
                $subscriber['error'] = true;
                $subscriber['error_message'] = 'Already sent';
                continue;
            }

            try {
                $newsletterMailSender = new NewsletterMailSender();
                $newsletterMailSender->setCampaign($campaign->toArray());
                $newsletterMailSender->setSubscriber($subscriber->toArray());
                $newsletterMailSender->setSender($sender->toArray());
                $newsletterMailSender->setTemplate($template->toArray());
                $sendMailResponse = $newsletterMailSender->sendMail();

                if ($sendMailResponse['success']) {
                    $campaignSendLog = new NewsletterCampaignsSendLog();
                    $campaignSendLog->campaign_id = $campaign->id;
                    $campaignSendLog->subscriber_id = $subscriber->id;
                    $campaignSendLog->is_sent = 1;
                    $campaignSendLog->save();
                }

            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }

        }

        return 0;
    }
}
