<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\Set\ValueObject\SetList;

/**
 * Main Rector configuration for Microweber.
 *
 * Usage:
 *   vendor/bin/rector process --dry-run
 *   vendor/bin/rector process --dry-run src/MicroweberPackages/Content
 *   vendor/bin/rector process --memory-limit=1G
 *
 * For Filament v5 migration, use rector-filament.php instead:
 *   vendor/bin/rector process --config=rector-filament.php
 */

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/src',
        __DIR__ . '/Modules',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        __DIR__ . '/vendor',
        __DIR__ . '/node_modules',
        __DIR__ . '/bootstrap/cache',
        __DIR__ . '/storage',
        __DIR__ . '/build',
        __DIR__ . '/packages/*/vendor',
        __DIR__ . '/packages/*/node_modules',
        __DIR__ . '/dev',
        // Skip non-PHP and generated files within processing paths
        '*/resources/views/*',
        '*/database/migrations/*',
        '*/database/seeders/*',
    ])
    ->withCache(__DIR__ . '/build/rector-cache')
    ->withParallel(
        timeoutSeconds: 300,
        maxNumberOfProcess: 4,
        jobSize: 20,
    )
    ->withPhpSets(php83: true)
    ->withSets([
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
    ])
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ]);
