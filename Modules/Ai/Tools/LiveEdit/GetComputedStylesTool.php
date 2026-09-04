<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: read the RENDERED (computed) CSS of elements on the canvas.
 *
 * A screenshot shows the model what the page looks like, but not the actual
 * values — so it cannot tell that, say, the nav links have a transparent
 * background and no padding (i.e. are unstyled). The frontend computes a compact
 * style summary (getComputedStyle) for the design-critical elements each turn
 * and the controller binds it to 'mw.ai.liveedit.context'. This tool returns
 * that map so the model can verify styling and spot unstyled/misstyled areas —
 * e.g. see the nav is unstyled and fix it. Read-only.
 */
class GetComputedStylesTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_computed_styles',
            'Read the rendered (computed) CSS of the page\'s key elements — nav/header, '
            . 'headings, links, buttons, sections and body — so you can SEE actual '
            . 'colours, backgrounds, fonts, padding and borders (a screenshot alone '
            . 'does not tell you these). Use it to detect unstyled or mis-styled areas '
            . '(e.g. a nav with a transparent background and no padding is unstyled) and '
            . 'verify a style you applied actually took effect. Optionally pass a CSS '
            . 'selector substring to filter to matching elements.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'selector',
                type: PropertyType::STRING,
                description: 'Optional filter — return only elements whose selector contains this '
                    . 'text (e.g. "nav", ".pixel-nav", "h1", ".btn"). Omit for all collected elements.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $filter = strtolower(trim((string) ($args['selector'] ?? '')));
        $ctx = app()->bound('mw.ai.liveedit.context') ? (array) app('mw.ai.liveedit.context') : [];
        $styles = $ctx['computed_styles'] ?? null;

        if (!is_array($styles) || empty($styles)) {
            return $this->handleError(
                'No computed styles are available for this turn (open the page in Live Edit).'
            );
        }

        $rows = [];
        foreach ($styles as $entry) {
            if (!is_array($entry)) {
                continue;
            }
            $sel = (string) ($entry['selector'] ?? '');
            if ($filter !== '' && strpos(strtolower($sel), $filter) === false) {
                continue;
            }
            $rows[] = $entry;
        }

        if ($filter !== '' && empty($rows)) {
            return $this->handleError("No collected element matched \"{$filter}\".");
        }

        return json_encode([
            'count' => count($rows),
            'elements' => $rows,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
