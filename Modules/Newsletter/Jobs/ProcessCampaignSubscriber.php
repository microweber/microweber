<?php

namespace Modules\Newsletter\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterTemplate;
use Modules\Newsletter\Senders\NewsletterMailSender;


class ProcessCampaignSubscriber implements ShouldQueue
{
    use Batchable, Queueable;

    public $subscriberId;
    public $campaignId;

    /**
     * Create a new job instance.
     */
    public function __construct($subscriberId, $campaignId)
    {
        $this->subscriberId = $subscriberId;
        $this->campaignId = $campaignId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $subscriber = NewsletterSubscriber::where('id', $this->subscriberId)->first();
        if (!$subscriber) {
            //$this->error('Subscriber not found');
            return;
        }

        $campaign = NewsletterCampaign::where('id', $this->campaignId)->first();
        if (!$campaign) {
            //$this->error('Campaign not found');
            return;
        }
        $sender = NewsletterSenderAccount::where('id', $campaign->sender_account_id)->first();
        if (!$sender) {
           // $this->error('Sender account not found');
            $campaign->status = NewsletterCampaign::STATUS_FAILED;
            $campaign->save();
            return;
        }

        if ($campaign->email_content_type == 'design') {
            $template = NewsletterTemplate::where('id', $campaign->email_template_id)->first();
            if (!$template) {
//            $this->error('Email template not found');
                $campaign->status = NewsletterCampaign::STATUS_FAILED;
                $campaign->save();
                return;
            }
        } else if (empty($campaign->email_content_html)) {
//            $this->error('Email content not found');
            $campaign->status = NewsletterCampaign::STATUS_FAILED;
            $campaign->save();
            return;
        }

        $checkCampaignStatus = NewsletterCampaign::select(['id', 'status'])->where('id', $campaign->id)
            ->first();
        if ($checkCampaignStatus->status == NewsletterCampaign::STATUS_FINISHED) {
//            $this->error('Campaign is finished');
            return;
        }
        if ($checkCampaignStatus->status == NewsletterCampaign::STATUS_CANCELED) {
//            $this->error('Campaign is canceled');
            return;
        }
        if ($checkCampaignStatus->status == NewsletterCampaign::STATUS_FAILED) {
            return;
        }

        $findCampaignSendLog = NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)
            ->where('subscriber_id', $subscriber->id)
            ->first();
        if (!empty($findCampaignSendLog)) {
//            $this->error('Campaign already sent to this subscriber');
            return;
        }

        $templateArray = [];
        if ($campaign->email_content_type == 'design') {
            $templateArray = $template->toArray();
        } else if (!empty($campaign->email_content_html)) {
            $templateArray['text'] = $campaign->email_content_html;
        }

        if (empty($templateArray)) {
            $campaign->status = NewsletterCampaign::STATUS_FAILED;
            $campaign->save();
            return;
        }

        $campaignSendLog = new NewsletterCampaignsSendLog();
        $campaignSendLog->campaign_id = $campaign->id;
        $campaignSendLog->subscriber_id = $subscriber->id;
        $campaignSendLog->is_sent = 0;
        $campaignSendLog->save();

        try {
            $newsletterMailSender = new NewsletterMailSender();
            $newsletterMailSender->setCampaign($campaign->toArray());
            $newsletterMailSender->setSubscriber($subscriber->toArray());
            $newsletterMailSender->setSender($sender->toArray());
            $newsletterMailSender->setTemplate($templateArray);

            $sendMailResponse = $newsletterMailSender->sendMail();

            if ($sendMailResponse['success']) {
                $campaignSendLog->is_sent = 1;
                $campaignSendLog->save();

                $campaign->completed_jobs = $campaign->completed_jobs + 1;
                $campaign->save();

            } else {
                $campaignSendLog->is_sent = 0;
                $campaignSendLog->save();
            }

        } catch (\Exception $e) {

            $campaignSendLog->is_sent = 0;
            $campaignSendLog->save();

//            $this->error($e->getMessage());
        }

    }
}
