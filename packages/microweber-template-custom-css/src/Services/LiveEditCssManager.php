<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Services;

use MicroweberPackages\TemplateCustomCss\Contracts\CssFileHandlerInterface;
use MicroweberPackages\TemplateCustomCss\Contracts\OptionStoreInterface;
use MicroweberPackages\TemplateCustomCss\Support\PathHelper;

/**
 * Manages per-template live_edit.css files.
 *
 * File locations (MUST stay stable for CMS backup/restore):
 *   {css_base_path}/{template}/live_edit.css
 *   {css_base_path}/{template}/live_edit_{environment}.css  (multisite)
 *
 * CMS path: userfiles/css/{template}/live_edit.css
 */
class LiveEditCssManager implements CssFileHandlerInterface
{
    public const KEY = 'live_edit';

    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected array $config,
        protected OptionStoreInterface $optionStore,
        protected CssValidator $validator,
        protected CssUrlRewriter $urlRewriter,
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
     * Folder where live_edit.css is stored for a template.
     * Legacy: userfiles_path() . 'css/' . $template . '/'
     */
    public function getLiveEditCssSaveFolder(string $template): string
    {
        $template = $this->normalizeTemplateName($template);
        $base = $this->cssBasePath();
        $folder = rtrim($base, "/\\") . PathHelper::ds() . $template . PathHelper::ds();

        return PathHelper::normalize($folder, true);
    }

    /**
     * Absolute path to live_edit.css (or multisite variant).
     * Returns null when checkExists and file is missing (legacy returned false).
     */
    public function getLiveEditCssPath(?string $template = null, bool $checkExists = true): string|false
    {
        $template = $this->normalizeTemplateName($template);
        $folder = $this->getLiveEditCssSaveFolder($template);
        $defaultPath = PathHelper::normalize($folder . $this->getLiveEditCssFilename(), false);

        $multisitePath = false;
        if ($this->isMultisite()) {
            $multisitePath = PathHelper::normalize(
                $folder . $this->getLiveEditCssFilenameMultisite(),
                false
            );
        }

        if ($checkExists) {
            if ($multisitePath !== false && is_file($multisitePath)) {
                return $multisitePath;
            }
            if (is_file($defaultPath)) {
                return $defaultPath;
            }

            return false;
        }

        if ($multisitePath !== false) {
            return $multisitePath;
        }

        return $defaultPath;
    }

    public function getLiveEditCssFilename(): string
    {
        return $this->fileTypeString('live_edit', 'filename', 'live_edit.css');
    }

    public function getLiveEditCssFilenameMultisite(): string
    {
        $base = $this->getLiveEditCssFilename();
        if (!$this->isMultisite()) {
            return $base;
        }

        $environment = $this->environment();
        $stem = pathinfo($base, PATHINFO_FILENAME);
        $ext = pathinfo($base, PATHINFO_EXTENSION);

        return $stem . '_' . $environment . ($ext !== '' ? '.' . $ext : '');
    }

    /**
     * Public URL with version cache-buster, or null when file missing.
     * Legacy signature returned void/null when missing.
     */
    public function getLiveEditCssUrl(?string $template = null): ?string
    {
        $template = $this->normalizeTemplateName($template);
        $path = $this->getLiveEditCssPath($template, true);

        if ($path === false || !is_file($path)) {
            return null;
        }

        $mtime = filemtime($path);
        $baseUrl = rtrim($this->cssBaseUrl(), '/') . '/' . $template . '/';
        // URL always uses the base filename for public links (legacy behaviour);
        // on multisite the physical file may be live_edit_{env}.css but the
        // public URL historically used live_edit.css — keep that for single-site.
        // For multisite physical path, expose the actual multisite filename so
        // the browser fetches the correct file.
        $filename = $this->isMultisite()
            ? $this->getLiveEditCssFilenameMultisite()
            : $this->getLiveEditCssFilename();

        // Match legacy: always used getLiveEditCssFilename() in the URL even on multisite.
        // Restoring exact legacy behaviour for single-site compatibility.
        $filename = $this->getLiveEditCssFilename();
        if ($this->isMultisite() && is_file(PathHelper::normalize(
            $this->getLiveEditCssSaveFolder($template) . $this->getLiveEditCssFilenameMultisite(),
            false
        ))) {
            // Prefer serving the multisite file via its real name so content is correct.
            $filename = $this->getLiveEditCssFilenameMultisite();
        }

        return $baseUrl . $filename . '?version=' . $mtime;
    }

    /**
     * Save live-edit CSS content to disk + option store.
     * Preserves legacy option key/group: template_css / template_{template}
     */
    public function saveLiveEditCssContent(?string $cssCont, ?string $template = null): string
    {
        $template = $this->normalizeTemplateName($template);
        $css = $cssCont ?? '';

        $path = $this->resolveWritePath($template);

        if ($css !== '') {
            $validate = $this->configBool('validate_on_save', true);
            $fileValidate = $this->fileTypeBool('live_edit', 'validate', true);
            if ($validate && $fileValidate) {
                $this->validator->assertValid($css);
            }

            $rewrite = $this->fileTypeBool('live_edit', 'rewrite_urls', true);
            if ($rewrite) {
                $css = $this->urlRewriter->forStorage($css);
            }
        }

        $optionKey = $this->configString('live_edit_option_key', 'template_css');
        $groupPrefix = $this->configString('live_edit_option_group_prefix', 'template_');

        $this->optionStore->save([
            'option_value' => $css,
            'option_key' => $optionKey,
            'option_group' => $groupPrefix . $template,
        ]);

        $dir = dirname($path);
        PathHelper::ensureDirectory($dir);

        if ($css !== '') {
            file_put_contents($path, $css);
        } elseif (is_file($path)) {
            file_put_contents($path, '');
        } else {
            // Ensure empty file exists for consistent paths after clear
            file_put_contents($path, '');
        }

        return $css;
    }

    /**
     * @return array{success?: string, error?: string}
     */
    public function remove(?string $template = null, bool $restoreBackup = false): array
    {
        $template = $this->normalizeTemplateName($template);
        $path = $this->getLiveEditCssPath($template, true);

        if ($restoreBackup) {
            $bak = $this->getLiveEditCssPath($template, false);
            if ($bak === false) {
                return ['error' => 'File could not be returned'];
            }
            $bakPath = $bak . '.bak';
            $target = str_ireplace('.bak', '', $bakPath);
            // Prefer explicit .bak next to default filename
            $folder = $this->getLiveEditCssSaveFolder($template);
            $defaultBak = PathHelper::normalize($folder . $this->getLiveEditCssFilename() . '.bak', false);
            if (is_file($defaultBak)) {
                $restored = str_ireplace('.bak', '', $defaultBak);
                if (@rename($defaultBak, $restored)) {
                    return ['success' => 'Custom css is returned'];
                }

                return ['error' => 'File could not be returned'];
            }

            return ['error' => 'File could not be returned'];
        }

        $folder = $this->getLiveEditCssSaveFolder($template);
        $tf = PathHelper::normalize($folder . $this->getLiveEditCssFilename(), false);
        if ($this->isMultisite()) {
            $multi = PathHelper::normalize($folder . $this->getLiveEditCssFilenameMultisite(), false);
            if (is_file($multi)) {
                $tf = $multi;
            }
        }

        $settingsKey = $this->configString('template_settings_option_key', 'template_settings');
        $groupPrefix = $this->configString('live_edit_option_group_prefix', 'template_');
        $this->optionStore->save([
            'option_value' => '',
            'option_key' => $settingsKey,
            'option_group' => $groupPrefix . $template,
        ]);

        // Also clear stored CSS option
        $optionKey = $this->configString('live_edit_option_key', 'template_css');
        $this->optionStore->save([
            'option_value' => '',
            'option_key' => $optionKey,
            'option_group' => $groupPrefix . $template,
        ]);

        if (is_file($tf) && @rename($tf, $tf . '.bak')) {
            return ['success' => 'Custom css is removed'];
        }

        return ['error' => 'File could not be removed'];
    }

    /**
     * Check for live_edit.css (or .bak) existence; returns path or null.
     * Legacy returned the path string or null/void.
     */
    public function checkForCustomCss(string $templateName, bool $checkForBackup = false): ?string
    {
        $template = $this->normalizeTemplateName($templateName);
        $folder = $this->getLiveEditCssSaveFolder($template);
        $path = PathHelper::normalize($folder . $this->getLiveEditCssFilename(), false);
        if ($checkForBackup) {
            $path .= '.bak';
        }

        if (is_file($path)) {
            return $path;
        }

        return null;
    }

    // --- CssFileHandlerInterface ---

    public function getPath(?string $template = null, bool $checkExists = true): ?string
    {
        $path = $this->getLiveEditCssPath($template, $checkExists);

        return $path === false ? null : $path;
    }

    public function getUrl(?string $template = null): ?string
    {
        return $this->getLiveEditCssUrl($template);
    }

    public function getContent(?string $template = null): string
    {
        $path = $this->getLiveEditCssPath($template, true);
        if ($path === false || !is_file($path)) {
            // Fall back to option store
            $template = $this->normalizeTemplateName($template);
            $optionKey = $this->configString('live_edit_option_key', 'template_css');
            $groupPrefix = $this->configString('live_edit_option_group_prefix', 'template_');
            $fromOption = $this->optionStore->get($optionKey, $groupPrefix . $template);

            return is_string($fromOption) ? $fromOption : '';
        }

        $content = file_get_contents($path);

        return is_string($content) ? $content : '';
    }

    public function saveContent(string $css, ?string $template = null): string
    {
        return $this->saveLiveEditCssContent($css, $template);
    }

    // --- internals ---

    protected function resolveWritePath(string $template): string
    {
        if ($this->isMultisite()) {
            $folder = $this->getLiveEditCssSaveFolder($template);
            $path = $folder . $this->getLiveEditCssFilenameMultisite();

            return PathHelper::normalize($path, false);
        }

        $path = $this->getLiveEditCssPath($template, false);

        return $path === false
            ? PathHelper::normalize($this->getLiveEditCssSaveFolder($template) . $this->getLiveEditCssFilename(), false)
            : $path;
    }

    protected function cssBasePath(): string
    {
        $path = $this->config['css_base_path'] ?? storage_path('app/public/css');

        return is_string($path) ? $path : storage_path('app/public/css');
    }

    protected function cssBaseUrl(): string
    {
        $url = $this->config['css_base_url'] ?? '/storage/css';

        return is_string($url) ? $url : '/storage/css';
    }

    protected function isMultisite(): bool
    {
        // Config is authoritative (CMS service provider sets it from mw_is_multisite()).
        return $this->configBool('multisite', false);
    }

    protected function environment(): string
    {
        $fromConfig = $this->config['environment'] ?? null;
        if (is_string($fromConfig) && $fromConfig !== '') {
            return trim($fromConfig);
        }

        try {
            if (function_exists('app')) {
                return trim((string) app()->environment());
            }
        } catch (\Throwable) {
            // ignore
        }

        return 'production';
    }

    public function normalizeTemplateName(?string $template): string
    {
        if ($template === null || trim($template) === '' || $template === 'default') {
            $resolved = $this->resolveDefaultTemplate();
            if ($resolved !== '') {
                return $resolved;
            }

            return $this->configString('default_template', 'default');
        }

        return $template;
    }

    /**
     * Resolve current template name from CMS when available.
     */
    protected function resolveDefaultTemplate(): string
    {
        try {
            if (function_exists('app') && app()->bound('template_manager')) {
                $tm = app('template_manager');
                if (is_object($tm) && property_exists($tm, 'templateAdapter')) {
                    /** @var object|null $adapter */
                    $adapter = $tm->templateAdapter ?? null;
                    if (is_object($adapter) && method_exists($adapter, 'getTemplateFolderName')) {
                        $name = $adapter->getTemplateFolderName();
                        if (is_string($name) && $name !== '' && $name !== 'default') {
                            return $name;
                        }
                    }
                }
            }
            if (function_exists('template_name')) {
                $name = template_name();
                if (is_string($name) && $name !== '') {
                    return $name;
                }
            }
            if (function_exists('get_option')) {
                $opt = get_option('current_template', 'template');
                if (is_string($opt) && $opt !== '' && $opt !== 'default') {
                    return $opt;
                }
            }
        } catch (\Throwable) {
            // ignore
        }

        return '';
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

    protected function fileTypeString(string $fileType, string $key, string $default = ''): string
    {
        $cfg = $this->fileTypeConfig($fileType);
        $value = $cfg[$key] ?? $default;
        if (is_string($value) && $value !== '') {
            return $value;
        }

        return $default;
    }
}
