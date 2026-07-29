<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Contracts;

interface FontProviderInterface
{
    /**
     * Provider key: google|custom|system
     */
    public function getName(): string;

    /**
     * @return list<array{family: string, category?: string|null, kind?: string}>
     */
    public function getAvailableFonts(): array;

    /**
     * Ensure the font is available locally (download / copy) if needed.
     *
     * @return array{success: bool, css_path?: string|null, css_url?: string|null, message?: string}
     */
    public function enable(string $family): array;

    /**
     * CSS snippet (@import or @font-face) for this enabled font.
     */
    public function getStylesheetCss(string $family, ?string $cssUrl = null, ?string $fileUrl = null): string;
}
