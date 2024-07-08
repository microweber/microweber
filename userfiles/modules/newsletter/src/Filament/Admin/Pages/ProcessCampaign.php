<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;


use Filament\Pages\Page;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

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
        $findSubscribersQuery = NewsletterSubscriber::query();
        $findSubscribersQuery->whereHas('lists', function ($query) use($campaign) {
            $query->where('list_id', $campaign->list_id);
        });

        $batchSize = 5;
        $findSubscribers = $findSubscribersQuery->paginate($batchSize, ['*'], 'step', $this->step);

        $this->step = $this->step + 1;
        $this->lastProcessed = $findSubscribers->items();
        $this->totalSteps = $findSubscribers->lastPage();


        if ($this->step >= $this->totalSteps) {
            $this->finished = 1;
            $this->dispatch('campaign-finished');
        }
    }
}
