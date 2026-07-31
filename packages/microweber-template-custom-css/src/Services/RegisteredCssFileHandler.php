<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Services;

use MicroweberPackages\TemplateCustomCss\Contracts\CssFileHandlerInterface;
use MicroweberPackages\TemplateCustomCss\Contracts\OptionStoreInterface;
use MicroweberPackages\TemplateCustomCss\Support\PathHelper;

/**
 * Generic handler for arbitrary registered CSS file slots
 * (e.g. future per-page CSS: page_123 → page_123.css).
 *
 * @phpstan-type FileTypeConfig array{
 *     filename?: string|null,
 *     storage?: string,
 *     option_key?: string|null,
 *     option_group?: string|null,
 *     option_group_prefix?: string|null,
 *     multisite?: bool,
 *     rewrite_urls?: bool,
 *     validate?: bool,
 *     cache?: bool
 * }
 */
class RegisteredCssFileHandler implements CssFileHandlerInterface
{
    /**
     * @param  FileTypeConfig  $typeConfig
     * @param  array<string, mixed>  $appConfig
     */
    public function __construct(
        protected string $key,
        protected array $typeConfig,
        protected array $appConfig,
        protected OptionStoreInterface $optionStore,
        protected CssValidator $validator,
        protected CssUrlRewriter $urlRewriter,
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getPath(?string $template = null, bool $checkExists = true): ?string
    {
        $storage = (string) ($this->typeConfig['storage'] ?? 'file');
        if ($storage === 'option') {
            return null;
        }

        $template = $template ?? $this->stringConfig('default_template', 'default');
        $filename = $this->resolveFilename($template);
        $base = $this->stringConfig('css_base_path', storage_path('app/public/css'));
        $path = PathHelper::normalize(
            rtrim($base, "/\\") . PathHelper::ds() . $template . PathHelper::ds() . $filename,
            false
        );

        if ($checkExists && !is_file($path)) {
            return null;
        }

        return $path;
    }

    public function getUrl(?string $template = null): ?string
    {
        $path = $this->getPath($template, true);
        if ($path === null) {
            return null;
        }

        $template = $template ?? $this->stringConfig('default_template', 'default');
        $filename = $this->resolveFilename($template);
        $baseUrl = rtrim($this->stringConfig('css_base_url', '/storage/css'), '/');
        $mtime = filemtime($path);

        return $baseUrl . '/' . $template . '/' . $filename . '?version=' . $mtime;
    }

    public function getContent(?string $template = null): string
    {
        $storage = (string) ($this->typeConfig['storage'] ?? 'file');
        if ($storage === 'option') {
            $key = (string) ($this->typeConfig['option_key'] ?? $this->key);
            $group = $this->resolveOptionGroup($template);
            $val = $this->optionStore->get($key, $group);

            return is_string($val) ? $val : '';
        }

        $path = $this->getPath($template, true);
        if ($path === null) {
            return '';
        }
        $content = file_get_contents($path);

        return is_string($content) ? $content : '';
    }

    public function saveContent(string $css, ?string $template = null): string
    {
        if ($css !== '' && (bool) ($this->typeConfig['validate'] ?? true) && (bool) ($this->appConfig['validate_on_save'] ?? true)) {
            $this->validator->assertValid($css);
        }

        if ($css !== '' && (bool) ($this->typeConfig['rewrite_urls'] ?? false)) {
            $css = $this->urlRewriter->forStorage($css);
        }

        $storage = (string) ($this->typeConfig['storage'] ?? 'file');
        $optionKey = $this->typeConfig['option_key'] ?? null;
        if (is_string($optionKey) && $optionKey !== '') {
            $this->optionStore->save([
                'option_value' => $css,
                'option_key' => $optionKey,
                'option_group' => $this->resolveOptionGroup($template),
            ]);
        }

        if ($storage === 'file') {
            $path = $this->getPath($template, false);
            if ($path !== null) {
                PathHelper::ensureDirectory(dirname($path));
                file_put_contents($path, $css);
            }
        }

        return $css;
    }

    /**
     * @return array{success?: string, error?: string}
     */
    public function remove(?string $template = null, bool $restoreBackup = false): array
    {
        $path = $this->getPath($template, true);
        if ($restoreBackup) {
            $bak = $this->getPath($template, false);
            if ($bak !== null && is_file($bak . '.bak') && @rename($bak . '.bak', $bak)) {
                return ['success' => 'Custom css is returned'];
            }

            return ['error' => 'File could not be returned'];
        }

        if ($path !== null && is_file($path) && @rename($path, $path . '.bak')) {
            return ['success' => 'Custom css is removed'];
        }

        $this->saveContent('', $template);

        return ['success' => 'Custom css is removed'];
    }

    protected function resolveFilename(?string $template): string
    {
        $filename = $this->typeConfig['filename'] ?? null;
        if (is_string($filename) && $filename !== '') {
            return $filename;
        }

        return $this->key . '.css';
    }

    protected function resolveOptionGroup(?string $template): string
    {
        $optionGroup = $this->typeConfig['option_group'] ?? null;
        if (is_string($optionGroup)) {
            return $optionGroup;
        }

        $prefixRaw = $this->typeConfig['option_group_prefix'] ?? 'template_';
        $prefix = is_string($prefixRaw) ? $prefixRaw : 'template_';
        $template = $template ?? $this->stringConfig('default_template', 'default');

        return $prefix . $template;
    }

    protected function stringConfig(string $key, string $default): string
    {
        $value = $this->appConfig[$key] ?? $default;

        return is_string($value) && $value !== '' ? $value : $default;
    }
}
