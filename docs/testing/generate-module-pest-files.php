#!/usr/bin/env php
<?php

/**
 * Generate Pest.php files for all modules
 *
 * This script generates Pest.php configuration files for all modules
 * that have tests but don't have a Pest.php file yet.
 */

$modulesDir = __DIR__ . '/../../Modules';
$modules = glob($modulesDir . '/*', GLOB_ONLYDIR);

$generated = 0;
$skipped = 0;

foreach ($modules as $modulePath) {
    $moduleName = basename($modulePath);

    // Determine test directory (Tests or tests)
    $testDir = null;
    $testDirName = null;
    if (is_dir($modulePath . '/Tests')) {
        $testDir = $modulePath . '/Tests';
        $testDirName = 'Tests';
    } elseif (is_dir($modulePath . '/tests')) {
        $testDir = $modulePath . '/tests';
        $testDirName = 'tests';
    } else {
        // No tests directory, skip
        $skipped++;
        continue;
    }

    // Check if Pest.php already exists
    $pestFile = $testDir . '/Pest.php';
    if (file_exists($pestFile)) {
        echo "✓ {$moduleName}: Pest.php already exists\n";
        continue;
    }

    // Create Pest.php
    $moduleNamespace = 'Modules\\' . $moduleName;
    $testCaseClass = 'Tests\\TestCase';

    // Check if module has its own TestCase
    $moduleTestCaseFile = $testDir . '/Unit/' . $moduleName . 'TestCase.php';
    if (file_exists($moduleTestCaseFile)) {
        $testCaseClass = $moduleNamespace . '\\Tests\\Unit\\' . $moduleName . 'TestCase';
    }

    // Check for lowercase test case
    $moduleTestCaseFileLower = $testDir . '/unit/' . $moduleName . 'TestCase.php';
    if (file_exists($moduleTestCaseFileLower)) {
        $testCaseClass = $moduleNamespace . '\\Tests\\Unit\\' . $moduleName . 'TestCase';
    }

    $pestContent = <<<PHP
<?php

use {$testCaseClass};

// Use the base TestCase for all tests in this module
uses({$testCaseClass}::class)->in(__DIR__);

// Uncomment to use RefreshDatabase for Feature tests
// use Illuminate\\Foundation\\Testing\\RefreshDatabase;
// uses(RefreshDatabase::class)->in('Feature');

beforeEach(function () {
    // {$moduleName} module-specific setup
})->in(__DIR__);

afterEach(function () {
    // {$moduleName} module-specific cleanup
})->in(__DIR__);
PHP;

    file_put_contents($pestFile, $pestContent);
    echo "✓ {$moduleName}: Created Pest.php\n";
    $generated++;
}

echo "\n";
echo "Generated Pest.php files: {$generated}\n";
echo "Skipped (no tests or already exists): {$skipped}\n";
echo "Total modules processed: " . count($modules) . "\n";
