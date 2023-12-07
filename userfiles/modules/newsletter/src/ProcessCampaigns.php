<?php
namespace MicroweberPackages\Modules\Newsletter;

use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;

class ProcessCampaigns
{
    public $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function run()
    {
        $getCampaigns = NewsletterCampaign::where('is_scheduled', 1)
            ->where('scheduled_at', '<=', date('Y-m-d H:i:s'))
            ->where(function ($query) {
                $query->where('is_done', 0)
                    ->orWhereNull('is_done');
            })
            ->get();

        if ($getCampaigns->count() > 0) {
            foreach ($getCampaigns as $campaign) {

                $this->logger->info('Processing Campaign: ' . $campaign->name);

                $template = newsletter_get_template(array("id"=>$campaign->email_template_id));
                $subscribers = newsletter_get_subscribers_for_list($campaign->list_id);
                $sender = newsletter_get_sender(array("id"=>$campaign->sender_account_id));

                if (empty($subscribers)) {
                    $this->logger->warn('No subscribers found for this campaign.');
                    $this->logger->info('');
                    continue;
                }

                if (empty($sender)) {
                    $this->logger->warn('No sender found for this campaign.');
                    $this->logger->info('');
                    continue;
                }

                if (empty($template)) {
                    $this->logger->warn('No template found for this campaign.');
                    $this->logger->info('');
                    continue;
                }

                $sendLogCount = 0;
                foreach($subscribers as $subscriber) {
                    $findCampaignSendLog = NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)
                        ->where('subscriber_id', $subscriber['id'])
                        ->where('is_sent', 1)
                        ->first();
                    if ($findCampaignSendLog) {
                        $this->logger->warn('Subscriber: ' . $subscriber['name'] . ' (' . $subscriber['email'] . ') - already sent');
                        continue;
                    }

                    $newsletterMailSender = new \Newsletter\Senders\NewsletterMailSender();
                    $newsletterMailSender->setCampaign($campaign);
                    $newsletterMailSender->setSubscriber($subscriber);
                    $newsletterMailSender->setSender($sender);
                    $newsletterMailSender->setTemplate($template);
                    $sendMailResponse = $newsletterMailSender->sendMail();

                    if ($sendMailResponse['success']) {
                        $this->logger->info('Subscriber: ' . $subscriber['name'] . ' (' . $subscriber['email'] . ')');
                        newsletter_campaigns_send_log($campaign['id'], $subscriber['id']);
                        $sendLogCount++;
                    }
                }

                if ($sendLogCount >= $campaign->sending_limit_per_day) {
                    $this->logger->info('Campaign: ' . $campaign->name . ' - daily sending limit reached.');
                }

                $this->logger->info('');
                $this->logger->info('');

            }
        }

        $this->logger->info('Process Campaigns Complete');

    }

}

class Logger {

    public function info($msg) {
        echo $msg . "\n";
    }

    public function warn($msg) {
        echo $msg . "\n";
    }

    public function error($msg) {
        echo $msg . "\n";
    }

}
