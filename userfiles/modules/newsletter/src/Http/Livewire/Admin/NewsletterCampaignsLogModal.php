<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use Livewire\WithPagination;
use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;

class NewsletterCampaignsLogModal extends AdminModalComponent
{
    use WithPagination;

    public $campaignId = 0;

    public function render()
    {
        $campaignLog = null;
        $findCampaign = NewsletterCampaign::where('id', $this->campaignId)->first();
        if ($findCampaign) {
            $findCampaignSendLog = NewsletterCampaignsSendLog::where('campaign_id', $findCampaign->id)
                ->where('is_sent', 1)
                ->with('subscriber')
                ->paginate(10);

            if ($findCampaignSendLog) {
                $campaignLog = $findCampaignSendLog;
            }
        }

        return view('microweber-module-newsletter::livewire.admin.campaigns-log-modal',[
            'campaign' => $findCampaign,
            'campaignLog' => $campaignLog
        ]);
    }

    public function mount($campaignId = 0)
    {
        $this->campaignId = $campaignId;
    }
}
