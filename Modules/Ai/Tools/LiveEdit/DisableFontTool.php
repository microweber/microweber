<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Disable a font on the site so it is no longer offered/loaded. With remove=true
 * it also deletes the font's files (locally and from the bucket on remote
 * installs). Backend tool; shared by the Live-Edit agent and MCP.
 */
class DisableFontTool extends BaseTool
{
    protected string $domain = 'fonts';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'disable_font',
            'Disable a font on the site so it is no longer loaded. Pass remove=true '
            . 'to also delete its files. Does not change which font the design '
            . 'currently uses — switch that with set_font. Use list_fonts to see '
            . 'enabled fonts.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'family',
                type: PropertyType::STRING,
                description: 'The font family to disable, e.g. "Poppins".',
                required: true,
            ),
            new ToolProperty(
                name: 'remove',
                type: PropertyType::BOOLEAN,
                description: 'When true, also delete the font files (not just disable). Default false.',
                required: false,
            ),
            new ToolProperty(
                name: 'provider',
                type: PropertyType::STRING,
                description: 'Optional provider filter: "google", "custom" or "system".',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $family = trim((string) ($args['family'] ?? ''));
        if ($family === '') {
            return $this->handleError('A font family is required.');
        }
        $remove = filter_var($args['remove'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $provider = strtolower(trim((string) ($args['provider'] ?? '')));
        $provider = in_array($provider, ['google', 'custom', 'system'], true) ? $provider : null;

        $manager = $this->manager();
        if ($manager === null) {
            return $this->handleError('The font manager is not available on this site.');
        }

        try {
            $ok = $remove
                ? $manager->removeFont($family, $provider)
                : $manager->disableFont($family, $provider);
        } catch (\Throwable $e) {
            return $this->handleError('Could not disable font: ' . $e->getMessage());
        }

        return json_encode([
            'success' => (bool) $ok,
            'family' => $family,
            'removed' => $remove,
            'message' => $ok
                ? ($remove ? "Removed \"{$family}\" and its files." : "Disabled \"{$family}\".")
                : "No enabled font \"{$family}\" was found.",
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
