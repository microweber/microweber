#!/usr/bin/env php
<?php
/**
 * Filament Resource API Documentation Generator
 *
 * Parses all Filament Resource PHP files in the project and generates
 * structured Markdown documentation for each resource, including:
 * - Model binding and navigation config
 * - Form schema (fields, types, validation)
 * - Table columns (types, searchable, sortable)
 * - Filters, actions, bulk actions
 * - Relations and pages
 * - Global search configuration
 *
 * Usage:
 *   php dev/generate-filament-docs.php [--output=docs/api/filament-resources.md]
 */

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$outputFile = $projectRoot . '/docs/api/filament-resources.md';

// Parse CLI args
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--output=')) {
        $outputFile = $projectRoot . '/' . ltrim(substr($arg, 9), '/');
    }
}

// ─── Resource Discovery ───────────────────────────────────────────────────────

function findResourceFiles(string $root): array
{
    $paths = [
        $root . '/Modules',
        $root . '/src/MicroweberPackages',
    ];

    $files = [];
    foreach ($paths as $basePath) {
        if (!is_dir($basePath)) {
            continue;
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($basePath, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if ($file->isFile()
                && str_ends_with($file->getFilename(), 'Resource.php')
                && str_contains($file->getPathname(), 'Filament')
                && !str_contains($file->getPathname(), 'RelationManager')
                && !str_contains($file->getPathname(), 'Concerns')
                && !str_contains($file->getPathname(), '/Pages/')
                && !str_contains($file->getPathname(), '/Widgets/')
            ) {
                $files[] = $file->getPathname();
            }
        }
    }
    sort($files);
    return $files;
}

// ─── PHP File Parser ──────────────────────────────────────────────────────────

function parseResourceFile(string $filePath, string $root): array
{
    $content = file_get_contents($filePath);
    $relativePath = str_replace($root . '/', '', $filePath);

    $info = [
        'file' => $relativePath,
        'class' => extractClassName($content),
        'namespace' => extractNamespace($content),
        'model' => extractModel($content),
        'navigationGroup' => extractStaticProperty($content, 'navigationGroup'),
        'navigationSort' => extractStaticProperty($content, 'navigationSort'),
        'navigationIcon' => extractStaticProperty($content, 'navigationIcon'),
        'navigationLabel' => extractStaticProperty($content, 'navigationLabel'),
        'label' => extractStaticProperty($content, 'label'),
        'description' => extractDescription($content),
        'recordTitleAttribute' => extractStaticProperty($content, 'recordTitleAttribute'),
        'shouldRegisterNavigation' => extractBoolProperty($content, 'shouldRegisterNavigation'),
        'formFields' => extractFormFields($content),
        'tableColumns' => extractTableColumns($content),
        'tableFilters' => extractTableFilters($content),
        'tableActions' => extractTableActions($content),
        'bulkActions' => extractBulkActions($content),
        'relations' => extractRelations($content),
        'pages' => extractPages($content),
        'globalSearch' => extractGlobalSearch($content),
        'widgets' => extractWidgets($content),
        'hasNavigationBadge' => str_contains($content, 'getNavigationBadge'),
    ];

    return $info;
}

function extractClassName(string $content): string
{
    if (preg_match('/class\s+(\w+)\s+extends/', $content, $m)) {
        return $m[1];
    }
    return 'Unknown';
}

function extractNamespace(string $content): string
{
    if (preg_match('/namespace\s+([\w\\\\]+);/', $content, $m)) {
        return $m[1];
    }
    return '';
}

function extractModel(string $content): string
{
    if (preg_match('/\$model\s*=\s*([A-Za-z\\\\]+)::class/', $content, $m)) {
        $parts = explode('\\', $m[1]);
        return end($parts);
    }
    return 'N/A';
}

function extractStaticProperty(string $content, string $name): ?string
{
    // Match: protected static ?type $name = 'value';
    if (preg_match('/\$' . preg_quote($name) . '\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $m)) {
        return $m[1];
    }
    // Match numeric
    if (preg_match('/\$' . preg_quote($name) . '\s*=\s*(\d+)/', $content, $m)) {
        return $m[1];
    }
    return null;
}

function extractBoolProperty(string $content, string $name): ?bool
{
    if (preg_match('/\$' . preg_quote($name) . '\s*=\s*(true|false)/', $content, $m)) {
        return $m[1] === 'true';
    }
    return null;
}

function extractDescription(string $content): ?string
{
    if (preg_match('/\$description\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $m)) {
        return $m[1];
    }
    return null;
}

function extractFormFields(string $content): array
{
    $fields = [];

    // Match form component declarations: Component::make('name')
    $formComponents = [
        'TextInput', 'Textarea', 'Select', 'Toggle', 'Checkbox', 'Radio',
        'DatePicker', 'DateTimePicker', 'TimePicker', 'FileUpload',
        'RichEditor', 'MarkdownEditor', 'Hidden', 'Repeater',
        'ColorPicker', 'KeyValue', 'TagsInput', 'Placeholder',
        'RadioDeck', 'MwFileUpload', 'MwMediaBrowser',
    ];

    $pattern = '/(?:Forms\\\\Components\\\\|' . implode('|', array_map('preg_quote', $formComponents)) . ')::make\([\'"](\w+)[\'"]\)/';

    if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[1] as $idx => $match) {
            $fieldName = $match[0];
            $offset = $match[1];

            // Look ahead for chained methods to determine properties
            $chunk = substr($content, $offset, 500);
            $componentType = 'TextInput';

            // Determine component type from the match context
            $preContext = substr($content, max(0, $matches[0][$idx][1] - 50), 50 + strlen($matches[0][$idx][0]));
            foreach ($formComponents as $comp) {
                if (str_contains($preContext, $comp . '::make')) {
                    $componentType = $comp;
                    break;
                }
            }

            // Check for common modifiers
            $required = (bool) preg_match('/->required\(\)/', $chunk);
            $maxLength = null;
            if (preg_match('/->maxLength\((\d+)\)/', $chunk, $ml)) {
                $maxLength = (int) $ml[1];
            }
            $label = null;
            if (preg_match('/->label\([\'"]([^\'"]+)[\'"]\)/', $chunk, $ll)) {
                $label = $ll[1];
            }
            $helperText = null;
            if (preg_match('/->helperText\([\'"]([^\'"]+)[\'"]\)/', $chunk, $ht)) {
                $helperText = $ht[1];
            }
            $relationship = null;
            if (preg_match('/->relationship\([\'"](\w+)[\'"]/', $chunk, $rel)) {
                $relationship = $rel[1];
            }
            $searchable = (bool) preg_match('/->searchable\(\)/', $chunk);
            $numeric = (bool) preg_match('/->numeric\(\)/', $chunk);
            $email = (bool) preg_match('/->email\(\)/', $chunk);

            // Avoid duplicates by name+type
            $key = $fieldName . ':' . $componentType;
            if (!isset($fields[$key])) {
                $fields[$key] = [
                    'name' => $fieldName,
                    'type' => $componentType,
                    'label' => $label,
                    'required' => $required,
                    'maxLength' => $maxLength,
                    'helperText' => $helperText,
                    'relationship' => $relationship,
                    'searchable' => $searchable,
                    'numeric' => $numeric,
                    'email' => $email,
                ];
            }
        }
    }

    return array_values($fields);
}

function extractTableColumns(string $content): array
{
    $columns = [];

    $columnTypes = [
        'TextColumn', 'BooleanColumn', 'ImageColumn', 'IconColumn',
        'ToggleColumn', 'ViewColumn', 'ImageUrlColumn', 'BadgeColumn',
    ];

    $pattern = '/(?:Tables\\\\Columns\\\\|Columns\\\\|' . implode('|', array_map('preg_quote', $columnTypes)) . ')::make\([\'"]([^\'"]+)[\'"]\)/';

    if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[1] as $idx => $match) {
            $colName = $match[0];
            $offset = $match[1];
            $chunk = substr($content, $offset, 400);

            $columnType = 'TextColumn';
            $preContext = substr($content, max(0, $matches[0][$idx][1] - 30), 30 + strlen($matches[0][$idx][0]));
            foreach ($columnTypes as $ct) {
                if (str_contains($preContext, $ct . '::make')) {
                    $columnType = $ct;
                    break;
                }
            }

            $searchable = (bool) preg_match('/->searchable\(\)/', $chunk);
            $sortable = (bool) preg_match('/->sortable\(\)/', $chunk);
            $toggleable = (bool) preg_match('/->toggleable\(/', $chunk);
            $label = null;
            if (preg_match('/->label\([\'"]([^\'"]+)[\'"]\)/', $chunk, $ll)) {
                $label = $ll[1];
            }
            $badge = (bool) preg_match('/->badge\(\)/', $chunk);

            $columns[] = [
                'name' => $colName,
                'type' => $columnType,
                'label' => $label,
                'searchable' => $searchable,
                'sortable' => $sortable,
                'toggleable' => $toggleable,
                'badge' => $badge,
            ];
        }
    }

    return $columns;
}

function extractTableFilters(string $content): array
{
    $filters = [];
    if (preg_match_all('/Filter::make\([\'"](\w+)[\'"]\)/', $content, $matches)) {
        foreach ($matches[1] as $filterName) {
            $filters[] = $filterName;
        }
    }
    if (preg_match_all('/SelectFilter::make\([\'"](\w+)[\'"]\)/', $content, $matches)) {
        foreach ($matches[1] as $filterName) {
            $filters[] = $filterName;
        }
    }
    return $filters;
}

function extractTableActions(string $content): array
{
    $actions = [];
    // Match named actions in ->actions([...])
    $actionTypes = ['EditAction', 'ViewAction', 'DeleteAction', 'CreateAction', 'ExportAction'];
    foreach ($actionTypes as $type) {
        if (str_contains($content, $type . '::make')) {
            $actions[] = str_replace('Action', '', $type);
        }
    }
    // Custom actions
    if (preg_match_all('/Action::make\([\'"]([^\'"]+)[\'"]\)/', $content, $matches)) {
        foreach ($matches[1] as $name) {
            if (!in_array($name, ['reset', 'openProduct'])) { // skip internal form actions
                $actions[] = $name;
            }
        }
    }
    return array_unique($actions);
}

function extractBulkActions(string $content): array
{
    $actions = [];
    if (str_contains($content, 'DeleteBulkAction')) {
        $actions[] = 'Delete';
    }
    if (str_contains($content, 'ExportBulkAction')) {
        $actions[] = 'Export';
    }
    return $actions;
}

function extractRelations(string $content): array
{
    $relations = [];
    if (preg_match_all('/(\w+RelationManager)::class/', $content, $matches)) {
        foreach ($matches[1] as $rm) {
            $relations[] = $rm;
        }
    }
    return $relations;
}

function extractPages(string $content): array
{
    $pages = [];
    if (preg_match_all("/['\"](\w+)['\"]\s*=>\s*[\\\\A-Za-z]+\\\\([\w]+)::route\(['\"]([^'\"]+)['\"]\)/", $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $pages[] = [
                'key' => $m[1],
                'class' => $m[2],
                'route' => $m[3],
            ];
        }
    }
    return $pages;
}

function extractGlobalSearch(string $content): ?array
{
    if (!str_contains($content, 'getGloballySearchableAttributes')) {
        return null;
    }

    $attrs = [];
    if (preg_match('/getGloballySearchableAttributes.*?return\s*\[([^\]]+)\]/s', $content, $m)) {
        preg_match_all("/['\"]([^'\"]+)['\"]/", $m[1], $am);
        $attrs = $am[1] ?? [];
    }
    return $attrs;
}

function extractWidgets(string $content): array
{
    $widgets = [];
    if (preg_match_all('/(\w+Widget|\w+Stats)::class/', $content, $matches)) {
        $widgets = $matches[1];
    }
    return $widgets;
}

// ─── Module Grouping Helper ───────────────────────────────────────────────────

function getModuleName(string $filePath): string
{
    if (preg_match('/Modules\/(\w+)\//', $filePath, $m)) {
        return $m[1];
    }
    if (preg_match('/MicroweberPackages\/(\w+)\//', $filePath, $m)) {
        return $m[1] . ' (Core)';
    }
    return 'Other';
}

// ─── Markdown Generator ───────────────────────────────────────────────────────

function generateMarkdown(array $resources, string $root): string
{
    $md = [];
    $md[] = '# Filament Admin Resource API Reference';
    $md[] = '';
    $md[] = '> Auto-generated from source code by `dev/generate-filament-docs.php`';
    $md[] = '> Last updated: ' . date('Y-m-d H:i');
    $md[] = '';
    $md[] = 'This document describes every Filament Resource registered in the Microweber admin panel.';
    $md[] = 'Each resource maps to an Eloquent model and exposes form fields (create/edit), table columns (list view),';
    $md[] = 'actions, filters, relation managers, and global search configuration.';
    $md[] = '';

    // Table of Contents
    $md[] = '## Table of Contents';
    $md[] = '';

    $grouped = [];
    foreach ($resources as $r) {
        $module = getModuleName($r['file']);
        $grouped[$module][] = $r;
    }
    ksort($grouped);

    $md[] = '| Module | Resource | Model | Navigation Group |';
    $md[] = '|--------|----------|-------|------------------|';
    foreach ($grouped as $module => $items) {
        foreach ($items as $r) {
            $anchor = strtolower(str_replace(['/', ' ', '(', ')'], ['-', '-', '', ''], $r['class']));
            $navGroup = $r['navigationGroup'] ?? '—';
            $md[] = "| {$module} | [{$r['class']}](#{$anchor}) | `{$r['model']}` | {$navGroup} |";
        }
    }
    $md[] = '';

    // Summary statistics
    $totalFields = 0;
    $totalColumns = 0;
    foreach ($resources as $r) {
        $totalFields += count($r['formFields']);
        $totalColumns += count($r['tableColumns']);
    }
    $md[] = '## Summary';
    $md[] = '';
    $md[] = '| Metric | Count |';
    $md[] = '|--------|-------|';
    $md[] = '| Total Resources | ' . count($resources) . ' |';
    $md[] = '| Total Form Fields | ' . $totalFields . ' |';
    $md[] = '| Total Table Columns | ' . $totalColumns . ' |';
    $md[] = '| Resources with Global Search | ' . count(array_filter($resources, fn($r) => $r['globalSearch'] !== null)) . ' |';
    $md[] = '| Resources with Navigation Badge | ' . count(array_filter($resources, fn($r) => $r['hasNavigationBadge'])) . ' |';
    $md[] = '| Resources with Relations | ' . count(array_filter($resources, fn($r) => !empty($r['relations']))) . ' |';
    $md[] = '| Resources with Widgets | ' . count(array_filter($resources, fn($r) => !empty($r['widgets']))) . ' |';
    $md[] = '';

    // Per-module sections
    foreach ($grouped as $module => $items) {
        $md[] = '---';
        $md[] = '';
        $md[] = "## Module: {$module}";
        $md[] = '';

        foreach ($items as $r) {
            $md[] = "### {$r['class']}";
            $md[] = '';
            if ($r['description']) {
                $md[] = "> {$r['description']}";
                $md[] = '';
            }
            $md[] = '| Property | Value |';
            $md[] = '|----------|-------|';
            $md[] = "| **File** | `{$r['file']}` |";
            $md[] = "| **Namespace** | `{$r['namespace']}` |";
            $md[] = "| **Model** | `{$r['model']}` |";
            $md[] = "| **Navigation Group** | " . ($r['navigationGroup'] ?? '—') . " |";
            $md[] = "| **Navigation Sort** | " . ($r['navigationSort'] ?? '—') . " |";
            $md[] = "| **Navigation Icon** | " . ($r['navigationIcon'] ?? '—') . " |";
            $md[] = "| **Navigation Label** | " . ($r['navigationLabel'] ?? $r['label'] ?? '—') . " |";
            $md[] = "| **Record Title** | " . ($r['recordTitleAttribute'] ?? '—') . " |";
            $hidden = ($r['shouldRegisterNavigation'] === false) ? 'Yes (hidden)' : 'No';
            $md[] = "| **Hidden from Nav** | {$hidden} |";
            $md[] = "| **Navigation Badge** | " . ($r['hasNavigationBadge'] ? 'Yes' : 'No') . " |";
            $md[] = '';

            // Form Fields
            if (!empty($r['formFields'])) {
                $md[] = '#### Form Fields';
                $md[] = '';
                $md[] = '| Field | Component | Label | Required | Validation | Notes |';
                $md[] = '|-------|-----------|-------|----------|------------|-------|';
                foreach ($r['formFields'] as $f) {
                    $label = $f['label'] ?? '—';
                    $req = $f['required'] ? 'Yes' : '—';
                    $validation = [];
                    if ($f['maxLength']) {
                        $validation[] = "max:{$f['maxLength']}";
                    }
                    if ($f['email']) {
                        $validation[] = 'email';
                    }
                    if ($f['numeric']) {
                        $validation[] = 'numeric';
                    }
                    $valStr = !empty($validation) ? implode(', ', $validation) : '—';
                    $notes = [];
                    if ($f['relationship']) {
                        $notes[] = "relation: `{$f['relationship']}`";
                    }
                    if ($f['searchable']) {
                        $notes[] = 'searchable';
                    }
                    if ($f['helperText']) {
                        $notes[] = $f['helperText'];
                    }
                    $notesStr = !empty($notes) ? implode('; ', $notes) : '—';
                    $md[] = "| `{$f['name']}` | {$f['type']} | {$label} | {$req} | {$valStr} | {$notesStr} |";
                }
                $md[] = '';
            } else {
                $md[] = '#### Form Fields';
                $md[] = '';
                $md[] = '_No form fields defined (resource may use a custom form or wizard)._';
                $md[] = '';
            }

            // Table Columns
            if (!empty($r['tableColumns'])) {
                $md[] = '#### Table Columns';
                $md[] = '';
                $md[] = '| Column | Type | Label | Searchable | Sortable | Toggleable | Badge |';
                $md[] = '|--------|------|-------|------------|----------|------------|-------|';
                foreach ($r['tableColumns'] as $c) {
                    $label = $c['label'] ?? '—';
                    $search = $c['searchable'] ? 'Yes' : '—';
                    $sort = $c['sortable'] ? 'Yes' : '—';
                    $toggle = $c['toggleable'] ? 'Yes' : '—';
                    $badge = $c['badge'] ? 'Yes' : '—';
                    $md[] = "| `{$c['name']}` | {$c['type']} | {$label} | {$search} | {$sort} | {$toggle} | {$badge} |";
                }
                $md[] = '';
            }

            // Filters
            if (!empty($r['tableFilters'])) {
                $md[] = '#### Filters';
                $md[] = '';
                foreach ($r['tableFilters'] as $filter) {
                    $md[] = "- `{$filter}`";
                }
                $md[] = '';
            }

            // Actions
            if (!empty($r['tableActions'])) {
                $md[] = '#### Table Actions';
                $md[] = '';
                $md[] = implode(', ', array_map(fn($a) => "`{$a}`", $r['tableActions']));
                $md[] = '';
            }

            // Bulk Actions
            if (!empty($r['bulkActions'])) {
                $md[] = '#### Bulk Actions';
                $md[] = '';
                $md[] = implode(', ', array_map(fn($a) => "`{$a}`", $r['bulkActions']));
                $md[] = '';
            }

            // Relations
            if (!empty($r['relations'])) {
                $md[] = '#### Relation Managers';
                $md[] = '';
                foreach ($r['relations'] as $rel) {
                    $md[] = "- `{$rel}`";
                }
                $md[] = '';
            }

            // Widgets
            if (!empty($r['widgets'])) {
                $md[] = '#### Widgets';
                $md[] = '';
                foreach ($r['widgets'] as $w) {
                    $md[] = "- `{$w}`";
                }
                $md[] = '';
            }

            // Pages
            if (!empty($r['pages'])) {
                $md[] = '#### Pages';
                $md[] = '';
                $md[] = '| Key | Page Class | Route |';
                $md[] = '|-----|------------|-------|';
                foreach ($r['pages'] as $p) {
                    $md[] = "| `{$p['key']}` | `{$p['class']}` | `{$p['route']}` |";
                }
                $md[] = '';
            }

            // Global Search
            if ($r['globalSearch'] !== null) {
                $md[] = '#### Global Search';
                $md[] = '';
                $md[] = 'Searchable attributes: ' . implode(', ', array_map(fn($a) => "`{$a}`", $r['globalSearch']));
                $md[] = '';
            }
        }
    }

    // Navigation Map
    $md[] = '---';
    $md[] = '';
    $md[] = '## Navigation Map';
    $md[] = '';
    $md[] = 'Resources organized by their admin panel navigation group:';
    $md[] = '';

    $navGroups = [];
    foreach ($resources as $r) {
        $group = $r['navigationGroup'] ?? 'Ungrouped';
        $navGroups[$group][] = $r;
    }
    ksort($navGroups);

    foreach ($navGroups as $group => $items) {
        usort($items, fn($a, $b) => ((int)($a['navigationSort'] ?? 999)) <=> ((int)($b['navigationSort'] ?? 999)));
        $md[] = "### {$group}";
        $md[] = '';
        foreach ($items as $r) {
            $icon = $r['navigationIcon'] ?? '';
            $sort = $r['navigationSort'] ?? '—';
            $hidden = $r['shouldRegisterNavigation'] === false ? ' _(hidden)_' : '';
            $badge = $r['hasNavigationBadge'] ? ' [badge]' : '';
            $md[] = "- **{$r['class']}** — `{$r['model']}` (sort: {$sort}){$hidden}{$badge}";
        }
        $md[] = '';
    }

    // Appendix: Regeneration
    $md[] = '---';
    $md[] = '';
    $md[] = '## Regenerating This Document';
    $md[] = '';
    $md[] = 'Run the generator script from the project root:';
    $md[] = '';
    $md[] = '```bash';
    $md[] = 'php dev/generate-filament-docs.php';
    $md[] = '```';
    $md[] = '';
    $md[] = 'To output to a custom path:';
    $md[] = '';
    $md[] = '```bash';
    $md[] = 'php dev/generate-filament-docs.php --output=docs/api/my-resources.md';
    $md[] = '```';
    $md[] = '';

    return implode("\n", $md);
}

// ─── Main ─────────────────────────────────────────────────────────────────────

echo "Scanning for Filament Resources...\n";

$files = findResourceFiles($projectRoot);
echo "Found " . count($files) . " resource files.\n";

$resources = [];
foreach ($files as $file) {
    echo "  Parsing: " . str_replace($projectRoot . '/', '', $file) . "\n";
    $resources[] = parseResourceFile($file, $projectRoot);
}

echo "Generating documentation...\n";
$markdown = generateMarkdown($resources, $projectRoot);

$outputDir = dirname($outputFile);
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

file_put_contents($outputFile, $markdown);
echo "Documentation written to: " . str_replace($projectRoot . '/', '', $outputFile) . "\n";
echo "Total resources documented: " . count($resources) . "\n";
