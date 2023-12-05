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

                $template = newsletter_get_template(array("id"=>$campaign->email_template_id));
                $subscribers = newsletter_get_subscribers_for_list($campaign->list_id);
                $sender = newsletter_get_sender(array("id"=>$campaign->sender_account_id));

                foreach($subscribers as $subscriber) {

                    $newsletterMailSender = new \Newsletter\Senders\NewsletterMailSender();
                    $newsletterMailSender->setCampaign($campaign);
                    $newsletterMailSender->setSubscriber($subscriber);
                    $newsletterMailSender->setSender($sender);
                    $newsletterMailSender->setTemplate($template);

                    $sendMailResponse = $newsletterMailSender->sendMail();

                    echo 'Subscriber: ' . $subscriber['name'] . ' (' . $subscriber['email'] . ') <br />';

                    if ($sendMailResponse['success']) {
                        echo '<font style="color:green;">' . $sendMailResponse['message'] . '</font>';
                        newsletter_campaigns_send_log($campaign['id'], $subscriber['id']);
                    } else {
                        echo '<font style="color:red;">' . $sendMailResponse['message'] . '</font>';
                    }

                    echo '<br />';
                }


            }
        }


        return 0;
    }
}
