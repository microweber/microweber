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

     public array $lastProcessed = [];


    public function mount($id)
    {
        $findCampaign = NewsletterCampaign::where('id', $id)->first();
        if ($findCampaign) {
            $this->campaign = $findCampaign;
        }
    }

    #[On('start-processing-campaign')]
    public function startProcessingCampaign()
    {
        $campaign = $this->campaign;
        $findSubscribers = NewsletterSubscriber::whereHas('lists', function ($query) use($campaign) {
            $query->where('list_id', $campaign->list_id);
        })->get();

    }

    #[On('execute-next-step')]
    public function executeNextStep()
    {
        if ($this->finished) {
            $this->dispatch('campaign-finished');
            return;
        }

        $this->step = $this->step + 1;


        $this->lastProcessed[] = $this->step;
        if (count($this->lastProcessed) > 10) {
            // cut the last 10
            $this->lastProcessed = array_slice($this->lastProcessed, -10);
        }

        if ($this->step > 100) {
            $this->finished = 1;
            $this->dispatch('campaign-finished');
        }
    }
}
