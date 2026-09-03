<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit context tool: read what is currently on a page.
 *
 * Gives the conversational Live-Edit agent awareness of the page it is editing —
 * title, url, template/layout, a text excerpt of the content, and the current
 * site custom CSS — so it can make relevant, targeted design/content changes
 * instead of guessing. Read-only.
 */
class GetPageContextTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'get_page_context',
            'Read the current page being edited: its title, URL, template, layout, '
            . 'a text excerpt of its content, and the site\'s current custom CSS. '
            . 'Call this first when you need to know what is on the page before '
            . 'making a change, or when the user refers to "this page"/"the heading" etc.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'content_id',
                type: PropertyType::INTEGER,
                description: 'The id of the page/content being edited. If unknown, pass 0 and the current/home page is used.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $contentId = (int) ($args['content_id'] ?? 0);

        try {
            $content = null;
            if ($contentId > 0 && function_exists('get_content_by_id')) {
                $content = get_content_by_id($contentId);
            }

            $ctx = [];
            if (is_array($content) && !empty($content)) {
                $ctx['id'] = $content['id'] ?? $contentId;
                $ctx['title'] = $content['title'] ?? '';
                $ctx['url'] = $content['url'] ?? '';
                $ctx['content_type'] = $content['content_type'] ?? '';
                $ctx['layout_file'] = $content['layout_file'] ?? ($content['layout'] ?? '');
                $body = strip_tags((string) ($content['content_body'] ?? $content['content'] ?? ''));
                $ctx['content_excerpt'] = mb_substr(trim(preg_replace('/\s+/', ' ', $body)), 0, 600);
            } else {
                $ctx['note'] = 'No specific page resolved; treat changes as site-wide.';
            }

            $ctx['active_template'] = function_exists('template_name') ? template_name() : null;
            try {
                $ctx['current_custom_css'] = mb_substr(trim((string) template_user_custom_css()->getContent()), 0, 2000);
            } catch (\Throwable $e) {
                $ctx['current_custom_css'] = '';
            }

            return json_encode($ctx, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read page context: ' . $e->getMessage());
        }
    }
}
