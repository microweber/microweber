<?php

namespace MicroweberPackages\Modules\Newsletter\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;
use MicroweberPackages\Modules\Newsletter\Senders\NewsletterMailSender;

class ProcessCampaignSubscriber implements ShouldQueue
{
    use Queueable;

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
            $campaign->status = NewsletterCampaign::STATUS_FINISHED;
            $campaign->save();
            return;
        }

        $template = NewsletterTemplate::where('id', $campaign->email_template_id)->first();
        if (!$template) {
//            $this->error('Email template not found');
            $campaign->status = NewsletterCampaign::STATUS_FINISHED;
            $campaign->save();
            return;
        }

        $checkCampaignStatus = NewsletterCampaign::select(['id', 'status'])->where('id', $campaign->id)
            ->first();
        if ($checkCampaignStatus->status == NewsletterCampaign::STATUS_FINISHED) {
//            $this->error('Campaign is finished');
            return;
        }
        if ($checkCampaignStatus->status == NewsletterCampaign::STATUS_PAUSED) {
//            $this->error('Campaign is paused');
            return;
        }
        if ($checkCampaignStatus->status == NewsletterCampaign::STATUS_CANCELED) {
//            $this->error('Campaign is canceled');
            return;
        }

        $findCampaignSendLog = NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)
            ->where('subscriber_id', $subscriber->id)
            ->first();
        if (!empty($findCampaignSendLog)) {
//            $this->error('Campaign already sent to this subscriber');
            return;
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
            } else {
                $campaignSendLog = new NewsletterCampaignsSendLog();
                $campaignSendLog->campaign_id = $campaign->id;
                $campaignSendLog->subscriber_id = $subscriber->id;
                $campaignSendLog->is_sent = 0;
                $campaignSendLog->save();
            }

        } catch (\Exception $e) {

            $campaignSendLog = new NewsletterCampaignsSendLog();
            $campaignSendLog->campaign_id = $campaign->id;
            $campaignSendLog->subscriber_id = $subscriber->id;
            $campaignSendLog->is_sent = 0;
            $campaignSendLog->save();

//            $this->error($e->getMessage());
        }
    }
}
