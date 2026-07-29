<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Providers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use MicroweberPackages\TemplateFonts\Contracts\FontProviderInterface;

class CustomFontsProvider implements FontProviderInterface
{
    /**
     * @param  list<string>  $allowedExtensions
     */
    public function __construct(
        protected string $fontsPath,
        protected string $fontsUrl,
        protected array $allowedExtensions = ['ttf', 'woff', 'woff2', 'otf'],
        protected int $maxUploadKb = 5120,
    ) {
    }

    public function getName(): string
    {
        return 'custom';
    }

    /**
     * @return list<array{family: string, category?: string|null, kind?: string}>
     */
    public function getAvailableFonts(): array
    {
        // Custom fonts are stored in DB; catalog is empty here
        return [];
    }

    /**
     * @return array{success: bool, css_path?: string|null, css_url?: string|null, message?: string}
     */
    public function enable(string $family): array
    {
        // Custom fonts are enabled when uploaded; nothing remote to fetch
        return ['success' => true, 'family' => $family];
    }

    public function getStylesheetCss(string $family, ?string $cssUrl = null, ?string $fileUrl = null): string
    {
        if ($cssUrl !== null && $cssUrl !== '' && is_file((string) $cssUrl) === false && str_contains($cssUrl, '://') === false && !str_starts_with($cssUrl, '/')) {
            // cssUrl may be a path
        }

        if ($cssUrl !== null && $cssUrl !== '' && (str_starts_with($cssUrl, 'http') || str_starts_with($cssUrl, '/') || str_starts_with($cssUrl, '.'))) {
            return "@import url('{$cssUrl}');";
        }

        if ($fileUrl !== null && $fileUrl !== '') {
            $format = $this->formatFromUrl($fileUrl);

            return "@font-face {\n"
                . "  font-family: '{$family}';\n"
                . "  src: url('{$fileUrl}') format('{$format}');\n"
                . "  font-display: swap;\n"
                . "}\n";
        }

        $slug = Str::slug($family);
        $localCss = $this->fontsPath . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . 'font.css';
        if (is_file($localCss)) {
            $url = rtrim($this->fontsUrl, '/') . '/' . $slug . '/font.css';

            return "@import url('{$url}');";
        }

        return '';
    }

    /**
     * Upload a custom font file and generate local CSS.
     *
     * @return array{success: bool, family?: string, file_path?: string, file_url?: string, css_path?: string, css_url?: string, message?: string}
     */
    public function upload(UploadedFile $file, ?string $family = null): array
    {
        $extension = strtolower((string) ($file->getClientOriginalExtension() ?: $file->extension() ?: ''));
        if (!in_array($extension, $this->allowedExtensions, true)) {
            return [
                'success' => false,
                'message' => 'Invalid font extension. Allowed: ' . implode(', ', $this->allowedExtensions),
            ];
        }

        $size = $file->getSize();
        if ($size !== false && $size > $this->maxUploadKb * 1024) {
            return [
                'success' => false,
                'message' => "File exceeds max size of {$this->maxUploadKb} KB",
            ];
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $familyName = $family !== null && trim($family) !== '' ? trim($family) : $originalName;
        $familyName = trim($familyName);
        if ($familyName === '') {
            return ['success' => false, 'message' => 'Font family name is required'];
        }

        $slug = Str::slug($familyName);
        if ($slug === '') {
            $slug = 'custom-font-' . substr(md5($familyName), 0, 8);
        }

        $dir = $this->fontsPath . DIRECTORY_SEPARATOR . $slug;
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            return ['success' => false, 'message' => 'Unable to create font directory'];
        }

        $filename = $slug . '.' . $extension;
        $file->move($dir, $filename);

        $filePath = $dir . DIRECTORY_SEPARATOR . $filename;
        $fileUrl = rtrim($this->fontsUrl, '/') . '/' . $slug . '/' . $filename;
        $format = $this->formatFromExtension($extension);

        $css = "@font-face {\n"
            . "  font-family: '{$familyName}';\n"
            . "  src: url('./{$filename}') format('{$format}');\n"
            . "  font-display: swap;\n"
            . "}\n";

        $cssPath = $dir . DIRECTORY_SEPARATOR . 'font.css';
        file_put_contents($cssPath, $css);
        $cssUrl = rtrim($this->fontsUrl, '/') . '/' . $slug . '/font.css';

        return [
            'success' => true,
            'family' => $familyName,
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'css_path' => $cssPath,
            'css_url' => $cssUrl,
        ];
    }

    protected function formatFromExtension(string $extension): string
    {
        return match ($extension) {
            'woff2' => 'woff2',
            'woff' => 'woff',
            'otf' => 'opentype',
            default => 'truetype',
        };
    }

    protected function formatFromUrl(string $url): string
    {
        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?: $url, PATHINFO_EXTENSION));

        return $this->formatFromExtension($ext);
    }
}
