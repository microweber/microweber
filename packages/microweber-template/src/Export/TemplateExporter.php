<?php

declare(strict_types=1);

namespace MicroweberPackages\Template\Export;

use MicroweberPackages\Template\ContentJson\ContentJsonExporter;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use ZipArchive;

/**
 * Exports a site built in Live Edit as a reusable, uploadable child template.
 *
 * The output is DATA + ASSETS ONLY — it contains NO PHP files (no config.php, no
 * ServiceProvider, no blade views) so it is safe to upload: everything
 * executable is inherited from its parent_template (see
 * {@see \MicroweberPackages\Template\Adapters\MicroweberTemplate::getConfig} for
 * the config.json loader and the parent-view fallback). The produced tree:
 *
 *   <Name>/
 *     config.json              name + parent_template + version/author/date
 *     module.json              discovery manifest, providers: []
 *     composer.json            type: microweber-template, providers: []
 *     content.json             pages/posts/products/menus (+ fonts), portable (URLs)
 *     resources/assets/css/base.css            frozen Live-Edit design (live_edit.css)
 *     resources/assets/js/custom.js            frozen Live-Edit JS (if any)
 *     resources/assets/img/screenshot.jpg      reused from the source template
 *
 * The frozen base.css loads as the child's own css/base.css (the winning design
 * layer in Base's master cascade), reproducing the Live-Edit look; content.json
 * installs the pages on activation.
 */
class TemplateExporter
{
    protected string $source = '';
    protected string $name = '';
    protected ?string $parent = null;
    protected bool $fullSite = false;
    protected ?string $author = null;

    /** Files that would make the export executable — never allowed in the output. */
    private const PHP_EXT = '/\.(php|phtml|phar|php[0-9]|phps|pht|inc)$/i';

    public function setSource(string $template): self
    {
        $this->source = trim($template);
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = self::studly(trim($name));
        return $this;
    }

    public function setParent(?string $parent): self
    {
        $this->parent = ($parent !== null && trim($parent) !== '') ? trim($parent) : null;
        return $this;
    }

    /** Export the whole site's content instead of only the source template's pages. */
    public function setFullSite(bool $full): self
    {
        $this->fullSite = $full;
        return $this;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Build the template folder and return a summary (incl. 'dir').
     *
     * @return array<string, mixed>
     */
    public function export(?string $destDir = null): array
    {
        if ($this->name === '') {
            throw new \InvalidArgumentException('A template name is required.');
        }

        $source = $this->source !== ''
            ? $this->source
            : (string) (app()->option_manager->get('current_template', 'template') ?: 'Base');

        // Default the parent to the source template so the design renders
        // faithfully (a Base-built site -> Base, a Big2-built site -> Big2). Never
        // let a template parent itself.
        $parent = $this->parent ?: $source;
        if (strcasecmp($parent, $this->name) === 0) {
            $parent = 'Base';
        }

        $destDir = $destDir ?: storage_path('app/template-exports');
        $root = rtrim($destDir, '/\\') . DIRECTORY_SEPARATOR . $this->name;
        $this->rmrf($root);
        @mkdir($root . '/resources/assets/css', 0755, true);
        @mkdir($root . '/resources/assets/img', 0755, true);

        $today = date('Y-m-d');

        // 1. Content — pages/posts/products/menus (portable: parents + menu
        //    targets are URLs, not ids), plus the enabled fonts.
        $content = (new ContentJsonExporter())
            ->setTemplate($this->fullSite ? null : $source)
            ->export();
        $content['template'] = $this->name;
        // Rebind every exported page/post/product to the NEW template, so when the
        // template is installed its content renders under it (not the source it was
        // exported from). Without this the pages keep active_site_template=<source>.
        foreach (['pages', 'posts', 'products'] as $bucket) {
            if (!empty($content[$bucket]) && is_array($content[$bucket])) {
                foreach ($content[$bucket] as &$item) {
                    if (is_array($item)) {
                        $item['active_site_template'] = $this->name;
                    }
                }
                unset($item);
            }
        }
        $content['fonts'] = $this->enabledFonts();
        $this->write($root . '/content.json', $this->json($content));

        // 2. Frozen design: the Live-Edit global custom CSS becomes the child's
        //    own css/base.css (the winning layer in Base's master cascade).
        $css = '';
        try {
            $css = (string) app(LiveEditCssManager::class)->getContent($source);
        } catch (\Throwable) {
            $css = '';
        }
        $this->write(
            $root . '/resources/assets/css/base.css',
            "/* Design exported from the \"{$source}\" Live-Edit site on {$today}. */\n" . trim($css) . "\n"
        );

        // Optional: frozen Live-Edit custom JS.
        $js = '';
        try {
            $js = (string) app(LiveEditCssManager::class)->getLiveEditJsContent($source);
        } catch (\Throwable) {
            $js = '';
        }
        if (trim($js) !== '') {
            @mkdir($root . '/resources/assets/js', 0755, true);
            $this->write($root . '/resources/assets/js/custom.js', trim($js) . "\n");
        }

        // 3. JSON-only manifests (no PHP).
        $alias = strtolower($this->name);
        $this->write($root . '/config.json', $this->json([
            'name' => $this->name,
            'parent_template' => $parent,
            'version' => '1.0.0',
            'author' => $this->author ?: 'Microweber',
            'created_at' => $today,
            'exported_from' => $source,
        ]));
        $this->write($root . '/module.json', $this->json([
            'name' => $this->name,
            'alias' => $alias,
            'description' => $this->name . ' — exported child of ' . $parent . '.',
            'keywords' => ['template', 'child', strtolower($parent)],
            'priority' => 100,
            'providers' => [],
            'files' => [],
        ]));
        $this->write($root . '/composer.json', $this->json([
            'name' => 'microweber-template/' . $alias,
            'description' => $this->name . ' — exported child of ' . $parent . '.',
            'type' => 'microweber-template',
            'extra' => ['laravel' => ['providers' => []]],
            'target-dir' => $this->name,
        ]));

        // 4. Screenshot — reuse the source template's (served copy or source).
        $this->copyScreenshot($source, $root);

        // 5. Hard guarantee: the output contains no executable PHP.
        $php = $this->findPhp($root);
        if ($php !== []) {
            throw new \RuntimeException(
                'Export unexpectedly produced PHP file(s): ' . implode(', ', $php)
            );
        }

        return [
            'name' => $this->name,
            'parent_template' => $parent,
            'exported_from' => $source,
            'dir' => $root,
            'pages' => count($content['pages'] ?? []),
            'posts' => count($content['posts'] ?? []),
            'products' => count($content['products'] ?? []),
            'fonts' => count($content['fonts'] ?? []),
            'css_bytes' => strlen($css),
        ];
    }

    /**
     * Build the template and pack it into a ZIP. Returns the summary incl. 'zip'.
     *
     * @return array<string, mixed>
     */
    public function exportToZip(?string $zipPath = null, ?string $destDir = null): array
    {
        $info = $this->export($destDir);
        $root = $info['dir'];

        $zipPath = $zipPath ?: (storage_path('app/template-exports') . DIRECTORY_SEPARATOR . $this->name . '.zip');
        @mkdir(dirname($zipPath), 0755, true);
        @unlink($zipPath);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Could not create the export zip at ' . $zipPath);
        }
        foreach ($this->files($root) as $file) {
            // Defense in depth: never add a PHP file to the archive, even if one
            // somehow appeared (e.g. inside copied media).
            if (preg_match(self::PHP_EXT, basename($file))) {
                continue;
            }
            $rel = $this->name . '/' . ltrim(str_replace($root, '', $file), '/\\');
            $zip->addFile($file, str_replace('\\', '/', $rel));
        }
        $zip->close();

        $info['zip'] = $zipPath;
        return $info;
    }

    /** @return list<array{family: string, provider: string}> */
    private function enabledFonts(): array
    {
        $model = \MicroweberPackages\TemplateFonts\Models\TemplateFont::class;
        if (!class_exists($model)) {
            return [];
        }
        try {
            return $model::query()
                ->where('is_enabled', 1)
                ->orderBy('sort_order')
                ->get(['family', 'provider'])
                ->map(fn ($f) => ['family' => (string) $f->family, 'provider' => (string) ($f->provider ?: 'google')])
                ->filter(fn ($f) => $f['family'] !== '')
                ->values()
                ->all();
        } catch (\Throwable) {
            return [];
        }
    }

    private function copyScreenshot(string $source, string $root): void
    {
        $candidates = [
            function_exists('templates_dir') ? templates_dir() . $source . '/screenshot.jpg' : null,
            function_exists('templates_dir') ? templates_dir() . $source . '/screenshot.png' : null,
            function_exists('public_path') ? public_path('templates/' . strtolower($source) . '/screenshot.jpg') : null,
        ];
        foreach ($candidates as $src) {
            if ($src && is_file($src)) {
                $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION)) === 'png' ? 'png' : 'jpg';
                @copy($src, $root . '/resources/assets/img/screenshot.' . $ext);
                return;
            }
        }
    }

    /** @return list<string> */
    private function findPhp(string $root): array
    {
        $hits = [];
        foreach ($this->files($root) as $file) {
            if (preg_match(self::PHP_EXT, basename($file))) {
                $hits[] = str_replace($root, '', $file);
            }
        }
        return $hits;
    }

    /** @return list<string> */
    private function files(string $root): array
    {
        if (!is_dir($root)) {
            return [];
        }
        $out = [];
        foreach (new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
        ) as $f) {
            if ($f->isFile()) {
                $out[] = $f->getPathname();
            }
        }
        return $out;
    }

    private function write(string $path, string $contents): void
    {
        @mkdir(dirname($path), 0755, true);
        file_put_contents($path, $contents);
    }

    private function json(array $data): string
    {
        return (string) json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function rmrf(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $f) {
            $f->isDir() ? @rmdir($f->getPathname()) : @unlink($f->getPathname());
        }
        @rmdir($dir);
    }

    private static function studly(string $name): string
    {
        // Normalise separators to underscores, then StudlyCase WITHOUT lowercasing
        // the rest (so an already-camelCase name like "MyTheme" is preserved while
        // "my exported theme" becomes "MyExportedTheme").
        $normalized = preg_replace('/[^A-Za-z0-9]+/', '_', trim($name)) ?? $name;
        return \Illuminate\Support\Str::studly($normalized);
    }
}
