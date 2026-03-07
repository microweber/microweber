<?php

/*
|--------------------------------------------------------------------------
| Module-Level Pest Configuration Template
|--------------------------------------------------------------------------
|
| Copy this file to your module's Tests directory as "Pest.php"
| and customize the namespace and configuration as needed.
|
| Example: Modules/YourModule/Tests/Pest.php
|
*/

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Use the base TestCase for all tests in this module
uses(TestCase::class)->in(__DIR__);

// Optional: Use RefreshDatabase for all tests
// uses(RefreshDatabase::class)->in('Feature');

// Optional: Configure module-specific before/after hooks
beforeEach(function () {
    // Module-specific setup
})->in(__DIR__);

afterEach(function () {
    // Module-specific teardown
})->in(__DIR__);
