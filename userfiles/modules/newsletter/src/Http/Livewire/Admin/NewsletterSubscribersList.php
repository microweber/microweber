<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class NewsletterSubscribersList extends Component
{
    use WithPagination;

    public $keyword;
    public $campaignsCount = 0;
    public $listsCount = 0;
    public $emailsSentCount = 0;

    protected $queryString = ['keyword'];

    protected $listeners = ['newsletterSubscribersListUpdated' => 'render'];

    public function render()
    {

        $this->campaignsCount = NewsletterCampaign::count();
        $this->listsCount = NewsletterList::count();
        $this->emailsSentCount = NewsletterCampaignsSendLog::count();

        $subscribersQuery = NewsletterSubscriber::query();

        if (!empty($this->keyword)) {
            $subscribersQuery->where('email', 'like', '%' . $this->keyword . '%');
            $subscribersQuery->orWhere('name', 'like', '%' . $this->keyword . '%');
        }

        $subscribers = $subscribersQuery->paginate(8);

        return view('microweber-module-newsletter::livewire.admin.subscribers.list', [
            'subscribers' => $subscribers
        ]);
    }

}
