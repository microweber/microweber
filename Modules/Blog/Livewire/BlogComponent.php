<?php

namespace Modules\Blog\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Post\Models\Post;
use Modules\Tag\Models\Tag;

class BlogComponent extends Component
{
    use WithPagination;

    public string $moduleId;
    public $postsPerPage = 10;
    public $layout = 'grid';
    public string $moduleType = 'blog';
    public string $moduleTemplateNamespace = 'modules.blog::livewire.blog';
    public string $template = 'default';
    public $showCategories = true;
    public $showTags = true;
    public $search = '';
    public $selectedCategory = null;
    public $selectedTags = [];
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $limit = 10;
    public $activeFilters = [];


    public array $settings = [];

    protected $queryString = [
        'search',
        'selectedCategory',
        'selectedTags',
        'sortBy',
        'sortOrder',
        'limit',
        'template'
    ];

    public function mount($moduleId = null)
    {
        $this->moduleId = $moduleId;

        // Get module settings if available
        if ($this->moduleId) {

            $settings = get_module_options($this->moduleId, $this->moduleType);

            $this->postsPerPage = $settings['options']['posts_per_page'] ?? 10;
            $this->limit = $this->postsPerPage;
            $this->layout = $settings['options']['layout'] ?? 'grid';
            $this->showCategories = $settings['options']['show_categories'] ?? true;
            $this->showTags = $settings['options']['show_tags'] ?? true;

            // task-2026-05-22-1b49a3 / AI-911 — wire the Sort by setting saved
            // by BlogSettings::order_by to the component's sortBy/sortOrder.
            // Pre-fix these two properties were hardcoded ('created_at', 'desc')
            // so the admin dropdown had no effect at runtime (Stage-1 settings-
            // not-wired defect, same family as AI-904/905/906/907).
            $orderBy = $settings['options']['order_by'] ?? 'date_desc';
            [$this->sortBy, $this->sortOrder] = match($orderBy) {
                'date_asc'   => ['created_at', 'asc'],
                'title_asc'  => ['title', 'asc'],
                'title_desc' => ['title', 'desc'],
                default      => ['created_at', 'desc'],
            };
        }

        $this->updateActiveFilters();
    }

    protected function updateActiveFilters()
    {
        $this->activeFilters = [];

        if ($this->search) {
            $this->activeFilters[] = "Search: {$this->search}";
        }

        if ($this->selectedCategory) {
            $category = \Modules\Category\Models\Category::find($this->selectedCategory);
            if ($category) {
                $this->activeFilters[] = "Category: {$category->title}";
            }
        }

        if (!empty($this->selectedTags)) {
            $tags = \Modules\Tag\Models\Tag::whereIn('id', $this->selectedTags)->get();
            foreach ($tags as $tag) {
                $this->activeFilters[] = "Tag: {$tag->name}";
            }
        }
    }

    public function removeFilter($filter)
    {
        if (str_starts_with($filter, 'Search: ')) {
            $this->search = '';
        } elseif (str_starts_with($filter, 'Category: ')) {
            $this->selectedCategory = null;
        } elseif (str_starts_with($filter, 'Tag: ')) {
            $tagName = str_replace('Tag: ', '', $filter);
            $tag = Tag::where('name', $tagName)->first();
            if ($tag) {
                $this->selectedTags = array_diff($this->selectedTags, [$tag->id]);
            }
        }

        $this->updateActiveFilters();
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->updateActiveFilters();
        $this->resetPage();
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->updateActiveFilters();
        $this->resetPage();
    }

    public function toggleTag($tagId)
    {
        if (in_array($tagId, $this->selectedTags)) {
            $this->selectedTags = array_diff($this->selectedTags, [$tagId]);
        } else {
            $this->selectedTags[] = $tagId;
        }
        $this->updateActiveFilters();
        $this->resetPage();
    }

    public function getPosts()
    {
        $query = Post::query()
            ->where('content_type', 'post')
            ->where('is_active', 1)
            ->orderBy($this->sortBy, $this->sortOrder);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedCategory) {
            $query->whereCategoryIds($this->selectedCategory);
        }

        if (!empty($this->selectedTags)) {
            $query->withAnyTag($this->selectedTags);
        }

        return $query->paginate($this->limit);
    }

    public function render()
    {
        $posts = $this->getPosts();

        $viewName = $this->moduleTemplateNamespace . '.' . $this->template;
        if (!view()->exists($viewName)) {
            $viewName = $this->moduleTemplateNamespace . '.default';
        }

        // task-2026-05-22-7c7804 / AI-904+AI-905: pass layout + visibility flags so
        // default.blade.php and post-card.blade.php can act on them (AI-872 Slice 1
        // stored them in component properties but never forwarded them to the view).
        return view($viewName, [
            'moduleId'       => $this->moduleId,
            'moduleType'     => $this->moduleType,
            'posts'          => $posts,
            'total'          => $posts->total(),
            'count'          => $posts->count(),
            'layout'         => $this->layout,
            'showCategories' => $this->showCategories,
            'showTags'       => $this->showTags,
        ]);
    }
}
