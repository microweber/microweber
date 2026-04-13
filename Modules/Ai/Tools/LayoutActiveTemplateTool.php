<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Modules\Content\Models\Content;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class LayoutActiveTemplateTool extends AbstractLayoutTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'layout_active_template',
            'Summarize the active template state, linked content usage, and style-setting groups.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'template_name', type: PropertyType::STRING, description: 'Optional template name. Defaults to the active template.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum usage rows to return (1-20). Default is 5.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $template = $this->resolveTemplate($args['template_name'] ?? null);
        $limit = $this->safeLimit($args['limit'] ?? 5, 5, 20);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to inspect layouts and template settings.');
        }

        if (! $template) {
            return $this->handleError('The requested template could not be found.');
        }

        try {
            $config = $this->templateConfig($template);
            $layouts = $this->templateLayouts($template);
            $styleGroups = $this->styleGroups($template);
            $styleReferences = $this->styleReferencePaths($template);
            $templateName = $template->getName();
            $isActive = strcasecmp($this->activeTemplateName(), $templateName) === 0;

            $linkedContentCount = Content::query()
                ->where('active_site_template', $templateName)
                ->count();

            $homePageCount = Content::query()
                ->where('active_site_template', $templateName)
                ->where('is_home', 1)
                ->count();

            $shopPageCount = Content::query()
                ->where('active_site_template', $templateName)
                ->where('is_shop', 1)
                ->count();

            $summaryRows = [[
                'template' => $templateName,
                'alias' => (string) ($config['alias'] ?? strtolower($templateName)),
                'enabled' => $this->yesNoLabel($template->isEnabled()),
                'active' => $this->yesNoLabel($isActive),
                'path' => $this->templateRelativePath($template),
                'layouts' => (string) $layouts->count(),
                'style_groups' => (string) count($styleGroups),
                'style_refs' => (string) count($styleReferences),
                'linked_content' => (string) $linkedContentCount,
                'home_pages' => (string) $homePageCount,
                'shop_pages' => (string) $shopPageCount,
            ]];

            $usageRows = collect($this->layoutUsageRows($templateName, $limit))
                ->map(static fn (array $row): array => [
                    'layout_file' => $row['layout_file'],
                    'count' => (string) $row['count'],
                ])
                ->all();

            $recentContentRows = collect($this->recentTemplateContent($templateName, $limit))
                ->map(static function (Content $content): array {
                    return [
                        'title' => (string) ($content->title ?? 'Untitled'),
                        'type' => (string) ($content->content_type ?? 'content'),
                        'url' => (string) ($content->url ?? ''),
                        'layout' => (string) ($content->layout_file ?? ''),
                        'flags' => implode(', ', array_filter([
                            (int) ($content->is_home ?? 0) === 1 ? 'home' : null,
                            (int) ($content->is_shop ?? 0) === 1 ? 'shop' : null,
                            (int) ($content->is_active ?? 0) === 1 ? 'active' : null,
                        ])) ?: 'none',
                    ];
                })
                ->all();

            $styleGroupRows = collect($styleGroups)
                ->take($limit)
                ->map(static fn (array $group): array => [
                    'group' => $group['title'],
                    'references' => (string) $group['reference_count'],
                ])
                ->all();

            $header = '<h4>Active template summary</h4><p>'
                . '<strong>Selected template:</strong> ' . e($templateName)
                . ' | <strong>Currently active:</strong> ' . $this->yesNoLabel($isActive)
                . '</p>';

            $output = $header
                . $this->formatAsHtmlTable(
                    $summaryRows,
                    [
                        'template' => 'Template',
                        'alias' => 'Alias',
                        'enabled' => 'Enabled',
                        'active' => 'Active',
                        'path' => 'Path',
                        'layouts' => 'Layouts',
                        'style_groups' => 'Style groups',
                        'style_refs' => 'Style refs',
                        'linked_content' => 'Linked content',
                        'home_pages' => 'Home pages',
                        'shop_pages' => 'Shop pages',
                    ],
                    'No template summary data is available.',
                    'layouts-active-template-summary'
                );

            if ($usageRows !== []) {
                $output .= '<h5>Top layout assignments</h5>';
                $output .= $this->formatAsHtmlTable(
                    $usageRows,
                    [
                        'layout_file' => 'Layout file',
                        'count' => 'Assignments',
                    ],
                    'No content items are currently linked to this template.',
                    'layouts-active-template-usage'
                );
            }

            if ($recentContentRows !== []) {
                $output .= '<h5>Recent template-linked content</h5>';
                $output .= $this->formatAsHtmlTable(
                    $recentContentRows,
                    [
                        'title' => 'Title',
                        'type' => 'Type',
                        'url' => 'URL',
                        'layout' => 'Layout',
                        'flags' => 'Flags',
                    ],
                    'No content items are currently linked to this template.',
                    'layouts-active-template-content'
                );
            }

            if ($styleGroupRows !== []) {
                $output .= '<h5>Style setting groups</h5>';
                $output .= $this->formatAsHtmlTable(
                    $styleGroupRows,
                    [
                        'group' => 'Group',
                        'references' => 'Referenced files',
                    ],
                    'No style-setting groups were found for this template.',
                    'layouts-active-template-style-groups'
                );
            }

            return $output;
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading template summary: ' . $exception->getMessage());
        }
    }
}
