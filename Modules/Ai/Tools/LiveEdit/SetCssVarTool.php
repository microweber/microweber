<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit design tool: set (override) the template's CSS custom properties
 * (design tokens) globally — e.g. retint the brand colour so every themed
 * element updates at once: {"vars": {"--mw-primary-color": "#0d6efd"}}.
 *
 * ARCHITECTURE: like apply_css, this is side-effect-free on the server. The
 * agent-chat endpoint surfaces the tool call to the Live-Edit front-end, which
 * writes a `:root { … }` rule through the global custom-CSS pipeline (so it wins
 * by source order and the user's SAVE persists it). Prefer this over apply_css
 * when you want to shift the WHOLE theme (all buttons/links/accents) at once;
 * use apply_css for one-off element styling. Discover current token names/values
 * first with get_css_vars.
 */
class SetCssVarTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'set_css_var',
            'Set/override one or more of the template\'s CSS custom properties (design '
            . 'tokens) globally, e.g. change the brand colour --mw-primary-color, the '
            . 'button colours --mw-btn-background-color / --mw-btn-text-color, or the '
            . 'link colour --mw-link-color. Use this to shift the WHOLE theme at once '
            . '(every themed element follows) — for a single element use apply_css. '
            . 'Call get_css_vars first to learn the exact token names and current values.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'vars',
                type: PropertyType::OBJECT,
                description: 'Map of CSS custom property name to value, e.g. '
                    . '{"--mw-primary-color": "#0d6efd", "--mw-btn-text-color": "#fff"}. '
                    . 'Names should start with "--". Applied as a global :root rule.',
                required: false,
            ),
            new ToolProperty(
                name: 'name',
                type: PropertyType::STRING,
                description: 'Single variable name (alternative to `vars`), e.g. "--mw-primary-color".',
                required: false,
            ),
            new ToolProperty(
                name: 'value',
                type: PropertyType::STRING,
                description: 'Value for the single `name` variable, e.g. "#0d6efd".',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $vars = $args['vars'] ?? null;
        if (is_string($vars) && $vars !== '') {
            $decoded = json_decode($vars, true);
            $vars = is_array($decoded) ? $decoded : null;
        }
        if (!is_array($vars) || empty($vars)) {
            $name = trim((string) ($args['name'] ?? ''));
            if ($name === '') {
                return $this->handleError('Provide `vars` (a name→value map) or a single `name` + `value`.');
            }
            $vars = [$name => (string) ($args['value'] ?? '')];
        }

        $decls = [];
        foreach ($vars as $name => $value) {
            $name = trim((string) $name);
            if ($name === '') {
                continue;
            }
            if (strncmp($name, '--', 2) !== 0) {
                $name = '--' . ltrim($name, '-');
            }
            $value = trim((string) $value);
            if ($value !== '') {
                $decls[$name] = $value;
            }
        }

        if (empty($decls)) {
            return $this->handleError('No valid CSS variable declarations were provided.');
        }

        // Side-effect-free: the front-end applies these as a global :root rule and
        // the user's Live-Edit SAVE persists them. Echo back for confirmation.
        $preview = ":root {\n";
        foreach ($decls as $n => $v) {
            $preview .= "  {$n}: {$v};\n";
        }
        $preview .= "}";

        return "OK — these design tokens will be set globally on the page (the user "
            . "saves with the Live-Edit Save button):\n" . $preview;
    }
}
