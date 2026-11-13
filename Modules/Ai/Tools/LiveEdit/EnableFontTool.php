<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Enable (install) a font on the site without changing the design — it becomes
 * available for use. Downloads + registers the font (and mirrors it to S3 on
 * remote installs). Backend tool; the MCP counterpart of set_font's enable half.
 * To also apply the font to the design in Live Edit, use set_font instead.
 */
class EnableFontTool extends BaseTool
{
    protected string $domain = 'fonts';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'enable_font',
            'Enable (install) a font family on the site so it becomes available — '
            . 'downloads and registers it. Does NOT change the design; to also '
            . 'apply it as the site font use set_font. Use search_fonts to find a '
            . 'valid family.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'family',
                type: PropertyType::STRING,
                description: 'The font family to enable, e.g. "Poppins".',
                required: true,
            ),
            new ToolProperty(
                name: 'provider',
                type: PropertyType::STRING,
                description: 'Font provider: "google" (default) or "system".',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $family = trim((string) ($args['family'] ?? ''));
        if ($family === '') {
            return $this->handleError('A font family is required (e.g. "Poppins").');
        }
        $provider = strtolower(trim((string) ($args['provider'] ?? 'google')));
        if (!in_array($provider, ['google', 'system', 'custom'], true)) {
            $provider = 'google';
        }

        $manager = $this->manager();
        if ($manager === null) {
            return $this->handleError('The font manager is not available on this site.');
        }

        try {
            $ok = $manager->enableFont($family, $provider);
        } catch (\Throwable $e) {
            return $this->handleError('Could not enable font: ' . $e->getMessage());
        }

        return json_encode([
            'success' => (bool) $ok,
            'family' => $family,
            'provider' => $provider,
            'message' => $ok ? "Enabled \"{$family}\"." : "Could not enable \"{$family}\".",
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function manager(): ?TemplateFontsManager
    {
        if (!class_exists(TemplateFontsManager::class) || !app()->bound(TemplateFontsManager::class)) {
            return null;
        }

        try {
            return app(TemplateFontsManager::class);
        } catch (\Throwable) {
            return null;
        }
    }
}
