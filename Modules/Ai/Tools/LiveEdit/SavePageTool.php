<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;

/**
 * Live-Edit frontend tool: save the current page.
 *
 * Triggers the normal Live-Edit SAVE (content + custom CSS) so the work is
 * persisted. The agent MUST call this before navigate_to_page, otherwise the
 * unsaved changes on the current page are lost when the canvas loads another
 * page. Frontend tool (see mw-ai.js frontendTools.save_page); side-effect-free
 * on the backend.
 */
class SavePageTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'save_page',
            'Save the current page now (persists the content and design edits). '
            . 'ALWAYS call this before navigate_to_page so nothing is lost when you '
            . 'move to another page.'
        );
    }

    public function __invoke(...$args): string
    {
        return 'OK — saving the current page.';
    }
}
