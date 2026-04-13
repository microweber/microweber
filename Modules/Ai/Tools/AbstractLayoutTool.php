<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;
use Modules\Content\Models\Content;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

abstract class AbstractLayoutTool extends BaseTool
{
    protected string $domain = 'layouts';

    protected array $requiredPermissions = ['manage_settings'];

    protected function safeLimit(mixed $limit, int $default = 10, int $max = 50): int
    {
        return max(1, min($max, (int) ($limit ?? $default)));
    }

    protected function normalizeNullableBoolean(mixed $value): ?bool
    {
        $normalized = strtolower(trim((string) $value));

        if ($normalized === '') {
            return null;
        }

        if (in_array($normalized, ['1', 'true', 'yes', 'y'], true)) {
            return true;
        }

        if (in_array($normalized, ['0', 'false', 'no', 'n'], true)) {
            return false;
        }

        return null;
    }

    protected function normalizeAssetType(mixed $value): string
    {
        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, ['all', 'views', 'css', 'js', 'images', 'design'], true)
            ? $normalized
            : 'all';
    }

    protected function activeTemplateName(): string
    {
        $template = trim((string) app()->option_manager->get('current_template', 'template'));

        if ($template !== '') {
            return $template;
        }

        $first = collect(app()->templates->all())->keys()->first();

        return is_string($first) && $first !== '' ? $first : 'Bootstrap';
    }

    protected function resolveTemplate(mixed $value = null): ?LaravelTemplate
    {
        $requested = trim((string) ($value ?? ''));
        $requested = $requested !== '' ? $requested : $this->activeTemplateName();

        $template = app()->templates->find($requested);
        if ($template instanceof LaravelTemplate) {
            return $template;
        }

        $normalized = strtolower($requested);

        foreach (app()->templates->all() as $candidate) {
            if (! $candidate instanceof LaravelTemplate) {
                continue;
            }

            if (strtolower($candidate->getName()) === $normalized) {
                return $candidate;
            }
        }

        return null;
    }

    protected function templateConfig(LaravelTemplate $template): array
    {
        $config = app()->template_manager->getConfig($template->getName());

        return is_array($config) ? $config : [];
    }

    protected function templateRelativePath(LaravelTemplate $template): string
    {
        $path = str_replace('\\', '/', $template->getPath());
        $basePath = rtrim(str_replace('\\', '/', base_path()), '/');

        if (str_starts_with($path, $basePath . '/')) {
            return Str::after($path, $basePath . '/');
        }

        return $path;
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    protected function templateLayouts(LaravelTemplate $template): Collection
    {
        $layouts = app()->layouts_manager->get_all([
            'site_template' => $template->getName(),
            'no_cache' => true,
        ]);

        return collect(is_array($layouts) ? $layouts : [])
            ->filter(static fn (mixed $layout): bool => is_array($layout) && (($layout['type'] ?? '') === 'layout'))
            ->values();
    }

    /**
     * @return array<string, mixed>
     */
    protected function styleSettings(LaravelTemplate $template): array
    {
        $settings = app()->template_manager->getStyleSettings($template->getPath());

        return is_array($settings) ? $settings : [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function rawStyleSettings(LaravelTemplate $template): array
    {
        $path = rtrim($template->getPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'style-settings.json';

        if (! is_file($path)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @return array<int, string>
     */
    protected function styleReferencePaths(LaravelTemplate $template): array
    {
        $raw = $this->rawStyleSettings($template);
        $references = [];

        $collect = function (mixed $node) use (&$collect, &$references): void {
            if (! is_array($node)) {
                return;
            }

            foreach ([
                'readSettingsFromFiles',
                'mergeFieldSettingsStylePropertiesFromFiles',
                'mergeFieldSettingsStylePropertiesFromFolders',
            ] as $key) {
                $items = $node[$key] ?? null;
                if (! is_array($items)) {
                    continue;
                }

                foreach ($items as $item) {
                    $path = trim((string) $item);
                    if ($path !== '') {
                        $references[] = str_replace('\\', '/', $path);
                    }
                }
            }

            foreach ($node as $child) {
                $collect($child);
            }
        };

        $collect($raw);

        return array_values(array_unique($references));
    }

    /**
     * @return array<int, array{title: string, reference_count: int}>
     */
    protected function styleGroups(LaravelTemplate $template): array
    {
        $settings = data_get($this->styleSettings($template), 'settings', []);

        if (! is_array($settings)) {
            return [];
        }

        return collect($settings)
            ->filter(static fn (mixed $item): bool => is_array($item))
            ->map(function (array $item): array {
                return [
                    'title' => trim((string) ($item['title'] ?? 'Untitled group')),
                    'reference_count' => is_array($item['readSettingsFromFiles'] ?? null)
                        ? count($item['readSettingsFromFiles'])
                        : 0,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{layout_file: string, count: int}>
     */
    protected function layoutUsageRows(string $templateName, int $limit = 5): array
    {
        return Content::query()
            ->where('active_site_template', $templateName)
            ->whereNotNull('layout_file')
            ->where('layout_file', '!=', '')
            ->select('layout_file')
            ->selectRaw('count(*) as aggregate_count')
            ->groupBy('layout_file')
            ->orderByDesc('aggregate_count')
            ->limit($limit)
            ->get()
            ->map(static fn (Content $content): array => [
                'layout_file' => (string) $content->layout_file,
                'count' => (int) ($content->aggregate_count ?? 0),
            ])
            ->all();
    }

    /**
     * @return array<int, Content>
     */
    protected function recentTemplateContent(string $templateName, int $limit = 5): array
    {
        return Content::query()
            ->where('active_site_template', $templateName)
            ->latest('id')
            ->limit($limit)
            ->get()
            ->all();
    }

    /**
     * @return Collection<int, array{path: string, type: string, size: int}>
     */
    protected function templateAssets(LaravelTemplate $template): Collection
    {
        $root = rtrim(str_replace('\\', '/', $template->getPath()), '/');
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $rows = [];

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $absolutePath = str_replace('\\', '/', $file->getPathname());
            $relativePath = ltrim(Str::after($absolutePath, $root), '/');

            if ($relativePath === '') {
                continue;
            }

            $type = $this->classifyAssetPath($relativePath);
            if ($type === null) {
                continue;
            }

            $rows[] = [
                'path' => $relativePath,
                'type' => $type,
                'size' => (int) $file->getSize(),
            ];
        }

        return collect($rows)
            ->sortBy('path', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    }

    protected function classifyAssetPath(string $relativePath): ?string
    {
        $path = strtolower(str_replace('\\', '/', $relativePath));
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($path === 'style-settings.json' || str_starts_with($path, 'resources/assets/design-styles/')) {
            return 'design';
        }

        if (str_starts_with($path, 'resources/views/')) {
            return 'views';
        }

        if (in_array($extension, ['css', 'scss', 'sass'], true)) {
            return 'css';
        }

        if (in_array($extension, ['js', 'ts'], true)) {
            return 'js';
        }

        if (in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'avif', 'ico'], true)) {
            return 'images';
        }

        return null;
    }

    protected function formatFileSize(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = min((int) floor(log($bytes, 1024)), count($units) - 1);

        return number_format($bytes / (1024 ** $power), $power === 0 ? 0 : 1) . ' ' . $units[$power];
    }

    protected function yesNoLabel(bool $value): string
    {
        return $value ? 'Yes' : 'No';
    }

    protected function isTruthy(mixed $value): bool
    {
        return in_array(strtolower(trim((string) $value)), ['1', 'true', 'yes', 'y'], true);
    }
}
