<?php

namespace MicroweberPackages\Template\ContentJson;

use Modules\Content\Models\Content;
use Modules\Menu\Models\Menu;

/**
 * Applies a flexible content.json payload (pages, posts, products and menus) to
 * the site — the simplified, human-authorable counterpart to the mw_default_content.zip
 * backup. Page/post/product bodies may be supplied as raw "html"/"content" or as a
 * "sections" array (rendered by {@see SectionRenderer}).
 *
 * Schema (every field optional unless noted):
 * {
 *   "template": "Alfa",                     // asset base + default active_site_template
 *   "data": { "preview": {...}, ... },      // design-level data passed to the section renderer
 *   "pages": [{
 *       "url": "home",            (required) "title": "Home",
 *       "is_home": true, "content_type": "page", "subtype": "static",
 *       "layout_file": "standalone.blade.php", "active_site_template": "Alfa",
 *       "parent": "<url-of-parent>",
 *       "html": "<section>…</section>"  | "content": "…"  | "sections": [ {type:…}, … ],
 *       "data": {...},                    // per-page section-renderer data (falls back to top-level "data")
 *       "menu": ["header_menu"]           // add this page to these menus
 *   }],
 *   "posts":    [ { same page shape, content_type defaults to "post",    "parent": "<blog-url>" } ],
 *   "products": [ { same page shape, content_type "product", "price": 9.99, "sku": "…", "qty": "nolimit" } ],
 *   "menus": { "header_menu": [ {"page":"home","title":"Home"}, {"url":"https://x","title":"Ext"} ], "footer_menu": [...] }
 * }
 */
class ContentJsonInstaller
{
    protected array $data = [];
    protected ?string $template = null;
    protected string $importType = 'full';   // full | delete | only_media
    protected string $language = 'en';
    protected $logger = null;

    /** url => created/updated content id, built during the content pass and used to wire menus. */
    protected array $urlToId = [];
    protected array $log = [];

    public function setData(array $data): self { $this->data = $data; return $this; }

    public function setFile(string $path): self
    {
        $raw = is_file($path) ? file_get_contents($path) : false;
        $json = $raw !== false ? json_decode($raw, true) : null;
        $this->data = is_array($json) ? $json : [];
        return $this;
    }

    public function setTemplate(?string $template): self { $this->template = $template; return $this; }
    public function setImportType(string $type): self { $this->importType = $type; return $this; }
    public function setLanguage(?string $lang): self { if ($lang) { $this->language = $lang; } return $this; }
    public function setLogger($logger): self { $this->logger = $logger; return $this; }

    public static function templateFile(string $template): string
    {
        // template_full_dir() resolves both core Templates/ and the extra template
        // roots (ExtraTemplates/) where JSON-design templates live.
        $dir = function_exists('template_full_dir')
            ? template_full_dir($template)
            : rtrim(templates_dir(), '/\\') . DIRECTORY_SEPARATOR . $template . DIRECTORY_SEPARATOR;
        return rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . 'content.json';
    }

    public function hasContent(): bool
    {
        return !empty($this->data['pages']) || !empty($this->data['posts'])
            || !empty($this->data['products']) || !empty($this->data['menus']);
    }

    /**
     * @return array{done:bool, log:array<int,string>}
     */
    public function install(): array
    {
        if (!$this->hasContent()) {
            return ['done' => false, 'log' => ['content.json has no pages/posts/products/menus']];
        }

        // only_media: content.json carries no media archive (design images are published
        // to public/templates/<name> at import time), so there is nothing to write.
        if ($this->importType === 'only_media') {
            return ['done' => true, 'log' => ['only_media: nothing to import from content.json']];
        }

        if ($this->importType === 'delete') {
            $this->deleteExistingContent();
        }

        foreach (($this->data['pages'] ?? []) as $page) {
            $this->saveContentItem($page, 'page', 'static');
        }
        foreach (($this->data['posts'] ?? []) as $post) {
            $this->saveContentItem($post, 'post', 'static');
        }
        foreach (($this->data['products'] ?? []) as $product) {
            $this->saveProduct($product);
        }

        $this->installMenus();
        $this->applyColorScheme();

        if (function_exists('clearcache')) {
            clearcache();
        }

        return ['done' => true, 'log' => $this->log];
    }

    /** Resolve the active_site_template for an item (explicit → installer template → current). */
    protected function resolveTemplate(array $item): string
    {
        return (string) ($item['active_site_template'] ?? $this->template ?? template_name());
    }

    /** Resolve the page body: explicit html/content wins, else render the sections array. */
    protected function resolveBody(array $item): string
    {
        if (isset($item['html']) && is_string($item['html'])) {
            return $item['html'];
        }
        if (isset($item['content']) && is_string($item['content'])) {
            return $item['content'];
        }
        if (!empty($item['sections']) && is_array($item['sections'])) {
            $data = $item['data'] ?? ($this->data['data'] ?? []);
            if (!is_array($data)) { $data = []; }
            $template = $this->resolveTemplate($item);
            $lname = strtolower($template);
            $data['mw_asset_base'] = '/templates/' . $lname;
            $data['mw_asset_dir'] = function_exists('public_path')
                ? public_path('templates/' . $lname)
                : (base_path('public/templates/' . $lname));
            return (new SectionRenderer())->render($item['sections'], $data);
        }
        return '';
    }

    /** Create or update a page/post content row. */
    protected function saveContentItem(array $item, string $defaultType, string $defaultSubtype): void
    {
        $url = trim((string) ($item['url'] ?? ''));
        if ($url === '') {
            $this->log[] = 'skip: item without url (' . ($item['title'] ?? '?') . ')';
            return;
        }

        $content = Content::where('url', $url)->first() ?: new Content();
        $content->title = (string) ($item['title'] ?? ucfirst(str_replace('-', ' ', $url)));
        $content->url = $url;
        $content->content_type = (string) ($item['content_type'] ?? $defaultType);
        $content->subtype = (string) ($item['subtype'] ?? $defaultSubtype);
        $content->is_active = (int) ($item['is_active'] ?? 1);
        $content->is_deleted = 0;
        if (array_key_exists('is_home', $item)) {
            $content->is_home = $this->boolInt($item['is_home']);
        }
        if (isset($item['layout_file'])) {
            $content->layout_file = (string) $item['layout_file'];
        }
        $content->active_site_template = $this->resolveTemplate($item);
        $content->parent = $this->resolveParent($item);
        $content->content = $this->resolveBody($item);
        if (isset($item['description'])) {
            $content->description = (string) $item['description'];
        }
        $content->save();

        $this->urlToId[$url] = $content->id;
        $this->rememberMenuRequests($url, $item);
        $this->log[] = "{$content->content_type} '{$url}' -> #{$content->id}";
    }

    /** Create or update a product (price persisted via the Product model's price trait). */
    protected function saveProduct(array $item): void
    {
        $url = trim((string) ($item['url'] ?? ''));
        if ($url === '') {
            $this->log[] = 'skip product without url';
            return;
        }

        $productClass = '\\Modules\\Product\\Models\\Product';
        if (!class_exists($productClass)) {
            // Product module unavailable — fall back to a plain product content row.
            $this->saveContentItem(['subtype' => 'product'] + $item, 'product', 'product');
            return;
        }

        /** @var \Modules\Content\Models\Content $product */
        $product = $productClass::where('url', $url)->first() ?: new $productClass();
        $product->title = (string) ($item['title'] ?? ucfirst(str_replace('-', ' ', $url)));
        $product->url = $url;
        $product->content_type = 'product';
        $product->subtype = (string) ($item['subtype'] ?? 'product');
        $product->is_active = (int) ($item['is_active'] ?? 1);
        $product->is_deleted = 0;
        $product->active_site_template = $this->resolveTemplate($item);
        $product->parent = $this->resolveParent($item);
        $product->content = $this->resolveBody($item);
        if (isset($item['description'])) {
            $product->description = (string) $item['description'];
        }
        if (isset($item['price'])) {
            $product->price = $item['price'];
        }
        $product->save();

        $this->urlToId[$url] = $product->id;
        $this->rememberMenuRequests($url, $item);
        $this->log[] = "product '{$url}' -> #{$product->id}" . (isset($item['price']) ? " (price {$item['price']})" : '');
    }

    /** Resolve a parent url reference to its content id (0 when absent/unknown). */
    protected function resolveParent(array $item): int
    {
        if (!isset($item['parent'])) {
            return 0;
        }
        $parent = $item['parent'];
        if (is_numeric($parent)) {
            return (int) $parent;
        }
        $parent = (string) $parent;
        if (isset($this->urlToId[$parent])) {
            return (int) $this->urlToId[$parent];
        }
        $row = Content::where('url', $parent)->first();
        return $row ? (int) $row->id : 0;
    }

    /** Collect per-page "menu" membership requests for the menu-wiring pass. */
    protected array $pendingMenuMembership = [];

    protected function rememberMenuRequests(string $url, array $item): void
    {
        foreach ((array) ($item['menu'] ?? []) as $menuTitle) {
            $menuTitle = (string) $menuTitle;
            if ($menuTitle !== '') {
                $this->pendingMenuMembership[$menuTitle][] = ['page' => $url, 'title' => $item['title'] ?? $url];
            }
        }
    }

    /** Build menus from both the explicit "menus" map and per-page "menu" memberships. */
    protected function installMenus(): void
    {
        $menus = [];
        foreach (($this->data['menus'] ?? []) as $title => $items) {
            $menus[(string) $title] = is_array($items) ? $items : [];
        }
        foreach ($this->pendingMenuMembership as $title => $items) {
            $menus[$title] = array_merge($menus[$title] ?? [], $items);
        }

        foreach ($menus as $title => $items) {
            if (empty($items)) {
                continue;
            }
            $menu = Menu::where('title', $title)->where('item_type', 'menu')->first() ?: new Menu();
            $menu->title = $title;
            $menu->item_type = 'menu';
            $menu->is_active = 1;
            $menu->save();

            $position = 0;
            foreach ($items as $entry) {
                if (!is_array($entry)) {
                    $entry = ['page' => (string) $entry];
                }
                $contentId = 0;
                if (isset($entry['page'])) {
                    $contentId = $this->urlToId[$entry['page']]
                        ?? (int) (Content::where('url', $entry['page'])->value('id') ?? 0);
                } elseif (isset($entry['content_id'])) {
                    $contentId = (int) $entry['content_id'];
                }
                $itemUrl = (string) ($entry['url'] ?? '');
                if ($contentId === 0 && $itemUrl === '') {
                    continue;
                }

                // Idempotency: skip if this menu already links the same target.
                $exists = Menu::where('parent_id', $menu->id)->where('item_type', 'menu_item')
                    ->where(function ($q) use ($contentId, $itemUrl) {
                        if ($contentId) { $q->orWhere('content_id', $contentId); }
                        if ($itemUrl !== '') { $q->orWhere('url', $itemUrl); }
                    })->first();
                if ($exists) {
                    continue;
                }

                $item = new Menu();
                $item->parent_id = $menu->id;
                $item->item_type = 'menu_item';
                $item->is_active = 1;
                $item->position = $position++;
                $item->title = (string) ($entry['title'] ?? '');
                if ($contentId) { $item->content_id = $contentId; }
                if ($itemUrl !== '') { $item->url = $itemUrl; }
                $item->save();
            }
            $this->log[] = "menu '{$title}': " . count($items) . ' item(s)';
        }
    }

    /**
     * Save the content.json "colorScheme" via the Live Edit colour editor's own store
     * (TemplateCustomCss::liveEdit()->saveContent → the per-template template_css option
     * + userfiles live_edit.css). Base's master emits that option inline, so applying a
     * template also applies its default scheme and the colours stay editable — without
     * writing to the (possibly read-only) public/templates path. base.css only carries
     * var() fallbacks.
     */
    protected function applyColorScheme(): void
    {
        $scheme = $this->data['colorScheme'] ?? [];
        if (!is_array($scheme) || empty($scheme)) {
            return;
        }
        $css = CssVarizer::schemeToRootCss($scheme);
        if ($css === '') {
            return;
        }
        $facade = '\\MicroweberPackages\\TemplateCustomCss\\Facades\\TemplateCustomCss';
        if (!class_exists($facade)) {
            return;
        }
        $template = (string) ($this->template ?? template_name());
        try {
            $facade::liveEdit()->saveContent($css, $template);
            $this->log[] = "color scheme -> {$template} template_css option + live_edit.css (" . count($scheme) . ' vars)';
        } catch (\Throwable $e) {
            $this->log[] = 'color scheme save skipped: ' . $e->getMessage();
        }
    }

    /** For import_type=delete: remove existing page/post/product content before importing. */
    protected function deleteExistingContent(): void
    {
        $deleted = Content::whereIn('content_type', ['page', 'post', 'product'])
            ->where('is_deleted', 0)
            ->get();
        foreach ($deleted as $row) {
            $row->is_deleted = 1;
            $row->save();
        }
        $this->log[] = 'delete: soft-deleted ' . count($deleted) . ' existing page/post/product rows';
    }

    protected function boolInt($v): int
    {
        if (is_bool($v)) { return $v ? 1 : 0; }
        if (is_numeric($v)) { return (int) $v ? 1 : 0; }
        return in_array(strtolower((string) $v), ['1', 'y', 'yes', 'true'], true) ? 1 : 0;
    }

    public function log(string $text): void
    {
        $this->log[] = $text;
        if (is_object($this->logger) && method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }
}
