<?php

declare(strict_types=1);

namespace MicroweberPackages\Pagination;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

/**
 * Unified paginator that renders pagination controls for any framework / style.
 *
 * Supports:
 *  - Multiple themes: bootstrap, bootstrap-flex, bootstrap-dropdown, tailwind,
 *    tailwind-flex, tailwind-dropdown
 *  - Size variants: sm, md, lg, xl
 *  - Windowed page links for large page counts (offset-based window)
 *  - Custom CSS classes for wrapper, items, active state, disabled state, links
 *  - View overrides from any registered view namespace
 */
class Paginator implements Htmlable
{
    protected int $currentPage;
    protected int $lastPage;
    protected string $baseUrl;
    protected string $pageName;
    protected array $queryParams;
    protected string $theme;
    protected string $size;
    protected int $onEachSide;
    protected array $customClasses;
    protected ?string $viewOverride;

    /**
     * @param array $options {
     *     @type int    $currentPage  Current active page (required)
     *     @type int    $lastPage     Total number of pages (required)
     *     @type string $baseUrl      Base URL for page links (default: current URL)
     *     @type string $pageName     Query parameter name for the page (default: 'page')
     *     @type array  $queryParams  Additional query parameters to preserve
     *     @type string $theme        Pagination theme (default: 'bootstrap')
     *     @type string $size         Size variant: sm, md, lg, xl (default: 'md')
     *     @type int    $onEachSide   Number of pages to show on each side of current (default: 5)
     *     @type array  $customClasses Custom CSS classes override
     *     @type string $view         Override the blade view used for rendering
     * }
     */
    public function __construct(array $options = [])
    {
        $this->currentPage  = max(1, (int) ($options['currentPage'] ?? 1));
        $this->lastPage     = max(1, (int) ($options['lastPage'] ?? 1));
        $this->baseUrl      = (string) ($options['baseUrl'] ?? '');
        $this->pageName     = (string) ($options['pageName'] ?? 'page');
        $this->queryParams  = (array) ($options['queryParams'] ?? []);
        $this->theme        = (string) ($options['theme'] ?? 'bootstrap');
        $this->size         = (string) ($options['size'] ?? 'md');
        $this->onEachSide   = max(1, (int) ($options['onEachSide'] ?? 5));
        $this->customClasses = (array) ($options['customClasses'] ?? []);
        $this->viewOverride = $options['view'] ?? null;
    }

    // ── Fluent setters ─────────────────────────────────────────────

    public function currentPage(int $page): static
    {
        $this->currentPage = max(1, $page);
        return $this;
    }

    public function lastPage(int $page): static
    {
        $this->lastPage = max(1, $page);
        return $this;
    }

    public function baseUrl(string $url): static
    {
        $this->baseUrl = $url;
        return $this;
    }

    public function pageName(string $name): static
    {
        $this->pageName = $name;
        return $this;
    }

    public function queryParams(array $params): static
    {
        $this->queryParams = $params;
        return $this;
    }

    public function theme(string $theme): static
    {
        $this->theme = $theme;
        return $this;
    }

    public function size(string $size): static
    {
        $this->size = $size;
        return $this;
    }

    public function onEachSide(int $count): static
    {
        $this->onEachSide = max(1, $count);
        return $this;
    }

    public function customClasses(array $classes): static
    {
        $this->customClasses = $classes;
        return $this;
    }

    public function view(string $view): static
    {
        $this->viewOverride = $view;
        return $this;
    }

    // ── Getters ────────────────────────────────────────────────────

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getPageName(): string
    {
        return $this->pageName;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getOnEachSide(): int
    {
        return $this->onEachSide;
    }

    public function getCustomClasses(): array
    {
        return $this->customClasses;
    }

    // ── Page window computation ────────────────────────────────────

    /**
     * Whether there are enough pages to warrant showing pagination.
     */
    public function hasPages(): bool
    {
        return $this->lastPage > 1;
    }

    /**
     * Whether we are on the first page.
     */
    public function onFirstPage(): bool
    {
        return $this->currentPage <= 1;
    }

    /**
     * Whether there are more pages after current.
     */
    public function hasMorePages(): bool
    {
        return $this->currentPage < $this->lastPage;
    }

    /**
     * Build the page URL for a given page number.
     */
    public function url(int $page): string
    {
        $page = max(1, min($page, $this->lastPage));

        $params = array_merge($this->queryParams, [$this->pageName => $page]);
        $query = http_build_query($params, '', '&');

        $base = rtrim($this->baseUrl, '?&');
        $separator = str_contains($base, '?') ? '&' : '?';

        return $base . $separator . $query;
    }

    /**
     * URL for the previous page.
     */
    public function previousPageUrl(): string
    {
        return $this->url(max(1, $this->currentPage - 1));
    }

    /**
     * URL for the next page.
     */
    public function nextPageUrl(): string
    {
        return $this->url(min($this->lastPage, $this->currentPage + 1));
    }

    /**
     * URL for the first page.
     */
    public function firstPageUrl(): string
    {
        return $this->url(1);
    }

    /**
     * URL for the last page.
     */
    public function lastPageUrl(): string
    {
        return $this->url($this->lastPage);
    }

    /**
     * Compute the windowed array of page elements.
     *
     * Returns an array of items, each being either:
     *  - ['type' => 'page', 'page' => int, 'url' => string, 'active' => bool]
     *  - ['type' => 'dots']
     *
     * For example with currentPage=100, lastPage=1000, onEachSide=5:
     *  1, ..., 95, 96, 97, 98, 99, |100|, 101, 102, 103, 104, 105, ..., 1000
     */
    public function elements(): array
    {
        if (!$this->hasPages()) {
            return [];
        }

        $window = $this->computeWindow();
        $elements = [];

        // First page (always shown)
        if ($window['start'] > 1) {
            $elements[] = $this->pageElement(1);
            if ($window['start'] > 2) {
                $elements[] = ['type' => 'dots'];
            }
        }

        // Window pages
        for ($i = $window['start']; $i <= $window['end']; $i++) {
            $elements[] = $this->pageElement($i);
        }

        // Last page (always shown)
        if ($window['end'] < $this->lastPage) {
            if ($window['end'] < $this->lastPage - 1) {
                $elements[] = ['type' => 'dots'];
            }
            $elements[] = $this->pageElement($this->lastPage);
        }

        return $elements;
    }

    /**
     * Get a flat array of page links suitable for legacy templates.
     *
     * Returns an array of arrays with keys:
     *   'attributes' => ['class', 'current', 'data-page-number', 'href']
     *   'title' => string
     */
    public function toLegacyArray(): array
    {
        $links = [];
        $elements = $this->elements();

        // Prepend prev
        if (!$this->onFirstPage()) {
            $links[] = [
                'attributes' => [
                    'class'            => '',
                    'current'          => false,
                    'data-page-number' => $this->currentPage - 1,
                    'href'             => $this->previousPageUrl(),
                ],
                'title' => '‹',
            ];
        }

        foreach ($elements as $el) {
            if ($el['type'] === 'dots') {
                $links[] = [
                    'attributes' => [
                        'class'            => 'disabled',
                        'current'          => false,
                        'data-page-number' => '',
                        'href'             => '#',
                    ],
                    'title' => '…',
                ];
            } else {
                $links[] = [
                    'attributes' => [
                        'class'            => $el['active'] ? 'active' : '',
                        'current'          => $el['active'],
                        'data-page-number' => $el['page'],
                        'href'             => $el['url'],
                    ],
                    'title' => (string) $el['page'],
                ];
            }
        }

        // Append next
        if ($this->hasMorePages()) {
            $links[] = [
                'attributes' => [
                    'class'            => '',
                    'current'          => false,
                    'data-page-number' => $this->currentPage + 1,
                    'href'             => $this->nextPageUrl(),
                ],
                'title' => '›',
            ];
        }

        return $links;
    }

    // ── CSS class resolution ───────────────────────────────────────

    /**
     * Resolve a CSS class key with custom override support.
     *
     * Keys: wrapper, list, item, link, active, disabled, dots, sizeClass
     */
    public function resolveClass(string $key): string
    {
        if (isset($this->customClasses[$key])) {
            return $this->customClasses[$key];
        }

        $defaults = $this->getThemeDefaults();

        return $defaults[$key] ?? '';
    }

    /**
     * Get the pagination size class for the current theme + size.
     */
    public function sizeClass(): string
    {
        if (isset($this->customClasses['sizeClass'])) {
            return $this->customClasses['sizeClass'];
        }

        $map = $this->getSizeMap();

        return $map[$this->size] ?? '';
    }

    // ── Rendering ──────────────────────────────────────────────────

    /**
     * Render the paginator to HTML.
     */
    public function render(): string
    {
        if (!$this->hasPages()) {
            return '';
        }

        $viewName = $this->resolveView();

        return view($viewName, ['paginator' => $this])->render();
    }

    /**
     * Implement Htmlable so the paginator can be used in Blade with {!! $paginator !!}
     */
    public function toHtml(): string
    {
        return $this->render();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    // ── Static factory ─────────────────────────────────────────────

    /**
     * Create a paginator from a Laravel LengthAwarePaginator.
     */
    public static function fromLaravel(\Illuminate\Pagination\LengthAwarePaginator $laravelPaginator, array $options = []): static
    {
        $options['currentPage'] = $laravelPaginator->currentPage();
        $options['lastPage']    = $laravelPaginator->lastPage();
        $options['baseUrl']     = $options['baseUrl'] ?? strtok($laravelPaginator->path() ?? '', '?');
        $options['pageName']    = $options['pageName'] ?? $laravelPaginator->getPageName();

        return new static($options);
    }

    // ── Internal helpers ───────────────────────────────────────────

    protected function computeWindow(): array
    {
        $start = max(1, $this->currentPage - $this->onEachSide);
        $end   = min($this->lastPage, $this->currentPage + $this->onEachSide);

        // Adjust if near beginning
        if ($start <= 1) {
            $end = min($this->lastPage, 1 + ($this->onEachSide * 2));
            $start = 1;
        }

        // Adjust if near end
        if ($end >= $this->lastPage) {
            $start = max(1, $this->lastPage - ($this->onEachSide * 2));
            $end = $this->lastPage;
        }

        return ['start' => $start, 'end' => $end];
    }

    protected function pageElement(int $page): array
    {
        return [
            'type'   => 'page',
            'page'   => $page,
            'url'    => $this->url($page),
            'active' => $page === $this->currentPage,
        ];
    }

    protected function resolveView(): string
    {
        if ($this->viewOverride) {
            return $this->viewOverride;
        }

        $themeMap = [
            'bootstrap'          => 'mw-pagination::bootstrap.default',
            'bootstrap-flex'     => 'mw-pagination::bootstrap.flex',
            'bootstrap-dropdown' => 'mw-pagination::bootstrap.dropdown',
            'tailwind'           => 'mw-pagination::tailwind.default',
            'tailwind-flex'      => 'mw-pagination::tailwind.flex',
            'tailwind-dropdown'  => 'mw-pagination::tailwind.dropdown',
        ];

        return $themeMap[$this->theme] ?? $themeMap['bootstrap'];
    }

    protected function getThemeDefaults(): array
    {
        $isTailwind = str_starts_with($this->theme, 'tailwind');

        if ($isTailwind) {
            return [
                'wrapper'  => 'flex items-center justify-center',
                'list'     => 'inline-flex -space-x-px',
                'item'     => '',
                'link'     => 'px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700',
                'active'   => 'px-3 py-2 leading-tight text-blue-600 border border-blue-300 bg-blue-50',
                'disabled' => 'px-3 py-2 leading-tight text-gray-300 bg-white border border-gray-300 cursor-not-allowed',
                'dots'     => 'px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300',
            ];
        }

        // Bootstrap variants
        return [
            'wrapper'  => 'd-flex justify-content-center',
            'list'     => 'pagination',
            'item'     => 'page-item',
            'link'     => 'page-link',
            'active'   => 'active',
            'disabled' => 'disabled',
            'dots'     => 'page-link',
        ];
    }

    protected function getSizeMap(): array
    {
        $isTailwind = str_starts_with($this->theme, 'tailwind');

        if ($isTailwind) {
            return [
                'sm' => 'text-xs',
                'md' => 'text-sm',
                'lg' => 'text-base',
                'xl' => 'text-lg',
            ];
        }

        return [
            'sm' => 'pagination-sm',
            'md' => '',
            'lg' => 'pagination-lg',
            'xl' => 'pagination-lg',
        ];
    }
}