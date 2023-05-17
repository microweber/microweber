<?php

namespace MicroweberPackages\Marketplace\Http\Livewire\Admin;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Package\MicroweberComposerClient;
use MicroweberPackages\Package\MicroweberComposerPackage;

class Marketplace extends Component
{
    use WithPagination;

    public $keyword = '';
    public $marketplace;
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
        $this->gotoPage(1);
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

            $latestVersionPackage = MicroweberComposerPackage::format($latestVersionPackage);

            $latestVersions[$packageName] = $latestVersionPackage;
        }

        $this->marketplace = $latestVersions;
    }

    /**

     * The attributes that are mass assignable.

     *
     * @var array
     */
    public function paginate($items, $perPage = 35, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function render()
    {
        $marketplacePagination = $this->paginate($this->marketplace);

        return view('marketplace::admin.marketplace.livewire.index',compact('marketplacePagination'));
    }
}
