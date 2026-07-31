<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;

/**
 * @method static array<string, mixed> getConfig()
 * @method static void setConfigValue(string $key, mixed $value)
 * @method static \MicroweberPackages\TemplateCustomCss\Contracts\OptionStoreInterface getOptionStore()
 * @method static \MicroweberPackages\TemplateCustomCss\Services\CssValidator getValidator()
 * @method static \MicroweberPackages\TemplateCustomCss\Services\CssUrlRewriter getUrlRewriter()
 * @method static \MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager liveEdit()
 * @method static \MicroweberPackages\TemplateCustomCss\Services\CustomCssManager customCss()
 * @method static void registerFileType(\MicroweberPackages\TemplateCustomCss\Contracts\CssFileHandlerInterface $handler)
 * @method static \MicroweberPackages\TemplateCustomCss\Contracts\CssFileHandlerInterface getHandler(string $key)
 * @method static bool hasHandler(string $key)
 * @method static list<string> registeredKeys()
 * @method static string save(string $key, string $css, ?string $template = null)
 * @method static string getContent(string $key, ?string $template = null)
 * @method static string|null getUrl(string $key, ?string $template = null)
 * @method static string|null getPath(string $key, ?string $template = null, bool $checkExists = true)
 * @method static array{success?: string, error?: string} remove(string $key, ?string $template = null, bool $restoreBackup = false)
 * @method static array{valid: bool, errors: list<string>} validate(string $css)
 * @method static array{url: ?string, content: string}|false saveLiveEditFromRequest(array<string, mixed> $params)
 * @method static array{success?: string, error?: string}|false removeLiveEditFromRequest(array<string, mixed> $params)
 * @method static void clearAllCaches()
 *
 * @see TemplateCustomCssManager
 */
class TemplateCustomCss extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TemplateCustomCssManager::class;
    }
}
