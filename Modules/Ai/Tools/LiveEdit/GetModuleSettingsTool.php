<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\Option\Models\Option;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: read a module instance's saved settings (options).
 *
 * Module settings are stored as options keyed by the module's DOM id
 * (option_group = module id). Given a module id — e.g. the one just inserted —
 * this returns its option key/value pairs so the agent can inspect current
 * settings before changing them with set_module_option. Read-only.
 */
class GetModuleSettingsTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_module_settings',
            'Read the saved settings (options) of a module instance by its id '
            . '(option_group = module id). Use it to inspect a module\'s current '
            . 'configuration before changing it with set_module_option.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'module_id',
                type: PropertyType::STRING,
                description: 'The module id (the DOM id of the module element / option group). '
                    . 'Use the id of the module you inserted.',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $moduleId = trim((string) ($args['module_id'] ?? ''));
        if ($moduleId === '') {
            return $this->handleError('A module_id is required.');
        }

        try {
            $rows = Option::where('option_group', $moduleId)
                ->orderBy('option_key')
                ->get(['option_key', 'option_value']);

            $options = [];
            foreach ($rows as $r) {
                $options[$r->option_key] = mb_substr((string) $r->option_value, 0, 300);
            }

            return json_encode([
                'module_id' => $moduleId,
                'count' => count($options),
                'settings' => $options,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read module settings: ' . $e->getMessage());
        }
    }
}
