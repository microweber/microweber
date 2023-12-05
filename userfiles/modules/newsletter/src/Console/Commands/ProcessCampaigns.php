<?php

namespace MicroweberPackages\Modules\Newsletter\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;

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


        for ($i = 0; $i < 5000; $i++) {
            newsletter_save_subscriber(array(
                'email' => 'test' . $i . '@test.com',
                'name' => 'Test ' . $i,
                'subscribed_for' => array(4)
            ));
        }
        return;

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

                $template = newsletter_get_template(array("id"=>$campaign->email_template_id));
                $subscribers = newsletter_get_subscribers_for_list($campaign->list_id);
                $sender = newsletter_get_sender(array("id"=>$campaign->sender_account_id));

                foreach($subscribers as $subscriber) {

                    $findCampaignSendLog = NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)
                        ->where('subscriber_id', $subscriber['id'])
                        ->where('is_sent', 1)
                        ->first();
                    if ($findCampaignSendLog) {
                        $this->warn('Subscriber: ' . $subscriber['name'] . ' (' . $subscriber['email'] . ') - already sent');
                        continue;
                    }

//                    $newsletterMailSender = new \Newsletter\Senders\NewsletterMailSender();
//                    $newsletterMailSender->setCampaign($campaign);
//                    $newsletterMailSender->setSubscriber($subscriber);
//                    $newsletterMailSender->setSender($sender);
//                    $newsletterMailSender->setTemplate($template);
//                    $sendMailResponse = $newsletterMailSender->sendMail();

                    $sendMailResponse = [];
                    $sendMailResponse['success'] = true;

                    $this->warn('Subscriber: ' . $subscriber['name'] . ' (' . $subscriber['email'] . ')');

                    if ($sendMailResponse['success']) {
                        newsletter_campaigns_send_log($campaign['id'], $subscriber['id']);
                    }

                }

            }
        }


        return 0;
    }
}
