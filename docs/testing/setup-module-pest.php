#!/usr/bin/env php
<?php

/**
 * Setup Module Pest Test Suite
 *
 * This script sets up Pest testing structure for a module.
 * Usage: php docs/testing/setup-module-pest.php ModuleName
 */

if ($argc < 2) {
    echo "Usage: php docs/testing/setup-module-pest.php <ModuleName>\n";
    echo "Example: php docs/testing/setup-module-pest.php Blog\n";
    exit(1);
}

$moduleName = $argv[1];
$modulePath = __DIR__ . '/../../Modules/' . $moduleName;

if (!is_dir($modulePath)) {
    echo "Error: Module '{$moduleName}' not found at {$modulePath}\n";
    exit(1);
}

// Determine test directory (Tests or tests)
$testDir = null;
if (is_dir($modulePath . '/Tests')) {
    $testDir = $modulePath . '/Tests';
} elseif (is_dir($modulePath . '/tests')) {
    $testDir = $modulePath . '/tests';
} else {
    // Create tests directory
    $testDir = $modulePath . '/tests';
    echo "Creating test directory: {$testDir}\n";
    mkdir($testDir, 0755, true);
}

// Create Unit and Feature directories
$unitDir = $testDir . '/Unit';
$featureDir = $testDir . '/Feature';

if (!is_dir($unitDir)) {
    echo "Creating Unit directory: {$unitDir}\n";
    mkdir($unitDir, 0755, true);
}

if (!is_dir($featureDir)) {
    echo "Creating Feature directory: {$featureDir}\n";
    mkdir($featureDir, 0755, true);
}

// Create Pest.php if it doesn't exist
$pestFile = $testDir . '/Pest.php';
if (!file_exists($pestFile)) {
    echo "Creating Pest.php: {$pestFile}\n";

    $moduleNamespace = 'Modules\\' . $moduleName;
    $testCaseClass = 'Tests\\TestCase';

    // Check if module has its own TestCase
    $moduleTestCaseFile = $testDir . '/Unit/' . $moduleName . 'TestCase.php';
    if (file_exists($moduleTestCaseFile)) {
        $testCaseClass = $moduleNamespace . '\\Tests\\Unit\\' . $moduleName . 'TestCase';
    }

    $pestContent = <<<PHP
<?php

use {$testCaseClass};

// Use the base TestCase for all tests in this module
uses({$testCaseClass}::class)->in(__DIR__);

// Uncomment to use RefreshDatabase for Feature tests
// use Illuminate\Foundation\Testing\RefreshDatabase;
// uses(RefreshDatabase::class)->in('Feature');

beforeEach(function () {
    // {$moduleName} module-specific setup
})->in(__DIR__);

afterEach(function () {
    // {$moduleName} module-specific cleanup
})->in(__DIR__);
PHP;

    file_put_contents($pestFile, $pestContent);
}

// Create example Pest test if no Pest tests exist
$pestTests = glob($testDir . '/*/*.php');
$hasPestTest = false;
foreach ($pestTests as $test) {
    $content = file_get_contents($test);
    if (strpos($content, 'test(') !== false || strpos($content, 'it(') !== false) {
        $hasPestTest = true;
        break;
    }
}

if (!$hasPestTest) {
    $exampleFile = $unitDir . '/ExamplePestTest.php';
    echo "Creating example Pest test: {$exampleFile}\n";

    $exampleContent = <<<PHP
<?php

use {$moduleNamespace}\Models\{$moduleName}Model;

test('can instantiate {$moduleName} model', function () {
    \$model = {$moduleName}Model::factory()->make();

    expect(\$model)->toBeInstanceOf({$moduleName}Model::class);
});

test('basic arithmetic works', function () {
    expect(2 + 2)->toBe(4);
});

// Uncomment and modify for your module
// test('can perform module action', function () {
//     \$result = performSomeAction();
//
//     expect(\$result)->toBeTrue();
// });
PHP;

    file_put_contents($exampleFile, $exampleContent);
}

echo "\n✅ Pest test suite setup complete for module: {$moduleName}\n";
echo "\nNext steps:\n";
echo "1. Run: composer install --dev (if not already installed)\n";
echo "2. Run tests: ./vendor/bin/pest Modules/{$moduleName}/tests\n";
echo "3. Read the guide: docs/testing/module-testing-guide.md\n";
