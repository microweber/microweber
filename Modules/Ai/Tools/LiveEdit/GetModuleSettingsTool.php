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
            'Inspect a module\'s settings. Pass module_id to read a module instance\'s '
            . 'CURRENTLY SAVED options (option_group = module id). Pass type (e.g. '
            . '"btn", "video", "google_maps", "contact_form") to also get the list of '
            . 'AVAILABLE option keys that module accepts and their input kind — call '
            . 'this before set_module_option so you use real option keys, not guesses.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'module_id',
                type: PropertyType::STRING,
                description: 'The module id (the DOM id of the module element / option group). '
                    . 'Use the id of the module you inserted. Returns its saved options.',
                required: false,
            ),
            new ToolProperty(
                name: 'type',
                type: PropertyType::STRING,
                description: 'The module type (e.g. "btn", "video", "google_maps", '
                    . '"contact_form"). Returns the AVAILABLE option keys that type accepts.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $moduleId = trim((string) ($args['module_id'] ?? ''));
        $type = trim((string) ($args['type'] ?? ''));

        if ($moduleId === '' && $type === '') {
            return $this->handleError('Provide a module_id (to read saved settings) and/or a type (to list available option keys).');
        }

        try {
            $result = [];

            if ($moduleId !== '') {
                $rows = Option::where('option_group', $moduleId)
                    ->orderBy('option_key')
                    ->get(['option_key', 'option_value']);
                $options = [];
                foreach ($rows as $r) {
                    $options[$r->option_key] = mb_substr((string) $r->option_value, 0, 300);
                }
                $result['module_id'] = $moduleId;
                $result['count'] = count($options);
                $result['settings'] = $options;
            }

            if ($type !== '') {
                $schema = $this->availableSettings($type);
                $result['type'] = $type;
                $result['available_settings'] = $schema;
                if (empty($schema)) {
                    $result['note'] = 'No simple option keys found for this type (it may have no configurable options, or it uses a media/repeater UI). You can still try set_module_option with keys seen in saved_settings.';
                }
            }

            return json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read module settings: ' . $e->getMessage());
        }
    }

    /**
     * The option keys a module TYPE accepts, parsed from its $settingsComponent
     * (a LiveEditModuleSettings Filament class whose fields use `options.<key>`
     * statePaths). Returns [key => input-kind]. Static source parse — no Filament
     * boot needed, works pre-render.
     *
     * @return array<string,string>
     */
    protected function availableSettings(string $type): array
    {
        $aliases = ['map' => 'google_maps', 'shop' => 'shop/products', 'products' => 'shop/products'];
        $type = $aliases[$type] ?? $type;

        $settingsClassFile = null;
        foreach (glob(base_path('Modules/*/Microweber/*Module.php')) as $f) {
            $src = (string) file_get_contents($f);
            if (!preg_match('/public static string \$module\s*=\s*[\'"]' . preg_quote($type, '/') . '[\'"]/', $src)) {
                continue;
            }
            if (!preg_match('/public static string \$settingsComponent\s*=\s*([A-Za-z0-9_]+)::class/', $src, $sm)) {
                return [];
            }
            $short = $sm[1];
            if (preg_match('/use\s+([A-Za-z0-9_\\\\]+\\\\' . preg_quote($short, '/') . ');/', $src, $um)) {
                $fqcn = $um[1];
            } else {
                preg_match('/namespace\s+([A-Za-z0-9_\\\\]+);/', $src, $nm);
                $fqcn = ($nm[1] ?? '') . '\\' . $short;
            }
            try {
                $settingsClassFile = (new \ReflectionClass($fqcn))->getFileName();
            } catch (\Throwable $e) {
                return [];
            }
            break;
        }

        if (!$settingsClassFile || !is_file($settingsClassFile)) {
            return [];
        }

        $src = (string) file_get_contents($settingsClassFile);
        $out = [];
        // Fields use statePaths like options.text / options.data-country.
        if (preg_match_all('/([A-Za-z0-9_]+)::make\(\s*[\'"]options\.([A-Za-z0-9_-]+)[\'"]/', $src, $m, PREG_SET_ORDER)) {
            foreach ($m as $x) {
                $comp = $x[1];
                $key = $x[2];
                $out[$key] = match (true) {
                    str_contains($comp, 'Toggle') => 'bool',
                    str_contains($comp, 'Select'), str_contains($comp, 'Radio') => 'select',
                    str_contains($comp, 'Color') => 'color',
                    str_contains($comp, 'Textarea') => 'text',
                    default => 'string',
                };
            }
        }

        return $out;
    }
}
