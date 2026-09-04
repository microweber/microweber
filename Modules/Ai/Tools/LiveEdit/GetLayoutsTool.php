<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * List the ready-made layouts of the active template — the same list the
 * Live-Edit "Insert layout" modal shows.
 *
 * Sourced from app()->microweber->getTemplates('layouts', $template) (the exact
 * source the frontend layout picker, mw.app.layouts.list(), reads via
 * api/module/list?layout_type=layout&elements_mode=true), so the layouts come
 * from the TEMPLATE, never hardcoded. Each row is { name, category, template }
 * where `template` is the value to pass to insert_layout. Read-only.
 */
class GetLayoutsTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_layouts',
            'List the ready-made layouts available in the active template — the same '
            . 'list the "Insert layout" modal shows. Use it to find a layout, then '
            . 'pass its `template` value to insert_layout. The layouts come from the '
            . 'template (not hardcoded). Optionally pass a template name to inspect a '
            . 'different template.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'template',
                type: PropertyType::STRING,
                description: 'Optional template name to list layouts for. Defaults to the active site template.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $template = trim((string) ($args['template'] ?? ''));
        if ($template === '') {
            $template = (string) (function_exists('template_name') ? template_name() : get_option('current_template', 'template'));
        }

        try {
            $skins = app()->microweber->getTemplates('layouts', $template);
            if (!is_array($skins)) {
                $skins = [];
            }

            $layouts = [];
            foreach ($skins as $skin) {
                $tpl = (string) ($skin['layout_file'] ?? $skin['template'] ?? '');
                if ($tpl === '') {
                    continue;
                }
                $cats = $skin['categories'] ?? ($skin['category'] ?? '');
                if (is_array($cats)) {
                    $cats = implode(', ', $cats);
                }
                $layouts[] = [
                    'name' => (string) ($skin['name'] ?? 'Layout'),
                    'category' => (string) $cats,
                    'template' => $tpl,
                ];
            }

            return json_encode([
                'template' => $template,
                'count' => count($layouts),
                'layouts' => $layouts,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Could not list layouts: ' . $e->getMessage());
        }
    }
}
