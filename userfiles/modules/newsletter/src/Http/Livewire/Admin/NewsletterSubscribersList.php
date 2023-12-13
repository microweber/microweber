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
    protected $listeners = [
        'newsletterSubscribersListUpdated' => 'render',
        'refreshSubscribers'=>'$refresh'
    ];

    public $checked = [];
    public $selectAll = false;

    public function delete($id)
    {
        $subscriber = NewsletterSubscriber::find($id);
        if ($subscriber) {
            $subscriber->delete();
        }
    }

    public function selectAll()
    {
        if ($this->selectAll) {
            $this->selectAll = false;
            $this->checked = [];
        } else {
            $this->selectAll = true;
            $this->checked = NewsletterSubscriber::pluck('id')->map(fn($item) => (string)$item)->toArray();
        }
    }

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
