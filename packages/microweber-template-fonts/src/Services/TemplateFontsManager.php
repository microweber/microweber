<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\TemplateFonts\Contracts\FontProviderInterface;
use MicroweberPackages\TemplateFonts\Models\TemplateFont;
use MicroweberPackages\TemplateFonts\Providers\CustomFontsProvider;
use MicroweberPackages\TemplateFonts\Providers\GoogleFontsProvider;
use MicroweberPackages\TemplateFonts\Providers\SystemFontsProvider;

/**
 * Central facade for enabled fonts, catalog, CSS generation, and custom uploads.
 *
 * Standalone: no CMS get_option/save_option for font lists (uses template_fonts table).
 */
class TemplateFontsManager
{
    /** @var array<string, FontProviderInterface> */
    protected array $providers = [];

    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected array $config = [],
    ) {
        $this->config = array_merge($this->defaultConfig(), $config);
        $this->registerDefaultProviders();
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultConfig(): array
    {
        return [
            'fonts_path' => storage_path('app/public/fonts'),
            'fonts_url' => '/storage/fonts',
            'google_fonts_domain' => 'fonts.googleapis.com',
            'google_fonts_proxy_domain' => 'google-fonts.microweberapi.com',
            'use_google_fonts_proxy' => false,
            'download_google_fonts_locally' => true,
            'system_fonts' => [
                'Arial, Helvetica, sans-serif',
                'Georgia, serif',
                'Times New Roman, serif',
                'Courier New, monospace',
                'Verdana, sans-serif',
                'Tahoma, sans-serif',
                'Trebuchet MS, sans-serif',
                'Impact, Charcoal, sans-serif',
            ],
            'allowed_extensions' => ['ttf', 'woff', 'woff2', 'otf'],
            'max_upload_kb' => 5120,
            'catalog_path' => null,
            'css_cache_path' => storage_path('app/public/cache'),
            'css_cache_url' => '/storage/cache',
            'compile_assets' => false,
            'migrate_legacy_options' => true,
            'legacy_option_key' => 'enabled_custom_fonts',
            'legacy_option_group' => 'template',
            'legacy_proxy_option_key' => 'use_google_fonts_proxy',
        ];
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

    protected function configInt(string $key, int $default = 0): int
    {
        $value = $this->config[$key] ?? $default;
        if (is_int($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return (int) $value;
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

    protected function registerDefaultProviders(): void
    {
        $domain = $this->resolveGoogleDomain();

        $this->providers['google'] = new GoogleFontsProvider(
            fontsPath: $this->configString('fonts_path', storage_path('app/public/fonts')),
            fontsUrl: $this->configString('fonts_url', '/storage/fonts'),
            googleDomain: $domain,
            downloadLocally: $this->configBool('download_google_fonts_locally', true),
            catalogPath: is_string($this->config['catalog_path'] ?? null) ? $this->config['catalog_path'] : null,
        );

        /** @var list<string> $allowedExtensions */
        $allowedExtensions = array_values(array_filter(
            is_array($this->config['allowed_extensions'] ?? null) ? $this->config['allowed_extensions'] : ['ttf', 'woff', 'woff2', 'otf'],
            'is_string'
        ));

        $this->providers['custom'] = new CustomFontsProvider(
            fontsPath: $this->configString('fonts_path', storage_path('app/public/fonts')),
            fontsUrl: $this->configString('fonts_url', '/storage/fonts'),
            allowedExtensions: $allowedExtensions,
            maxUploadKb: $this->configInt('max_upload_kb', 5120),
        );
        /** @var list<string> $systemFonts */
        $systemFonts = array_values(array_filter(
            is_array($this->config['system_fonts'] ?? null) ? $this->config['system_fonts'] : [],
            'is_string'
        ));
        $this->providers['system'] = new SystemFontsProvider($systemFonts);
    }

    public function registerProvider(FontProviderInterface $provider): void
    {
        $this->providers[$provider->getName()] = $provider;
    }

    public function getProvider(string $name): ?FontProviderInterface
    {
        return $this->providers[$name] ?? null;
    }

    /**
     * @return array<string, FontProviderInterface>
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    public function resolveGoogleDomain(): string
    {
        $useProxy = $this->configBool('use_google_fonts_proxy', false);

        // CMS option override when available
        if (function_exists('get_option')) {
            $opt = get_option(
                $this->configString('legacy_proxy_option_key', 'use_google_fonts_proxy'),
                $this->configString('legacy_option_group', 'template')
            );
            if ($opt !== null && $opt !== false && $opt !== '') {
                $useProxy = (int) $opt === 1;
            }
        }

        return $useProxy
            ? $this->configString('google_fonts_proxy_domain', 'google-fonts.microweberapi.com')
            : $this->configString('google_fonts_domain', 'fonts.googleapis.com');
    }

    /**
     * Enabled font family names (favorites). The one-time fold of the legacy
     * `enabled_custom_fonts` option into template_fonts is handled by the
     * package migration, not at runtime; the option is only read here as a
     * fallback when the table isn't present yet.
     *
     * @return list<string>
     */
    public function getEnabledFonts(): array
    {
        if (!$this->tableReady()) {
            return $this->readLegacyOptionFonts();
        }

        /** @var list<string> $families */
        // Use get() (not pluck()) so the CacheableQueryBuilderTrait's CachedBuilder
        // actually caches the result — pluck() bypasses the cached get() override.
        // Family names are then plucked from the in-memory collection.
        $families = TemplateFont::query()
            ->enabled()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->pluck('family')
            ->filter(static fn ($f) => is_string($f) && $f !== '')
            ->values()
            ->all();

        return $families;
    }

    /**
     * System fonts + enabled custom/google fonts (for pickers).
     *
     * @return list<string>
     */
    public function getFonts(): array
    {
        $systemRaw = $this->config['system_fonts'] ?? [];
        $system = is_array($systemRaw) ? array_values(array_filter($systemRaw, 'is_string')) : [];
        $enabled = $this->getEnabledFonts();

        /** @var list<string> $merged */
        $merged = array_values(array_unique(array_merge($system, $enabled)));

        return $merged;
    }

    /**
     * Catalog of available Google (+ system) fonts for the picker API.
     *
     * @return list<array<string, mixed>>
     */
    public function getAvailableFonts(): array
    {
        $ready = [];
        foreach ($this->providers as $provider) {
            foreach ($provider->getAvailableFonts() as $font) {
                $ready[] = $font;
            }
        }

        return $ready;
    }

    /**
     * Enable / favorite a font (downloads Google font locally when configured).
     */
    public function enableFont(string $family, string $provider = 'google', ?string $category = null): bool
    {
        $family = trim($family);
        if ($family === '') {
            return false;
        }

        if (!$this->tableReady()) {
            return $this->legacyAppendFont($family);
        }

        $providerInstance = $this->providers[$provider] ?? $this->providers['google'] ?? null;
        $cssPath = null;
        $cssUrl = null;
        $filePath = null;
        $fileUrl = null;

        if ($providerInstance !== null) {
            $result = $providerInstance->enable($family);
            // Always persist the family; CSS can fall back to remote when download fails
            $cssPath = $result['css_path'] ?? null;
            $cssUrl = $result['css_url'] ?? null;
        }

        $existing = TemplateFont::query()
            ->where('family', $family)
            ->where('provider', $provider)
            ->first();

        if ($existing !== null) {
            $existing->is_enabled = true;
            if ($cssPath !== null) {
                $existing->css_path = $cssPath;
            }
            if ($cssUrl !== null) {
                $existing->css_url = $cssUrl;
            }
            if ($category !== null) {
                $existing->category = $category;
            }
            $existing->save();
        } else {
            $maxSortRaw = TemplateFont::query()->max('sort_order');
            $maxSort = is_numeric($maxSortRaw) ? (int) $maxSortRaw : 0;
            TemplateFont::query()->create([
                'family' => $family,
                'provider' => $provider,
                'category' => $category,
                'is_enabled' => true,
                'file_path' => $filePath,
                'file_url' => $fileUrl,
                'css_path' => $cssPath,
                'css_url' => $cssUrl,
                'sort_order' => $maxSort + 1,
            ]);
        }

        $this->clearCssCache();

        return true;
    }

    public function disableFont(string $family, ?string $provider = null): bool
    {
        $family = trim($family);
        if ($family === '') {
            return false;
        }

        if (!$this->tableReady()) {
            return $this->legacyRemoveFont($family);
        }

        $query = TemplateFont::query()->where('family', $family);
        if ($provider !== null) {
            $query->where('provider', $provider);
        }

        $updated = $query->update(['is_enabled' => false]);
        $this->clearCssCache();

        return $updated > 0;
    }

    public function removeFont(string $family, ?string $provider = null): bool
    {
        $family = trim($family);
        if ($family === '') {
            return false;
        }

        if (!$this->tableReady()) {
            return $this->legacyRemoveFont($family);
        }

        $query = TemplateFont::query()->where('family', $family);
        if ($provider !== null) {
            $query->where('provider', $provider);
        }

        $deleted = $query->delete();
        $this->clearCssCache();

        return $deleted > 0;
    }

    /**
     * Upload a custom TTF/WOFF font and enable it.
     *
     * @return array{success: bool, font?: TemplateFont, message?: string}
     */
    public function uploadCustomFont(UploadedFile $file, ?string $family = null): array
    {
        /** @var CustomFontsProvider $custom */
        $custom = $this->providers['custom'];
        $result = $custom->upload($file, $family);

        if ($result['success'] !== true) {
            return ['success' => false, 'message' => $result['message'] ?? 'Upload failed'];
        }

        $familyName = isset($result['family']) ? (string) $result['family'] : '';
        if ($familyName === '') {
            return ['success' => false, 'message' => 'Missing family name'];
        }

        if (!$this->tableReady()) {
            $this->legacyAppendFont($familyName);

            return ['success' => true, 'message' => 'Saved to legacy options (table missing)'];
        }

        $maxSortRaw = TemplateFont::query()->max('sort_order');
        $maxSort = is_numeric($maxSortRaw) ? (int) $maxSortRaw : 0;
        $font = TemplateFont::query()->updateOrCreate(
            [
                'family' => $familyName,
                'provider' => TemplateFont::PROVIDER_CUSTOM,
            ],
            [
                'category' => 'custom',
                'is_enabled' => true,
                'file_path' => $result['file_path'] ?? null,
                'file_url' => $result['file_url'] ?? null,
                'css_path' => $result['css_path'] ?? null,
                'css_url' => $result['css_url'] ?? null,
                'sort_order' => $maxSort + 1,
            ]
        );

        $this->clearCssCache();

        return ['success' => true, 'font' => $font];
    }

    public function getFontsStylesheetCss(): string
    {
        $output = [];

        if (!$this->tableReady()) {
            $enabled = $this->readLegacyOptionFonts();
            $google = $this->providers['google'] ?? null;
            if ($google instanceof GoogleFontsProvider) {
                foreach ($enabled as $family) {
                    $css = $google->getStylesheetCss($family);
                    if ($css !== '') {
                        $output[] = $css;
                    }
                }
            }

            return implode("\n", $output);
        }

        $fonts = TemplateFont::query()->enabled()->orderBy('sort_order')->orderBy('id')->get();

        foreach ($fonts as $font) {
            $provider = $this->providers[$font->provider] ?? null;
            if ($provider === null) {
                continue;
            }
            $css = $provider->getStylesheetCss(
                $font->family,
                $font->css_url,
                $font->file_url
            );
            if ($css !== '') {
                $output[] = $css;
            }
        }

        return implode("\n", $output);
    }

    public function getFontsStylesheetFilename(?string $css = null): string
    {
        $env = 'default';

        if (function_exists('mw_is_multisite') && mw_is_multisite()) {
            try {
                $env = (string) app()->environment();
            } catch (\Throwable) {
                $env = 'default';
            }
        }

        return 'custom_css.fonts.' . $env . '.' . $this->resolveCssVersion($css) . '.css';
    }

    /**
     * Cache-busting version token embedded in the compiled fonts stylesheet
     * filename.
     *
     * The compiled file is written once and only when it does not yet exist
     * (see getFontsStylesheetCssUrl), so the buster is a full md5 signature of
     * the actual generated CSS — the filename invalidates automatically whenever
     * the output truly changes (font enabled/disabled/re-ordered/re-downloaded,
     * and domain/proxy toggles that alter the CSS but not the enabled-font list).
     *
     * MW_VERSION, when running inside the CMS, is kept as an optional provenance
     * prefix (which platform version wrote the file); it is not needed for
     * busting and is simply omitted standalone. There is no configurable version
     * — a static one never changes and the signature already does the work.
     *
     * Full md5 (not a truncated prefix): the CSS is generated from an option/
     * table, not a file, so there is no filemtime to key on, and a truncation
     * collision between two different font sets would be served as stale cache.
     */
    protected function resolveCssVersion(?string $css = null): string
    {
        if ($css === null) {
            $css = $this->getFontsStylesheetCss();
        }

        $signature = md5($css);

        return defined('MW_VERSION')
            ? (string) MW_VERSION . '.' . $signature
            : $signature;
    }

    public function getFontsStylesheetCssUrl(?callable $apiUrlResolver = null): string
    {
        $apiUrl = $apiUrlResolver !== null
            ? $apiUrlResolver()
            : (function_exists('api_url') ? api_url('template/print_custom_css_fonts') : '/api/template/print_custom_css_fonts');

        if (function_exists('in_live_edit') && function_exists('is_admin') && in_live_edit() && is_admin()) {
            return $apiUrl;
        }

        if (!$this->configBool('compile_assets', false)) {
            return $apiUrl;
        }

        $css = $this->getFontsStylesheetCss();
        $cacheDir = $this->configString('css_cache_path', storage_path('app/public/cache'));
        $filename = $this->getFontsStylesheetFilename($css);
        $fullPath = rtrim($cacheDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

        if (!is_file($fullPath)) {
            if (!is_dir($cacheDir)) {
                @mkdir($cacheDir, 0755, true);
            }
            if (is_dir($cacheDir)) {
                @file_put_contents($fullPath, $css);
            }
        }

        return rtrim($this->configString('css_cache_url', '/storage/cache'), '/') . '/' . $filename;
    }

    public function clearCssCache(): void
    {
        // Because the compiled filename now carries a content signature, a
        // single-name unlink would leave every previous signature behind. Glob
        // the whole family so stale versions are swept on any font mutation.
        $dirs = [$this->configString('css_cache_path', storage_path('app/public/cache'))];
        if (function_exists('userfiles_path')) {
            $dirs[] = rtrim(userfiles_path(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'cache';
        }

        foreach (array_unique($dirs) as $dir) {
            $pattern = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'custom_css.fonts.*.css';
            foreach ((array) glob($pattern) as $file) {
                if (is_string($file) && is_file($file)) {
                    @unlink($file);
                }
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfigValue(string $key, mixed $value): void
    {
        $this->config[$key] = $value;

        // Rebuild providers when path/domain related config changes
        if (in_array($key, ['fonts_path', 'fonts_url', 'google_fonts_domain', 'google_fonts_proxy_domain', 'use_google_fonts_proxy', 'download_google_fonts_locally', 'system_fonts', 'allowed_extensions', 'max_upload_kb', 'catalog_path'], true)) {
            $this->registerDefaultProviders();
        }
    }

    public function tableReady(): bool
    {
        try {
            return Schema::hasTable('template_fonts');
        } catch (\Throwable) {
            return false;
        }
    }


    /**
     * @return list<string>
     */
    protected function readLegacyOptionFonts(): array
    {
        $legacyKey = $this->configString('legacy_option_key', 'enabled_custom_fonts');
        $legacyGroup = $this->configString('legacy_option_group', 'template');

        if (function_exists('get_option')) {
            $raw = get_option($legacyKey, $legacyGroup);
            if (is_string($raw) && $raw !== '') {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    return array_values(array_filter($decoded, static fn ($f) => is_string($f) && $f !== ''));
                }
            }
        }

        // Direct options table read when helpers missing but table exists
        try {
            if (Schema::hasTable('options')) {
                $row = DB::table('options')
                    ->where('option_key', $legacyKey)
                    ->where('option_group', $legacyGroup)
                    ->first();
                if ($row !== null && !empty($row->option_value)) {
                    $decoded = json_decode((string) $row->option_value, true);
                    if (is_array($decoded)) {
                        return array_values(array_filter($decoded, static fn ($f) => is_string($f) && $f !== ''));
                    }
                }
            }
        } catch (\Throwable) {
            // ignore
        }

        return [];
    }

    protected function legacyAppendFont(string $family): bool
    {
        $fonts = $this->readLegacyOptionFonts();
        if (!in_array($family, $fonts, true)) {
            $fonts[] = $family;
        }
        if (function_exists('save_option')) {
            save_option(
                $this->configString('legacy_option_key', 'enabled_custom_fonts'),
                json_encode($fonts),
                $this->configString('legacy_option_group', 'template')
            );

            return true;
        }

        return false;
    }

    protected function legacyRemoveFont(string $family): bool
    {
        $fonts = array_values(array_filter(
            $this->readLegacyOptionFonts(),
            static fn (string $f) => $f !== $family
        ));
        if (function_exists('save_option')) {
            save_option(
                $this->configString('legacy_option_key', 'enabled_custom_fonts'),
                json_encode($fonts),
                $this->configString('legacy_option_group', 'template')
            );

            return true;
        }

        return false;
    }
}
