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

        /*
         * AI-130 / SEC-05 (cycle-123 2026-05-09): defense-in-depth
         * keyword sanitization. The downstream `get_content()` path
         * uses Eloquent's parameterized query builder so SQLi is
         * already covered at the infrastructure layer; this strip
         * is for stored-XSS protection (the keyword is echoed back
         * to the search-results view via Blade `{{ }}` which already
         * HTML-escapes — but Livewire occasionally emits unescaped
         * snippets in update payloads).
         *
         *   - strip_tags removes `<script>`, `<svg>`, `<img>`, etc.
         *   - mb_substr caps length to 200 chars so an attacker can't
         *     force the LIKE pattern to scan with a 10MB string.
         */
        $keyword = strip_tags((string) $this->searchQuery);
        $keyword = mb_substr($keyword, 0, 200);

        $params = [
            'search_in_fields' => 'title,content,description',
            'keyword' => $keyword,
            'limit' => 10,
            'no_cache' => true,
            'search_in' => 'content',
        ];

        if ($this->dataContentId > 0) {
            $params['parent'] = (int) $this->dataContentId;
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
