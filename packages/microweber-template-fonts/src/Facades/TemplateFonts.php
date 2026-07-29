<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

/**
 * @method static list<string> getEnabledFonts()
 * @method static list<string> getFonts()
 * @method static list<array<string, mixed>> getAvailableFonts()
 * @method static bool enableFont(string $family, string $provider = 'google', ?string $category = null)
 * @method static bool disableFont(string $family, ?string $provider = null)
 * @method static bool removeFont(string $family, ?string $provider = null)
 * @method static string getFontsStylesheetCss()
 * @method static string getFontsStylesheetCssUrl(?callable $apiUrlResolver = null)
 * @method static string getFontsStylesheetFilename()
 * @method static void clearCssCache()
 * @method static string resolveGoogleDomain()
 * @method static array{success: bool, font?: \MicroweberPackages\TemplateFonts\Models\TemplateFont, message?: string} uploadCustomFont(\Illuminate\Http\UploadedFile $file, ?string $family = null)
 * @method static array<string, mixed> getConfig()
 * @method static void setConfigValue(string $key, mixed $value)
 * @method static void registerProvider(\MicroweberPackages\TemplateFonts\Contracts\FontProviderInterface $provider)
 * @method static \MicroweberPackages\TemplateFonts\Contracts\FontProviderInterface|null getProvider(string $name)
 * @method static bool tableReady()
 *
 * @see TemplateFontsManager
 */
class TemplateFonts extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TemplateFontsManager::class;
    }
}
