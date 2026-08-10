<?php

declare(strict_types=1);

namespace Modules\Layouts\Tools;

use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class LayoutAssetSummaryTool extends AbstractLayoutTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'layout_asset_summary',
            'Summarize template asset categories and safe relative design/style references.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'template_name', type: PropertyType::STRING, description: 'Optional template name. Defaults to the active template.', required: false),
            new ToolProperty(name: 'asset_type', type: PropertyType::STRING, description: 'Optional asset type filter: all, views, css, js, images, or design.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum asset rows to return (1-50). Default is 12.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $template = $this->resolveTemplate($args['template_name'] ?? null);
        $assetType = $this->normalizeAssetType($args['asset_type'] ?? 'all');
        $limit = $this->safeLimit($args['limit'] ?? 12, 12, 50);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to inspect layouts and template settings.');
        }

        if (! $template) {
            return $this->handleError('The requested template could not be found.');
        }

        try {
            $assets = $this->templateAssets($template);
            $filteredAssets = $assetType === 'all'
                ? $assets
                : $assets->where('type', $assetType)->values();
            $priorityPaths = collect(array_merge(
                ['style-settings.json'],
                $this->styleReferencePaths($template)
            ))
                ->filter(static fn (string $path): bool => $path !== '')
                ->values();

            $assetCounts = $assets
                ->groupBy('type')
                ->map(static fn ($group): int => $group->count())
                ->sortKeys()
                ->map(static fn (int $count, string $type): array => [
                    'type' => ucfirst($type),
                    'count' => (string) $count,
                ])
                ->values()
                ->all();

            $styleReferenceRows = collect($this->styleReferencePaths($template))
                ->take($limit)
                ->map(static fn (string $path): array => ['path' => $path])
                ->all();

            $assetRows = $filteredAssets
                ->sortBy(function (array $asset) use ($priorityPaths): array {
                    $index = $priorityPaths->search($asset['path']);
                    $priority = $index === false ? 9999 : (int) $index;

                    return [$priority, $asset['path']];
                }, SORT_REGULAR)
                ->take($limit)
                ->map(fn (array $asset): array => [
                    'path' => $asset['path'],
                    'type' => ucfirst($asset['type']),
                    'size' => $this->formatFileSize((int) $asset['size']),
                ])
                ->all();

            $header = '<h4>Template asset summary</h4><p>'
                . '<strong>Template:</strong> ' . e($template->getName())
                . ' | <strong>Path:</strong> ' . e($this->templateRelativePath($template))
                . ' | <strong>Filter:</strong> ' . e($assetType)
                . ' | <strong>Matching assets:</strong> ' . $filteredAssets->count()
                . '</p>';

            $output = $header
                . $this->formatAsHtmlTable(
                    $assetCounts,
                    [
                        'type' => 'Asset type',
                        'count' => 'Count',
                    ],
                    'No template assets were found.',
                    'layouts-asset-summary-counts'
                );

            if ($styleReferenceRows !== []) {
                $output .= '<h5>Style setting references</h5>';
                $output .= $this->formatAsHtmlTable(
                    $styleReferenceRows,
                    ['path' => 'Referenced path'],
                    'No style-setting references were found.',
                    'layouts-asset-summary-style-refs'
                );
            }

            if ($assetRows !== []) {
                $output .= '<h5>Sample asset references</h5>';
                $output .= $this->formatAsHtmlTable(
                    $assetRows,
                    [
                        'path' => 'Relative path',
                        'type' => 'Type',
                        'size' => 'Size',
                    ],
                    'No template assets matched the requested filter.',
                    'layouts-asset-summary-assets'
                );
            }

            return $output;
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading template asset summary: ' . $exception->getMessage());
        }
    }
}
