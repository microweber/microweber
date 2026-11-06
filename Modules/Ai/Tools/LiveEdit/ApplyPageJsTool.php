<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: set custom JavaScript for the CURRENT PAGE ONLY (by content id).
 *
 * The page-scoped sibling of apply_js. Writes a per-page JS layer keyed by the
 * current page's content id (userfiles/css/pages/live_edit_<id>.js), served as a
 * deferred <script> on that page's published footer only (never in the editor).
 * OVERWRITE semantics: pass the COMPLETE script each time (it replaces the page's
 * JS). Side-effect-free here; the front-end persists it via api/page_save_custom_js.
 */
class ApplyPageJsTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'apply_page_js',
            'Set custom JavaScript for the CURRENT PAGE ONLY (scoped to this page by '
            . 'its content id), not the whole site. Use for interactivity/behaviour '
            . 'that should run on just this one page. Pass the COMPLETE script each '
            . 'time (overwrite semantics). It runs on the PUBLISHED page only (never '
            . 'in the editor), so tell the user to open/reload the live page to see '
            . 'it. Use apply_js instead for site-wide behaviour.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'js',
                type: PropertyType::STRING,
                description: 'The complete JavaScript for THIS page (vanilla JS, an '
                    . 'IIFE guarded by DOMContentLoaded, honouring prefers-reduced-'
                    . 'motion; no <script> tags). Replaces the page\'s existing JS.',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $js = trim((string) ($args['js'] ?? ''));
        if ($js === '') {
            return $this->handleError('No JavaScript was provided.');
        }

        return "OK — this JavaScript will be saved for THIS page only (runs on the "
            . "published page; reload it to see the effect).";
    }
}
