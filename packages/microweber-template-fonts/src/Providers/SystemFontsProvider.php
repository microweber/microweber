<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Providers;

use MicroweberPackages\TemplateFonts\Contracts\FontProviderInterface;

class SystemFontsProvider implements FontProviderInterface
{
    /**
     * @param  list<string>  $systemFonts
     */
    public function __construct(
        protected array $systemFonts = [],
    ) {
    }

    public function getName(): string
    {
        return 'system';
    }

    /**
     * @return list<array{family: string, category?: string|null, kind?: string}>
     */
    public function getAvailableFonts(): array
    {
        $ready = [];
        foreach ($this->systemFonts as $font) {
            if ($font === '') {
                continue;
            }
            $ready[] = [
                'family' => $font,
                'category' => 'system',
                'kind' => 'system',
                'provider' => 'system',
            ];
        }

        return $ready;
    }

    /**
     * @return array{success: bool, css_path?: string|null, css_url?: string|null, message?: string}
     */
    public function enable(string $family): array
    {
        return ['success' => true, 'family' => $family];
    }

    public function getStylesheetCss(string $family, ?string $cssUrl = null, ?string $fileUrl = null): string
    {
        // System fonts need no CSS import
        return '';
    }
}
