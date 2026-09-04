<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;

/**
 * Live-Edit tool: list the editable fields/regions on the current canvas.
 *
 * Microweber pages expose editable regions (elements with the `edit` class /
 * a `field`+`rel` pair) and module instances. The frontend collects them from
 * the live canvas each turn and the controller binds them to the request-scoped
 * container key 'mw.ai.liveedit.context'. This tool returns that list so the
 * model knows exactly which regions it can write into (with set_text) or drop a
 * module into (with insert_module) — instead of guessing selectors. Read-only.
 */
class GetEditFieldsTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_edit_fields',
            'List the editable fields/regions on the current page — the editable '
            . 'content areas (class "edit", with their field/rel) and the module '
            . 'instances (with id and type). Use it to see where you can write text '
            . 'or insert a module before acting, instead of guessing selectors.'
        );
    }

    protected function properties(): array
    {
        return [];
    }

    public function __invoke(...$args): string
    {
        $ctx = app()->bound('mw.ai.liveedit.context') ? (array) app('mw.ai.liveedit.context') : [];
        $fields = $ctx['edit_fields'] ?? null;

        if (!is_array($fields)) {
            return $this->handleError(
                'No editable-fields list is available for this turn (open the page in Live Edit).'
            );
        }

        // Normalise to a compact, predictable shape.
        $regions = [];
        $modules = [];
        foreach ($fields as $f) {
            if (!is_array($f)) {
                continue;
            }
            if (($f['kind'] ?? '') === 'module') {
                $modules[] = [
                    'id' => (string) ($f['id'] ?? ''),
                    'type' => (string) ($f['type'] ?? ''),
                ];
            } else {
                $regions[] = [
                    'field' => (string) ($f['field'] ?? ''),
                    'rel' => (string) ($f['rel'] ?? ''),
                    'tag' => (string) ($f['tag'] ?? ''),
                    'id' => (string) ($f['id'] ?? ''),
                ];
            }
        }

        return json_encode([
            'edit_regions' => $regions,
            'modules' => $modules,
            'count' => count($regions) + count($modules),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
