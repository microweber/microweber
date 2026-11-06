<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit design tool: apply custom CSS to the CURRENT PAGE ONLY (by content id).
 *
 * The page-scoped sibling of apply_css. Where apply_css writes the GLOBAL,
 * site-wide live_edit.css (every page), this writes a per-page layer keyed by the
 * current page's content id (userfiles/css/pages/live_edit_<id>.css), served as
 * the FINAL stylesheet on that page so it overrides the site-wide design there.
 * Use it when the user wants a change on THIS page only ("just this page", "only
 * here", "on the home page only"). Like apply_css it is side-effect-free here: the
 * front-end applies it to the canvas for preview and persists it to the per-page
 * file via api/page_save_custom_css (saved immediately — no Live-Edit Save needed).
 */
class ApplyPageCssTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'apply_page_css',
            'Apply custom CSS to the CURRENT PAGE ONLY (scoped to this page by its '
            . 'content id), not the whole site. Use this instead of apply_css when '
            . 'the user wants a visual change on just this one page ("only on this '
            . 'page", "just the home page", "here only"). The change previews live in '
            . 'the editor and is saved immediately to the page-scoped CSS; it loads '
            . 'after the global design so it overrides the site-wide look on this page.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'css',
                type: PropertyType::STRING,
                description: 'One or more complete CSS rules to apply to THIS page '
                    . 'only, e.g. "section.hero { background: #c8102e; } h1 { color: '
                    . '#fff; }". Include selectors and declarations. Merged into the '
                    . 'page\'s existing per-page CSS.',
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

        return "OK — this CSS will be applied live and saved for THIS page only "
            . "(scoped by content id):\n" . $css;
    }
}
