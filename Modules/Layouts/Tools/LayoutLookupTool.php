<?php

declare(strict_types=1);

namespace Modules\Layouts\Tools;

use Illuminate\Support\Str;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class LayoutLookupTool extends AbstractLayoutTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'layout_lookup',
            'Search installed template layouts by template, name, file, category, or content type.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'template_name', type: PropertyType::STRING, description: 'Optional template name. Defaults to the active template.', required: false),
            new ToolProperty(name: 'layout_file', type: PropertyType::STRING, description: 'Optional relative layout file for an exact lookup.', required: false),
            new ToolProperty(name: 'search_term', type: PropertyType::STRING, description: 'Optional search term for layout name, description, category, or file.', required: false),
            new ToolProperty(name: 'category', type: PropertyType::STRING, description: 'Optional exact category filter.', required: false),
            new ToolProperty(name: 'content_type', type: PropertyType::STRING, description: 'Optional content type filter such as static or dynamic.', required: false),
            new ToolProperty(name: 'include_hidden', type: PropertyType::STRING, description: 'Optional hidden-layout filter: yes or no. Defaults to no.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum layout rows to return (1-50). Default is 10.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $template = $this->resolveTemplate($args['template_name'] ?? null);
        $layoutFile = trim(str_replace('\\', '/', (string) ($args['layout_file'] ?? '')));
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $category = trim((string) ($args['category'] ?? ''));
        $contentType = strtolower(trim((string) ($args['content_type'] ?? '')));
        $includeHidden = $this->normalizeNullableBoolean($args['include_hidden'] ?? '') ?? false;
        $limit = $this->safeLimit($args['limit'] ?? 10, 10, 50);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to inspect layouts and template settings.');
        }

        if (! $template) {
            return $this->handleError('The requested template could not be found.');
        }

        try {
            $layouts = $this->templateLayouts($template)
                ->filter(function (array $layout) use ($layoutFile, $searchTerm, $category, $contentType, $includeHidden): bool {
                    $isHidden = $this->isTruthy($layout['hidden'] ?? null) || strtolower(trim((string) ($layout['visible'] ?? ''))) === 'false';

                    if (! $includeHidden && $isHidden) {
                        return false;
                    }

                    if ($layoutFile !== '' && strcasecmp((string) ($layout['layout_file'] ?? ''), $layoutFile) !== 0) {
                        return false;
                    }

                    if ($category !== '' && strcasecmp((string) ($layout['category'] ?? ''), $category) !== 0) {
                        return false;
                    }

                    if ($contentType !== '' && strtolower((string) ($layout['content_type'] ?? '')) !== $contentType) {
                        return false;
                    }

                    if ($searchTerm === '') {
                        return true;
                    }

                    $needle = Str::lower($searchTerm);
                    $haystack = Str::lower(implode(' ', array_filter([
                        (string) ($layout['name'] ?? ''),
                        (string) ($layout['description'] ?? ''),
                        (string) ($layout['category'] ?? ''),
                        (string) ($layout['content_type'] ?? ''),
                        (string) ($layout['layout_file'] ?? ''),
                    ])));

                    return str_contains($haystack, $needle);
                })
                ->take($limit)
                ->values();

            if ($layouts->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'layout' => 'Layout',
                        'file' => 'File',
                    ],
                    'No layouts matched the requested filters.',
                    'layouts-lookup-empty'
                );
            }

            $rows = $layouts->map(function (array $layout): array {
                $isHidden = $this->isTruthy($layout['hidden'] ?? null) || strtolower(trim((string) ($layout['visible'] ?? ''))) === 'false';

                return [
                    'layout' => (string) ($layout['name'] ?? 'Untitled layout'),
                    'category' => (string) ($layout['category'] ?? 'All'),
                    'content_type' => (string) ($layout['content_type'] ?? 'Any'),
                    'file' => (string) ($layout['layout_file'] ?? ''),
                    'default' => $this->yesNoLabel($this->isTruthy($layout['is_default'] ?? null)),
                    'hidden' => $this->yesNoLabel($isHidden),
                ];
            })->all();

            $summaryBits = [
                '<strong>Template:</strong> ' . e($template->getName()),
                '<strong>Found:</strong> ' . $layouts->count() . ' layout(s)',
            ];

            if ($searchTerm !== '') {
                $summaryBits[] = '<strong>Search:</strong> "' . e($searchTerm) . '"';
            }
            if ($contentType !== '') {
                $summaryBits[] = '<strong>Content type:</strong> ' . e($contentType);
            }
            if ($category !== '') {
                $summaryBits[] = '<strong>Category:</strong> ' . e($category);
            }

            $header = '<h4>Layouts lookup</h4><p>' . implode(' | ', $summaryBits) . '</p>';

            return $header . $this->formatAsHtmlTable(
                $rows,
                [
                    'layout' => 'Layout',
                    'category' => 'Category',
                    'content_type' => 'Content type',
                    'file' => 'File',
                    'default' => 'Default',
                    'hidden' => 'Hidden',
                ],
                'No layouts matched the requested filters.',
                'layouts-lookup-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading template layouts: ' . $exception->getMessage());
        }
    }
}
