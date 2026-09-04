<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * List the insertable Microweber modules — the same list the Live-Edit
 * "Insert module" modal shows.
 *
 * Sourced from app()->microweber->getModulesDetails() (the exact source the
 * frontend module picker, mw.app.modules.list(), reads via api/module/list),
 * so the agent inserts modules by their REAL type string instead of guessing.
 * Each row is { module (the type to pass to insert_module), name, as_element }.
 * Read-only.
 */
class GetModulesTool extends BaseTool
{
    protected string $domain = 'liveedit';

    /** Internal module types the picker itself hides. */
    private const HIDDEN = ['template_settings'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_modules',
            'List the Microweber modules that can be inserted into the page — the '
            . 'same list the "Insert module" modal shows. Use it to find the exact '
            . 'module type string to pass to insert_module (e.g. "video", '
            . '"google_maps", "contact_form", "shop/products", "pictures", "menu"). '
            . 'Optionally pass a search term to filter by type or name.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search',
                type: PropertyType::STRING,
                description: 'Optional filter — matches the module type or name (case-insensitive).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $search = strtolower(trim((string) ($args['search'] ?? '')));

        try {
            $details = app()->microweber->getModulesDetails();
            if (!is_array($details)) {
                $details = [];
            }

            $modules = [];
            foreach ($details as $mod) {
                $type = (string) ($mod['module'] ?? '');
                if ($type === '' || in_array($type, self::HIDDEN, true)) {
                    continue;
                }
                $name = (string) ($mod['name'] ?? $type);
                if ($search !== ''
                    && strpos(strtolower($type), $search) === false
                    && strpos(strtolower($name), $search) === false) {
                    continue;
                }
                $modules[] = [
                    'module' => $type,
                    'name' => $name,
                    'as_element' => (bool) ($mod['as_element'] ?? false),
                ];
            }

            // Stable order by type so the list reads predictably.
            usort($modules, fn ($a, $b) => strcmp($a['module'], $b['module']));

            return json_encode([
                'count' => count($modules),
                'modules' => $modules,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Could not list modules: ' . $e->getMessage());
        }
    }
}
