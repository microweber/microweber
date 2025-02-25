<?php

namespace Modules\Search\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class SearchComponent extends Component
{
    use WithPagination;

    public $moduleId;
    public $placeholder = 'Search...';
    public $dataContentId = 0;
    public $autocomplete = false;
    public $searchQuery = '';
    public $searchResults = [];
    public $isLoading = false;

    public function mount($moduleId = null)
    {
        $this->moduleId = $moduleId;

        // Get module settings if available
        if ($this->moduleId) {
            $settings = get_module_options($this->moduleId, 'search');

            $this->placeholder = $settings['options']['placeholder'] ?? 'Search...';
            $this->dataContentId = $settings['options']['data-content-id'] ?? 0;
            $this->autocomplete = $settings['options']['autocomplete'] ?? false;
        }

        // Check for URL hash parameter
        $hash = request()->get('keyword');
        if ($hash) {
            $this->searchQuery = urldecode($hash);
            $this->search();
        }
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) > 2) {
            $this->search();
        } else {
            $this->searchResults = [];
        }
    }

    public function search()
    {
        $this->isLoading = true;

        $params = [
            'search_in_fields' => 'title,content,description',
            'keyword' => $this->searchQuery,
            'limit' => 10,
            'no_cache' => true,
            'search_in' => 'content',
        ];

        if ($this->dataContentId > 0) {
            $params['parent'] = $this->dataContentId;
        }

        $this->searchResults = get_content($params);
        $this->isLoading = false;
    }

    public function clearSearch()
    {
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function render()
    {
        return view('modules.search::livewire.search.index', [
            'results' => $this->searchResults,
        ]);
    }
}
