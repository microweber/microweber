<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;

/**
 * Live-Edit tool: read the element the user has currently SELECTED in the editor.
 *
 * When the user says "change THIS to red", "make this bigger", "move it up" etc.,
 * "this/it" is whatever they have clicked/selected on the canvas. The frontend
 * sends the selected element (a stable selector + tag/classes/text/styles) each
 * turn; the controller binds it to 'mw.ai.liveedit.context'. This tool returns it
 * so the model can resolve the reference and act on the right element (its
 * `selector` is safe to pass to apply_css / set_text / set_image / delete_element).
 * Read-only.
 */
class GetSelectedElementTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_selected_element',
            'Get the element the user has SELECTED on the canvas — use this to '
            . 'resolve "this", "that", "it", "here", "the selected element" in the '
            . 'user\'s request. Returns its `selector` (pass it to apply_css/set_text/'
            . 'set_image/delete_element), tag, classes, text and current styles.'
        );
    }

    protected function properties(): array
    {
        return [];
    }

    public function __invoke(...$args): string
    {
        $ctx = app()->bound('mw.ai.liveedit.context') ? (array) app('mw.ai.liveedit.context') : [];
        $sel = $ctx['selected_element'] ?? null;
        if (!is_array($sel) || empty($sel['selector'])) {
            return $this->handleError(
                'Nothing is selected on the canvas right now. Ask the user to click the element they mean, '
                . 'or target it by a selector from the page markup.'
            );
        }
        return json_encode(['selected_element' => $sel], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
