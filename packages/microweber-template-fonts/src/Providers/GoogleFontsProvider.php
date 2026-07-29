<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Providers;

use Illuminate\Support\Str;
use MicroweberPackages\TemplateFonts\Contracts\FontProviderInterface;
use MicroweberPackages\TemplateFonts\Downloaders\GoogleFontDownloader;

class GoogleFontsProvider implements FontProviderInterface
{
    public function __construct(
        protected string $fontsPath,
        protected string $fontsUrl,
        protected string $googleDomain,
        protected bool $downloadLocally = true,
        protected ?string $catalogPath = null,
    ) {
    }

    public function getName(): string
    {
        return 'google';
    }

    /**
     * @return list<array{family: string, category?: string|null, kind?: string}>
     */
    public function getAvailableFonts(): array
    {
        $catalogDir = $this->catalogPath ?? dirname(__DIR__, 2) . '/resources/data';
        $ready = [];

        foreach (['fonts.json', 'fonts-more.json'] as $file) {
            $path = $catalogDir . DIRECTORY_SEPARATOR . $file;
            if (!is_file($path)) {
                continue;
            }
            $decoded = json_decode((string) file_get_contents($path), true);
            if (!is_array($decoded) || !isset($decoded['items']) || !is_array($decoded['items'])) {
                continue;
            }
            foreach ($decoded['items'] as $font) {
                if (!is_array($font) || !isset($font['family']) || !is_string($font['family'])) {
                    continue;
                }
                $ready[] = [
                    'family' => $font['family'],
                    'category' => isset($font['category']) && is_string($font['category']) ? $font['category'] : null,
                    'kind' => isset($font['kind']) && is_string($font['kind']) ? $font['kind'] : 'webfonts#webfont',
                    'provider' => 'google',
                ];
            }
        }

        return $ready;
    }

    /**
     * @return array{success: bool, css_path?: string|null, css_url?: string|null, message?: string, family?: string}
     */
    public function enable(string $family): array
    {
        $family = trim($family);
        if ($family === '') {
            return ['success' => false, 'message' => 'Font family is required'];
        }

        if (!$this->downloadLocally) {
            return [
                'success' => true,
                'css_path' => null,
                'css_url' => $this->remoteCssUrl($family),
                'family' => $family,
            ];
        }

        if (!is_dir($this->fontsPath) && !mkdir($this->fontsPath, 0755, true) && !is_dir($this->fontsPath)) {
            return ['success' => false, 'message' => 'Unable to create fonts path'];
        }

        $slug = Str::slug($family);
        $localCss = $this->fontsPath . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . 'font.css';
        if (is_file($localCss)) {
            return [
                'success' => true,
                'css_path' => $localCss,
                'css_url' => rtrim($this->fontsUrl, '/') . '/' . $slug . '/font.css',
                'family' => $family,
            ];
        }

        try {
            $downloader = new GoogleFontDownloader();
            $downloader->setOutputPath($this->fontsPath);
            $downloader->addFontUrl($this->remoteCssUrl($family));
            $result = $downloader->download();

            if (isset($result['error'])) {
                return ['success' => false, 'message' => $result['error']];
            }

            if (is_file($localCss)) {
                return [
                    'success' => true,
                    'css_path' => $localCss,
                    'css_url' => rtrim($this->fontsUrl, '/') . '/' . $slug . '/font.css',
                    'family' => $family,
                ];
            }

            // Download may have used a different slug; fall back to remote
            return [
                'success' => true,
                'css_path' => null,
                'css_url' => $this->remoteCssUrl($family),
                'family' => $family,
                'message' => 'Downloaded with remote fallback',
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getStylesheetCss(string $family, ?string $cssUrl = null, ?string $fileUrl = null): string
    {
        if ($cssUrl !== null && $cssUrl !== '') {
            return "@import url('{$cssUrl}');";
        }

        $slug = Str::slug($family);
        $localCss = $this->fontsPath . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . 'font.css';
        if (is_file($localCss)) {
            $url = rtrim($this->fontsUrl, '/') . '/' . $slug . '/font.css';

            return "@import url('{$url}');";
        }

        $remote = $this->remoteCssUrl($family, withWeights: true);

        return "@import url({$remote});";
    }

    public function remoteCssUrl(string $family, bool $withWeights = false): string
    {
        $font = str_replace('%2B', '+', $family);
        $encoded = rawurlencode($font);

        if ($withWeights) {
            return "//{$this->googleDomain}/css?family={$encoded}:300italic,400italic,600italic,700italic,800italic,400,600,800,700,300&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic";
        }

        return "https://{$this->googleDomain}/css?family={$encoded}";
    }
}
