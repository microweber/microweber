<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

// Load custom Rector rules
require_once __DIR__ . '/dev/rector-rules/Filament/Rector/RenameSectionImportRector.php';
require_once __DIR__ . '/dev/rector-rules/Filament/Rector/RenameTableActionImportRector.php';
require_once __DIR__ . '/dev/rector-rules/Filament/Rector/RenameTabsImportRector.php';
require_once __DIR__ . '/dev/rector-rules/Filament/Rector/RenameFormMethodSignatureRector.php';
require_once __DIR__ . '/dev/rector-rules/Filament/Rector/RenameSchemaMethodCallRector.php';
require_once __DIR__ . '/dev/rector-rules/Filament/Rector/ConvertTestAnnotationToAttributeRector.php';
require_once __DIR__ . '/dev/rector-rules/Filament/Rector/FixLivewireEventDispatchRector.php';

use Dev\Rector\Filament\Rector\RenameSectionImportRector;
use Dev\Rector\Filament\Rector\RenameTableActionImportRector;
use Dev\Rector\Filament\Rector\RenameTabsImportRector;
use Dev\Rector\Filament\Rector\RenameFormMethodSignatureRector;
use Dev\Rector\Filament\Rector\RenameSchemaMethodCallRector;
use Dev\Rector\Filament\Rector\ConvertTestAnnotationToAttributeRector;
use Dev\Rector\Filament\Rector\FixLivewireEventDispatchRector;

/**
 * Rector configuration for Filament v3 to v5 migration.
 *
 * This configuration file includes custom Rector rules specifically designed
 * for migrating Microweber modules from Filament v3 to Filament v5.
 *
 * Usage:
 *   vendor/bin/rector process --config=rector-filament.php --dry-run
 *   vendor/bin/rector process --config=rector-filament.php
 *   vendor/bin/rector process --config=rector-filament.php Modules/Billing
 */

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Modules',
        __DIR__ . '/src',
        __DIR__ . '/app',
    ])
    ->withSkip([
        __DIR__ . '/vendor',
        __DIR__ . '/node_modules',
        __DIR__ . '/bootstrap/cache',
        __DIR__ . '/storage',
        __DIR__ . '/build',
        __DIR__ . '/dev',
        // Skip non-Filament files for faster targeted processing
        '*/resources/views/*',
        '*/database/migrations/*',
        '*/database/seeders/*',
        '*/resources/dist/*',
        '*/public/build/*',
        '*/node_modules/*',
    ])
    ->withMemoryLimit('1G')
    ->withCache(__DIR__ . '/build/rector-cache')
    ->withParallel(
        timeoutSeconds: 600,
        maxNumberOfProcess: max(1, (int) (getenv('RECTOR_WORKERS') ?: min(4, (int) @shell_exec('nproc 2>/dev/null') ?: 2))),
        jobSize: 40,
    )
    ->withPhpSets(php83: true)
    ->withRules([
        // Filament v5 specific rules
        RenameSectionImportRector::class,
        RenameTableActionImportRector::class,
        RenameTabsImportRector::class,
        RenameFormMethodSignatureRector::class,
        RenameSchemaMethodCallRector::class,
        ConvertTestAnnotationToAttributeRector::class,
    ])
    ->withImportNames(importShortClasses: false, removeUnusedImports: true);
