<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Services;

use MicroweberPackages\TemplateCustomCss\Contracts\CssFileHandlerInterface;
use MicroweberPackages\TemplateCustomCss\Contracts\OptionStoreInterface;
use MicroweberPackages\TemplateCustomCss\Exceptions\CssFileNotFoundException;
use MicroweberPackages\TemplateCustomCss\Exceptions\InvalidCssException;
use MicroweberPackages\TemplateCustomCss\Support\PathHelper;

/**
 * Central multi-file CSS manager.
 *
 * Supports an arbitrary number of CSS file slots (live_edit, custom, future
 * per-page files, etc.) via registerFileType() / getHandler().
 */
class TemplateCustomCssManager
{
    /** @var array<string, CssFileHandlerInterface> */
    protected array $handlers = [];

    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected array $config,
        protected OptionStoreInterface $optionStore,
        protected CssValidator $validator,
        protected CssUrlRewriter $urlRewriter,
        protected LiveEditCssManager $liveEdit,
        protected CustomCssManager $customCss,
    ) {
        $this->handlers[LiveEditCssManager::KEY] = $liveEdit;
        $this->handlers[CustomCssManager::KEY] = $customCss;
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
        // Keep child managers in sync (they hold their own config snapshot).
        $this->liveEdit->setConfigValue($key, $value);
        $this->customCss->setConfigValue($key, $value);
    }

    public function getOptionStore(): OptionStoreInterface
    {
        return $this->optionStore;
    }

    public function getValidator(): CssValidator
    {
        return $this->validator;
    }

    public function getUrlRewriter(): CssUrlRewriter
    {
        return $this->urlRewriter;
    }

    public function liveEdit(): LiveEditCssManager
    {
        return $this->liveEdit;
    }

    public function customCss(): CustomCssManager
    {
        return $this->customCss;
    }

    public function registerFileType(CssFileHandlerInterface $handler): void
    {
        $this->handlers[$handler->getKey()] = $handler;
    }

    public function getHandler(string $key): CssFileHandlerInterface
    {
        if (!isset($this->handlers[$key])) {
            throw new CssFileNotFoundException("CSS file type [{$key}] is not registered.");
        }

        return $this->handlers[$key];
    }

    public function hasHandler(string $key): bool
    {
        return isset($this->handlers[$key]);
    }

    /**
     * @return list<string>
     */
    public function registeredKeys(): array
    {
        return array_keys($this->handlers);
    }

    /**
     * @return array<string, CssFileHandlerInterface>
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * Save CSS for a registered file type.
     *
     * @throws InvalidCssException
     */
    public function save(string $key, string $css, ?string $template = null): string
    {
        return $this->getHandler($key)->saveContent($css, $template);
    }

    public function getContent(string $key, ?string $template = null): string
    {
        return $this->getHandler($key)->getContent($template);
    }

    public function getUrl(string $key, ?string $template = null): ?string
    {
        return $this->getHandler($key)->getUrl($template);
    }

    public function getPath(string $key, ?string $template = null, bool $checkExists = true): ?string
    {
        return $this->getHandler($key)->getPath($template, $checkExists);
    }

    /**
     * @return array{success?: string, error?: string}
     */
    public function remove(string $key, ?string $template = null, bool $restoreBackup = false): array
    {
        return $this->getHandler($key)->remove($template, $restoreBackup);
    }

    /**
     * Validate CSS without saving.
     *
     * @return array{valid: bool, errors: list<string>}
     */
    public function validate(string $css): array
    {
        return $this->validator->validate($css);
    }

    /**
     * High-level save for live-edit endpoint (CMS-compatible response).
     *
     * @param  array<string, mixed>  $params
     * @return array{url: ?string, content: string}|false
     */
    public function saveLiveEditFromRequest(array $params): array|false
    {
        $template = null;
        if (isset($params['active_site_template']) && is_string($params['active_site_template'])) {
            $template = trim($params['active_site_template']);
            if ($template === '') {
                $template = null;
            }
        } elseif (isset($params['template']) && is_string($params['template'])) {
            $template = trim($params['template']);
            if ($template === '') {
                $template = null;
            }
        }

        if ($template === null || $template === 'default') {
            $template = $this->liveEdit->normalizeTemplateName($template);
            // Also try CMS option current_template
            if (function_exists('get_option')) {
                $current = get_option('current_template', 'template');
                if (is_string($current) && $current !== '' && $current !== 'default') {
                    $template = $current;
                }
            }
        }

        $css = '';
        if (isset($params['css_file_content']) && is_string($params['css_file_content'])) {
            $css = $params['css_file_content'];
        } elseif (isset($params['css']) && is_string($params['css'])) {
            $css = $params['css'];
        }

        // Optional template_settings JSON save (legacy save_template_settings)
        if (!empty($params['save_template_settings'])) {
            $settingsKeyRaw = $this->config['template_settings_option_key'] ?? 'template_settings';
            $settingsKey = is_string($settingsKeyRaw) ? $settingsKeyRaw : 'template_settings';
            $groupPrefixRaw = $this->config['live_edit_option_group_prefix'] ?? 'template_';
            $groupPrefix = is_string($groupPrefixRaw) ? $groupPrefixRaw : 'template_';
            $json = json_encode($params);
            $this->optionStore->save([
                'option_value' => is_string($json) ? $json : '',
                'option_key' => $settingsKey,
                'option_group' => $groupPrefix . $template,
            ]);
        }

        // Ensure directory exists
        $folder = $this->liveEdit->getLiveEditCssSaveFolder($template);
        PathHelper::ensureDirectory($folder);

        $saved = $this->liveEdit->saveLiveEditCssContent($css, $template);
        $url = $this->liveEdit->getLiveEditCssUrl($template);

        return [
            'url' => $url,
            'content' => $saved,
        ];
    }

    /**
     * @param  array<string, mixed>  $params
     * @return array{success?: string, error?: string}|false
     */
    public function removeLiveEditFromRequest(array $params): array|false
    {
        $template = null;
        if (isset($params['template']) && is_string($params['template']) && $params['template'] !== '') {
            $template = $params['template'];
        } elseif (function_exists('get_option')) {
            $opt = get_option('current_template', 'template');
            if (is_string($opt) && $opt !== '') {
                $template = $opt;
            }
        }

        $template = is_string($template) ? $template : '';
        if ($template === '') {
            return false;
        }

        $restore = !empty($params['return_styles']);

        return $this->liveEdit->remove($template, $restore);
    }

    public function clearAllCaches(): void
    {
        $this->customCss->clearCache();
    }
}
