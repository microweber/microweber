<?php

namespace MicroweberPackages\Marketplace\Http\Livewire\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use MicroweberPackages\Package\MicroweberComposerClient;

class Marketplace extends Component
{
    public $keyword = '';
    public $marketplace = [];
    public $category = 'microweber-template';

    public $queryString = [
        'keyword',
        'category'
    ];

    public function updatedKeyword($keyword)
    {
        $this->filter();
    }

    public function filterCategory($category)
    {
        $this->category = $category;
        $this->filter();
    }

    public function mount()
    {
        $this->filter();
    }

    public function reloadPackages()
    {
        Cache::forget('livewire-marketplace');
        $this->filter();
    }

    public function filter()
    {

        $packages = Cache::remember('livewire-marketplace', Carbon::now()->addHours(12), function () {
            $marketplace = new MicroweberComposerClient();
            return $marketplace->search();
        });

        $latestVersions = [];

        $allowedCategories = [
            'microweber-module',
            'microweber-template'
        ];
        if (!empty($this->category)) {
            if ($this->category !== 'all') {
                $allowedCategories = [];
                $allowedCategories[] = $this->category;
            }
        }
        foreach ($packages as $packageName=>$package) {

            $latestVersionPackage = end($package);

            if (isset($latestVersionPackage['type']) && !in_array($latestVersionPackage['type'], $allowedCategories)) {
                continue;
            }

            $searchKeywords = [];
            if (isset($latestVersionPackage['keywords']) && is_array($latestVersionPackage['keywords'])) {
                $searchKeywords = array_merge($searchKeywords, $latestVersionPackage['keywords']);
            }

            if (isset($latestVersionPackage['extra']['categories']) && is_array($latestVersionPackage['extra']['categories'])) {
                $searchKeywords = array_merge($searchKeywords, $latestVersionPackage['extra']['categories']);
            }

            if (!empty($this->keyword)) {
                $founded = false;
                if (in_array($this->keyword, $searchKeywords)) {
                    $founded = true;
                }
                if (isset($latestVersionPackage['description'])) {
                    if (mb_strpos($latestVersionPackage['description'], $this->keyword) !== false) {
                        $founded = true;
                    }
                }
                if (!$founded) {
                    continue;
                }
            }

            $latestVersions[$packageName] = $latestVersionPackage;
        }

        $this->marketplace = $latestVersions;
    }

    public function render()
    {
        return view('marketplace::admin.marketplace.livewire.index');
    }
}
