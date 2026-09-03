<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit design tool: apply custom CSS to the current site.
 *
 * ARCHITECTURE: edits flow through the DOM exactly like a user editing in Live
 * Edit, and persist via the existing Live-Edit SAVE function — NOT server-side.
 * So this tool does NOT write anything itself. It validates the CSS and echoes
 * it back; the agent-chat endpoint surfaces this tool call (name + `css` arg) to
 * the Live-Edit front-end, which applies it to the canvas via the live CSS
 * editor (mw.top().app.cssEditor) and registers the change so the user's SAVE
 * persists it through the normal pipeline. Keeping this side-effect-free avoids
 * double-writing + keeps the user in control of what gets saved.
 */
class ApplyCssTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'apply_css',
            'Apply custom CSS to the current website to change its visual design '
            . '(colors, fonts, sizes, spacing, borders, backgrounds, layout, etc.). '
            . 'Provide one or more COMPLETE CSS rules (selector + declarations). The '
            . 'change is applied live on the page in the editor; the user saves it '
            . 'with the Live-Edit Save button. Always use this tool to make visual '
            . 'changes rather than only describing them.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'css',
                type: PropertyType::STRING,
                description: 'One or more complete CSS rules to apply, e.g. '
                    . '"h1 { color: #e63946; } .btn { border-radius: 8px; }". Include '
                    . 'selectors and declarations. Use selectors likely present on a '
                    . 'Microweber page (body, section, h1-h6, p, a, .btn, .module, '
                    . 'img, .container). Appended to the existing custom CSS.',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $css = trim((string) ($args['css'] ?? ''));
        if ($css === '') {
            return $this->handleError('No CSS was provided to apply.');
        }

        // Side-effect-free by design: do NOT persist here. The agent-chat endpoint
        // surfaces this tool call (name + `css`) to the Live-Edit front-end, which
        // applies it to the real canvas DOM via the live CSS editor and marks it
        // dirty so the user's Live-Edit SAVE persists it through the normal
        // pipeline. Echo the CSS back so the model can confirm the change.
        return "OK — this CSS will be applied live on the page (the user saves it "
            . "with the Live-Edit Save button):\n" . $css;
    }
}
