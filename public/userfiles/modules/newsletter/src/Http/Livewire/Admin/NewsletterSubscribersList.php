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
    public $listId;
    public $campaignsCount = 0;
    public $listsCount = 0;
    public $emailsSentCount = 0;

    protected $queryString = ['keyword', 'listId'];
    protected $listeners = [
        'newsletterSubscribersListUpdated' => 'render',
        'refreshSubscribers'=>'$refresh',
        'filterSubscribersByListId'=>'filterSubscribersByListId'
    ];

    public $checked = [];
    public $selectAll = false;

    public function filterSubscribersByListId($listId)
    {
        $this->listId = $listId;
        $this->gotoPage(1);
    }

    public function delete($id)
    {
        $subscriber = NewsletterSubscriber::find($id);
        if ($subscriber) {
            $subscriber->delete();
        }
    }

    public function deleteSelected()
    {
        if (!empty($this->checked)) {
            NewsletterSubscriber::whereIn('id', $this->checked)->delete();
            $this->checked = [];
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

        if ($this->listId > 0) {
            $subscribersQuery->whereHas('lists', function ($query) {
                $query->where('list_id', $this->listId);
            });
        }

        if (!empty($this->keyword)) {
            $subscribersQuery->where('email', 'like', '%' . $this->keyword . '%');
            $subscribersQuery->orWhere('name', 'like', '%' . $this->keyword . '%');
        }

        $subscribers = $subscribersQuery->paginate(8);

        return view('microweber-module-newsletter::livewire.admin.subscribers.list', [
            'subscribers' => $subscribers,
            'lists' => NewsletterList::all(),
        ]);
    }

}
