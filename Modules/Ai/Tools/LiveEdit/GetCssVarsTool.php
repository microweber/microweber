<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: read the active template's CSS custom properties (design
 * tokens) — brand/primary colour, button colours, link colour, text and
 * background tokens, fonts, radii, spacing.
 *
 * WHY THIS MATTERS: a new section the model builds inherits these tokens. Without
 * seeing them the model writes e.g. a button that inherits --mw-primary-color
 * (say orange) and drops it on a section it tinted with the SAME token — an
 * orange button on an orange background (invisible). With the palette in hand the
 * model can (a) REUSE the tokens so its work matches the theme, and (b) guarantee
 * contrast — give a CTA a colour that stands out from its background, or override
 * a token globally with set_css_var. The frontend collects the computed values
 * each turn and the controller binds them to 'mw.ai.liveedit.context'. Read-only.
 */
class GetCssVarsTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_css_vars',
            'Read the active template\'s CSS custom properties (design tokens) — '
            . 'brand/primary colour, button background/text colours, link colour, '
            . 'body/heading text colours, backgrounds, fonts, border-radius. Call '
            . 'this BEFORE styling a section so your colours MATCH the theme and, '
            . 'critically, so buttons/CTAs CONTRAST with their background instead of '
            . 'inheriting the same brand colour as the section behind them (e.g. an '
            . 'orange button on an orange band). Reuse these tokens in your CSS '
            . '(var(--mw-primary-color)) or override one globally with set_css_var. '
            . 'Optionally pass a substring to filter (e.g. "btn", "primary", "color").'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'filter',
                type: PropertyType::STRING,
                description: 'Optional filter — return only variables whose name contains '
                    . 'this text (e.g. "primary", "btn", "link", "background"). Omit for all.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $filter = strtolower(trim((string) ($args['filter'] ?? '')));
        $ctx = app()->bound('mw.ai.liveedit.context') ? (array) app('mw.ai.liveedit.context') : [];
        $vars = $ctx['css_vars'] ?? null;

        if (!is_array($vars) || empty($vars)) {
            return $this->handleError(
                'No CSS variables are available for this turn (open the page in Live Edit).'
            );
        }

        $rows = [];
        foreach ($vars as $name => $value) {
            $name = (string) $name;
            if ($filter !== '' && strpos(strtolower($name), $filter) === false) {
                continue;
            }
            $rows[$name] = is_scalar($value) ? (string) $value : '';
        }

        if ($filter !== '' && empty($rows)) {
            return $this->handleError("No CSS variable name matched \"{$filter}\".");
        }

        return json_encode([
            'count' => count($rows),
            'vars' => $rows,
            'hint' => 'Reuse these with var(--name) so your styles match the theme. To '
                . 'make a CTA readable, give it a colour that contrasts with its section '
                . 'background rather than the same brand token. To retint the whole '
                . 'theme, call set_css_var (e.g. --mw-primary-color).',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
