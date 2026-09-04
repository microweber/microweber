<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;

/**
 * Live-Edit tool: read the layout/section the user has currently SELECTED.
 *
 * Like get_selected_element but for the containing layout/section handle — use it
 * for requests like "change THIS section's background" or "delete this block".
 * The frontend sends the selected layout each turn; the controller binds it to
 * 'mw.ai.liveedit.context'. Returns its `selector` (safe for apply_css /
 * delete_element / move_element) plus tag/classes/text. Read-only.
 */
class GetSelectedLayoutTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_selected_layout',
            'Get the layout/section the user has SELECTED on the canvas — use this to '
            . 'resolve "this section", "this block", "here" when the user means a whole '
            . 'section. Returns its `selector` (pass to apply_css/delete_element/'
            . 'move_element), tag, classes and text.'
        );
    }

    protected function properties(): array
    {
        return [];
    }

    public function __invoke(...$args): string
    {
        $ctx = app()->bound('mw.ai.liveedit.context') ? (array) app('mw.ai.liveedit.context') : [];
        $sel = $ctx['selected_layout'] ?? null;
        if (!is_array($sel) || empty($sel['selector'])) {
            return $this->handleError(
                'No section/layout is selected on the canvas right now. Ask the user to click the section they mean.'
            );
        }
        return json_encode(['selected_layout' => $sel], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
