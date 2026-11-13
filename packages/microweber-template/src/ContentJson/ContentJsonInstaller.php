<?php

namespace MicroweberPackages\Template\ContentJson;

use Illuminate\Support\Facades\Storage;
use Modules\Content\Models\Content;
use Modules\Menu\Models\Menu;
use MicroweberPackages\Template\Export\TemplateMediaBundler;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

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
 *   "menus": { "header_menu": [ {"page":"home","title":"Home"}, {"url":"https://x","title":"Ext"} ], "footer_menu": [...] },
 *   "fonts": [ {"family":"Poppins","provider":"google"}, {"family":"Inter"} ]   // enabled on install
 * }
 */
class ContentJsonInstaller
{
    protected array $data = [];
    protected ?string $template = null;
    protected ?string $templateDir = null;   // dir holding content.json + resources/assets/media
    protected string $importType = 'full';   // full | delete | only_media
    protected string $language = 'en';
    protected $logger = null;

    /** Resolved base URL for this template's materialised media (for {{TPL_MEDIA}}), or null. */
    protected ?string $mediaBaseUrl = null;
    protected bool $mediaResolved = false;

    /** url => created/updated content id, built during the content pass and used to wire menus. */
    protected array $urlToId = [];
    protected array $log = [];

    public function setData(array $data): self { $this->data = $data; return $this; }

    public function setFile(string $path): self
    {
        $raw = is_file($path) ? file_get_contents($path) : false;
        $json = $raw !== false ? json_decode($raw, true) : null;
        $this->data = is_array($json) ? $json : [];
        // The template dir (holding resources/assets/media) is the content.json's dir.
        if (is_file($path)) {
            $this->templateDir = dirname($path);
        }
        return $this;
    }

    public function setTemplate(?string $template): self { $this->template = $template; return $this; }

    /** Explicitly set the template folder (holding resources/assets/media/). */
    public function setTemplateDir(?string $dir): self { $this->templateDir = $dir ? rtrim($dir, '/\\') : null; return $this; }
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
        $this->installFonts();

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
            return $this->resolveMediaPlaceholders($item['html']);
        }
        if (isset($item['content']) && is_string($item['content'])) {
            return $this->resolveMediaPlaceholders($item['content']);
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
        $this->installGallery($content, $item);
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
        $this->installGallery($product, $item);
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

    /**
     * Recreate a content item's attached gallery (Pictures module) — media table rows
     * keyed by (rel_type = morph_name(Content), rel_id = the NEW content id). Filenames
     * are resolved from the {{TPL_MEDIA}} placeholder (materialising the bundled files
     * into the target userfiles). Idempotent: existing attached rows are replaced.
     */
    protected function installGallery($content, array $item): void
    {
        $gallery = $item['gallery'] ?? [];
        if (!is_array($gallery) || $gallery === []) {
            return;
        }
        $mediaClass = '\\Modules\\Media\\Models\\Media';
        if (!class_exists($mediaClass) || !function_exists('morph_name')) {
            $this->log[] = 'gallery: Media model unavailable, skipped';
            return;
        }
        $relType = morph_name(Content::class);

        try {
            // Replace any existing attached gallery for idempotent re-imports.
            $mediaClass::query()->where('rel_type', $relType)->where('rel_id', (int) $content->id)->delete();
        } catch (\Throwable) {
        }

        $position = 0;
        $made = 0;
        foreach ($gallery as $g) {
            if (!is_array($g)) {
                continue;
            }
            $filename = (string) ($g['filename'] ?? '');
            if ($filename === '') {
                continue;
            }
            $filename = $this->resolveMediaPlaceholders($filename);

            try {
                $m = new $mediaClass();
                $m->rel_type = $relType;
                $m->rel_id = (int) $content->id;
                $m->filename = $filename;
                $m->media_type = (string) ($g['media_type'] ?? 'picture');
                $m->position = (int) ($g['position'] ?? $position);
                if (isset($g['image_options'])) {
                    $m->image_options = $g['image_options'];
                }
                if (isset($g['title'])) {
                    $m->title = (string) $g['title'];
                }
                if (isset($g['description'])) {
                    $m->description = (string) $g['description'];
                }
                $m->save();
                $made++;
            } catch (\Throwable $e) {
                $this->log[] = 'gallery: row failed (' . $e->getMessage() . ')';
            }
            $position++;
        }
        if ($made > 0) {
            $this->log[] = "gallery '{$content->url}': {$made} image(s)";
        }
    }

    /**
     * Enable the fonts the template shipped in content.json ("fonts": [{family,
     * provider}, …]). The exporter freezes the picked-font families here; without
     * this pass an installed/activated template would render with the design CSS's
     * font var() fallbacks instead of the fonts the author actually chose. Enabling
     * downloads (or, on S3, mirrors) the woff2 + font.css the same way the Live-Edit
     * font picker does, so multisite/CDN deploys serve them correctly.
     */
    protected function installFonts(): void
    {
        $fonts = $this->data['fonts'] ?? [];
        if (!is_array($fonts) || $fonts === []) {
            return;
        }

        if (!class_exists(TemplateFontsManager::class) || !app()->bound(TemplateFontsManager::class)) {
            $this->log[] = 'fonts: TemplateFontsManager unavailable, skipped ' . count($fonts) . ' font(s)';
            return;
        }

        try {
            $manager = app(TemplateFontsManager::class);
        } catch (\Throwable $e) {
            $this->log[] = 'fonts: could not resolve manager (' . $e->getMessage() . ')';
            return;
        }

        $enabled = 0;
        foreach ($fonts as $font) {
            // Accept both {family, provider} objects and bare family strings.
            if (is_string($font)) {
                $font = ['family' => $font];
            }
            if (!is_array($font)) {
                continue;
            }
            $family = trim((string) ($font['family'] ?? ''));
            if ($family === '') {
                continue;
            }
            $provider = trim((string) ($font['provider'] ?? 'google')) ?: 'google';
            $category = isset($font['category']) ? (string) $font['category'] : null;
            try {
                if ($manager->enableFont($family, $provider, $category)) {
                    $enabled++;
                }
            } catch (\Throwable $e) {
                $this->log[] = "fonts: '{$family}' skipped ({$e->getMessage()})";
            }
        }

        if ($enabled > 0) {
            $this->log[] = "fonts: enabled {$enabled} of " . count($fonts) . ' font(s)';
        }
    }

    /**
     * Swap the {{TPL_MEDIA}} placeholder (written by {@see TemplateMediaBundler} on
     * export) for the URL of this template's media once it is materialised into the
     * target site's userfiles. If materialisation is unavailable the placeholder is
     * left in place rather than producing a half-rewritten URL.
     */
    protected function resolveMediaPlaceholders(string $html): string
    {
        if (!str_contains($html, TemplateMediaBundler::PLACEHOLDER)) {
            return $html;
        }
        $base = $this->mediaBaseUrl();
        if ($base === null || $base === '') {
            return $html;
        }
        return str_replace(TemplateMediaBundler::PLACEHOLDER, $base, $html);
    }

    /** Lazily materialise the template's bundled media and cache the base URL. */
    protected function mediaBaseUrl(): ?string
    {
        if (!$this->mediaResolved) {
            $this->mediaResolved = true;
            $this->mediaBaseUrl = $this->materializeMedia();
        }
        return $this->mediaBaseUrl;
    }

    /**
     * Copy the template's resources/assets/media/* into the target site's userfiles
     * (S3-safe, per-site, idempotent) and return the base URL under which the files
     * are served (so {{TPL_MEDIA}}/<name> resolves). Returns null when there is no
     * bundled media or the userfiles disk API is unavailable.
     */
    protected function materializeMedia(): ?string
    {
        $dir = $this->bundledMediaDir();
        if ($dir === null) {
            return null;
        }
        $files = array_filter(glob($dir . '/*') ?: [], 'is_file');
        if ($files === []) {
            return null;
        }
        if (!function_exists('userfiles_disk')) {
            return null;
        }

        $tpl = strtolower((string) ($this->template ?? (function_exists('template_name') ? template_name() : 'template')));
        $tpl = preg_replace('/[^a-z0-9_-]+/', '', $tpl) ?: 'template';

        // Disk-relative key prefix — per-site and S3-safe.
        $rel = function_exists('media_base_path_relative')
            ? media_base_path_relative()
            : (function_exists('userfiles_base_relpath') ? rtrim(userfiles_base_relpath('media'), '/') . '/' : 'userfiles/media/');
        $prefix = rtrim($rel, '/') . '/templates/' . $tpl . '/';

        try {
            $disk = Storage::disk(userfiles_disk());
        } catch (\Throwable $e) {
            $this->log[] = 'media: userfiles disk unavailable (' . $e->getMessage() . ')';
            return null;
        }

        $written = 0;
        foreach ($files as $file) {
            $key = $prefix . basename($file);
            try {
                if (!$disk->exists($key)) {
                    $disk->put($key, (string) file_get_contents($file));
                }
                $written++;
            } catch (\Throwable $e) {
                $this->log[] = 'media: failed ' . basename($file) . ' (' . $e->getMessage() . ')';
            }
        }

        // Derive the folder URL the same way for local and remote disks.
        $base = null;
        try {
            $probe = $disk->url($prefix . '__mw_p__');
            $cut = strrpos($probe, '/');
            if ($cut !== false) {
                $base = substr($probe, 0, $cut);
            }
        } catch (\Throwable) {
            $base = null;
        }
        if ($base === null && function_exists('media_base_url')) {
            $base = rtrim((string) media_base_url(), '/') . '/templates/' . $tpl;
        }

        $this->log[] = "media: materialised {$written} file(s) -> " . (string) $base;
        return $base ? rtrim($base, '/') : null;
    }

    /** Locate the template's bundled media dir (resources/assets/media). */
    protected function bundledMediaDir(): ?string
    {
        if ($this->templateDir && is_dir($this->templateDir . '/resources/assets/media')) {
            return $this->templateDir . '/resources/assets/media';
        }
        if ($this->template && function_exists('template_full_dir')) {
            $cand = rtrim((string) template_full_dir($this->template), '/\\') . '/resources/assets/media';
            if (is_dir($cand)) {
                return $cand;
            }
        }
        return null;
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
