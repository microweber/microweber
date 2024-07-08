<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;


use Filament\Pages\Page;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;
use MicroweberPackages\Modules\Newsletter\Senders\NewsletterMailSender;

class ProcessCampaign extends Page
{
    protected static ?string $slug = 'process-campaign/{id}';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.process-campaign';

    protected static bool $shouldRegisterNavigation = false;

    public $listeners = [

    ];

    public $campaign = null;

    #[Url]
    public ?int $step = 0;

     #[Url]
    public ?int $finished = 0;

     public $totalSteps = 0;

     public array $lastProcessed = [];


    public function mount($id)
    {
        $findCampaign = NewsletterCampaign::where('id', $id)->first();
        if ($findCampaign) {
            $this->campaign = $findCampaign;
        }
    }

    #[On('execute-next-step')]
    public function executeNextStep()
    {
        if ($this->finished) {
            $this->dispatch('campaign-finished');
            return;
        }

        $campaign = $this->campaign;
        $sender = NewsletterSenderAccount::where('id', $campaign->sender_account_id)->first();
        $template = NewsletterTemplate::where('id', $campaign->email_template_id)->first();

        $findSubscribersQuery = NewsletterSubscriber::query();
        $findSubscribersQuery->whereHas('lists', function ($query) use($campaign) {
            $query->where('list_id', $campaign->list_id);
        });

        $batchSize = 1;
        $findSubscribers = $findSubscribersQuery->paginate($batchSize, ['*'], 'step', $this->step);

        $subscribers = $findSubscribers->items();
        if (empty($subscribers)) {
            $this->finished = 1;
            $this->dispatch('campaign-finished');
            return;
        }

        $this->step = $this->step + 1;
        $this->totalSteps = $findSubscribers->lastPage();

        $sliceLatestSend = 8;
        foreach ($subscribers as $subscriber) {

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
                $subscriber['error'] = true;
                if (isset($sendMailResponse['message'])) {
                    $subscriber['error_message'] = $sendMailResponse['message'];
                }
            }

            $this->lastProcessed[] = $subscriber;
            if (count($this->lastProcessed) >= $sliceLatestSend) {
                $this->lastProcessed = array_slice($this->lastProcessed, -$sliceLatestSend, $sliceLatestSend);
            }
        }

        if ($this->step >= $this->totalSteps) {
            $this->finished = 1;
            $this->dispatch('campaign-finished');
        }
    }
}
