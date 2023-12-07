<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;

class NewsletterCampaignsLogModal extends AdminModalComponent
{
    public $campaignLog = [];

    public function render()
    {
        return view('microweber-module-newsletter::livewire.admin.campaigns-log-modal');
    }

    public function mount($campaignId)
    {
        $findCampaign = NewsletterCampaign::where('id', $campaignId)->first();
        if ($findCampaign) {
            $findCampaignSendLog = NewsletterCampaignsSendLog::where('campaign_id', $campaignId)
                ->where('is_sent', 1)
                ->get();
            if ($findCampaignSendLog) {
                $this->campaignLog = $findCampaignSendLog->toArray();
            }
        }
    }
}
