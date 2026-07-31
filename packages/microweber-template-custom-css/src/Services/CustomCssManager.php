<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Services;

use MicroweberPackages\TemplateCustomCss\Contracts\CssFileHandlerInterface;
use MicroweberPackages\TemplateCustomCss\Contracts\OptionStoreInterface;
use MicroweberPackages\TemplateCustomCss\Support\PathHelper;

/**
 * Manages the site-wide user custom CSS (options.custom_css / template group)
 * and its optional compiled cache file under userfiles/cache/.
 *
 * Legacy path: TemplateCustomCss adapter.
 */
class CustomCssManager implements CssFileHandlerInterface
{
    public const KEY = 'custom';

    /** @var list<callable(): void> */
    protected array $contentHooks = [];

    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected array $config,
        protected OptionStoreInterface $optionStore,
        protected CssValidator $validator,
    ) {
    }

    public function setConfigValue(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }

    public function getKey(): string
    {
        return self::KEY;
    }

    /**
     * Register a callback invoked while building CSS content
     * (mirrors mw.template.print_custom_css_includes / print_custom_css events).
     *
     * @param  callable(): void  $hook
     */
    public function addContentHook(callable $hook): void
    {
        $this->contentHooks[] = $hook;
    }

    public function getCustomCssContent(): string
    {
        ob_start();

        if (function_exists('event_trigger')) {
            event_trigger('mw.template.print_custom_css_includes');
        }

        foreach ($this->contentHooks as $hook) {
            $hook();
        }

        $optionKey = $this->configString('custom_css_option_key', 'custom_css');
        $optionGroup = $this->configString('custom_css_option_group', 'template');
        $customCss = $this->optionStore->get($optionKey, $optionGroup);
        if (is_string($customCss)) {
            echo $customCss;
        }

        if (function_exists('event_trigger')) {
            event_trigger('mw.template.print_custom_css');
        }

        $output = ob_get_contents();
        ob_end_clean();

        return $output === false ? '' : $output;
    }

    /**
     * Returns CSS content and optionally writes compile cache.
     */
    public function getCustomCss(): string
    {
        $content = $this->getCustomCssContent();

        if ($this->shouldCompileAssets()) {
            $cacheFile = $this->cacheFilename($content);
            $cacheDir = $this->cacheDir();
            if (!is_file($cacheFile)) {
                PathHelper::ensureDirectory($cacheDir);
                if (is_dir($cacheDir)) {
                    @file_put_contents($cacheFile, $content);
                }
            }
        }

        return $content;
    }

    /**
     * Public URL for the custom CSS endpoint or cached file.
     *
     * @return string|false
     */
    public function getCustomCssUrl(): string|false
    {
        $content = $this->getCustomCssContent();
        if (trim($content) === '') {
            return false;
        }

        $url = $this->printCssUrl();

        $inLiveEdit = function_exists('in_live_edit') && in_live_edit();
        $isAdmin = function_exists('is_admin') && is_admin();
        if ($inLiveEdit && $isAdmin) {
            return $url;
        }

        if ($this->shouldCompileAssets()) {
            $cacheFile = $this->cacheFilename($content);
            if (is_file($cacheFile)) {
                $mtime = filemtime($cacheFile);
                $url = rtrim($this->cacheUrl(), '/') . '/' . basename($cacheFile) . '?ver=' . $mtime;
            }
        }

        return $url;
    }

    public function clearCache(): void
    {
        // Filenames carry a content signature, so glob every custom_css*.css
        // variant (matches TemplateManager::clear_cached_custom_css) — a single
        // filename unlink would leave older signatures behind.
        $cacheDir = $this->cacheDir();
        if (is_dir($cacheDir)) {
            $files = glob(rtrim($cacheDir, "/\\") . PathHelper::ds() . 'custom_css*.css');
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        @unlink($file);
                    }
                }
            }
        }
    }

    /**
     * Save user custom CSS into the option store.
     */
    public function saveCustomCss(string $css): string
    {
        if ($css !== '') {
            $validate = $this->configBool('validate_on_save', true);
            $fileValidate = $this->fileTypeBool('custom', 'validate', true);
            if ($validate && $fileValidate) {
                $this->validator->assertValid($css);
            }
        }

        $optionKey = $this->configString('custom_css_option_key', 'custom_css');
        $optionGroup = $this->configString('custom_css_option_group', 'template');

        $this->optionStore->save([
            'option_value' => $css,
            'option_key' => $optionKey,
            'option_group' => $optionGroup,
        ]);

        $this->clearCache();

        return $css;
    }

    // --- CssFileHandlerInterface ---

    public function getPath(?string $template = null, bool $checkExists = true): ?string
    {
        $file = $this->cacheFilename();
        if ($checkExists && !is_file($file)) {
            return null;
        }

        return $file;
    }

    public function getUrl(?string $template = null): ?string
    {
        $url = $this->getCustomCssUrl();

        return $url === false ? null : $url;
    }

    public function getContent(?string $template = null): string
    {
        return $this->getCustomCssContent();
    }

    public function saveContent(string $css, ?string $template = null): string
    {
        return $this->saveCustomCss($css);
    }

    /**
     * @return array{success?: string, error?: string}
     */
    public function remove(?string $template = null, bool $restoreBackup = false): array
    {
        $this->saveCustomCss('');
        $this->clearCache();

        return ['success' => 'Custom css is removed'];
    }

    // --- internals ---

    protected function shouldCompileAssets(): bool
    {
        if (function_exists('config')) {
            try {
                $mw = config('microweber.compile_assets');
                if ($mw !== null) {
                    return (bool) $mw;
                }
            } catch (\Throwable) {
                // ignore
            }
        }

        return $this->configBool('compile_assets', false);
    }

    protected function versionToken(): string
    {
        // Optional provenance segment only — the md5 content signature does the
        // cache-busting. Inside the CMS this is the platform version; standalone
        // it is empty (no arbitrary configurable version needed).
        if (defined('MW_VERSION')) {
            /** @var string|int|float $v */
            $v = constant('MW_VERSION');

            return (string) $v;
        }

        return '';
    }

    protected function siteCrc(): string
    {
        $site = '';
        if (function_exists('site_url')) {
            $site = (string) site_url();
        } elseif (function_exists('config')) {
            try {
                $cfgUrl = config('app.url', '');
                $site = is_string($cfgUrl) ? $cfgUrl : '';
            } catch (\Throwable) {
                $site = '';
            }
        }

        return (string) crc32($site);
    }

    protected function cacheDir(): string
    {
        $path = $this->config['css_cache_path'] ?? storage_path('app/public/cache');

        return PathHelper::normalize(is_string($path) ? $path : storage_path('app/public/cache'), true);
    }

    protected function cacheUrl(): string
    {
        $url = $this->config['css_cache_url'] ?? '/storage/cache';

        return is_string($url) ? $url : '/storage/cache';
    }

    protected function cacheFilename(?string $content = null): string
    {
        // The full md5 of the CSS is the cache-buster: the file is written only
        // when absent, so folding a content signature into the name is what makes
        // it self-invalidate on every edit. (Full hash, not a truncated prefix —
        // the CSS is option-stored so there's no filemtime to key on, and a
        // truncation collision would be served as stale cache.)
        //
        // MW_VERSION, when running inside the CMS, is kept as an optional
        // provenance segment (which platform version wrote the file, matching the
        // legacy custom_css.<crc>.<MW_VERSION>.css convention); it is not needed
        // for busting and is simply omitted standalone.
        if ($content === null) {
            $content = $this->getCustomCssContent();
        }

        $version = $this->versionToken();
        $versionSegment = $version !== '' ? $version . '.' : '';

        return $this->cacheDir()
            . 'custom_css.' . $this->siteCrc()
            . '.' . $versionSegment
            . md5($content)
            . '.css';
    }

    protected function printCssUrl(): string
    {
        $route = $this->configString('print_custom_css_route', 'template/print_custom_css');
        if (function_exists('api_url')) {
            return (string) api_url($route);
        }
        if (function_exists('url')) {
            return (string) url('api/' . ltrim($route, '/'));
        }

        return '/api/' . ltrim($route, '/');
    }

    protected function configString(string $key, string $default = ''): string
    {
        $value = $this->config[$key] ?? $default;
        if (is_string($value)) {
            return $value;
        }
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return $default;
    }

    protected function configBool(string $key, bool $default = false): bool
    {
        $value = $this->config[$key] ?? $default;
        if (is_bool($value)) {
            return $value;
        }
        if (is_int($value) || is_string($value)) {
            return (bool) $value;
        }

        return $default;
    }

    /**
     * @return array<string, mixed>
     */
    protected function fileTypeConfig(string $key): array
    {
        $types = $this->config['file_types'] ?? [];
        if (!is_array($types)) {
            return [];
        }
        $type = $types[$key] ?? [];

        return is_array($type) ? $type : [];
    }

    protected function fileTypeBool(string $fileType, string $key, bool $default = false): bool
    {
        $cfg = $this->fileTypeConfig($fileType);
        $value = $cfg[$key] ?? $default;
        if (is_bool($value)) {
            return $value;
        }
        if (is_int($value) || is_string($value)) {
            return (bool) $value;
        }

        return $default;
    }
}
