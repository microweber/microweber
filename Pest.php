<?php

/*
|--------------------------------------------------------------------------
| Pest Configuration
|--------------------------------------------------------------------------
|
| This file configures Pest testing framework for the Microweber project.
| It discovers tests from modules and runs them alongside PHPUnit tests.
|
*/

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

// Configure the root test case
uses(TestCase::class)->in('tests');

// Module test discovery - automatically include tests from Modules
uses(TestCase::class)
    ->in('Modules/*/Tests')
    ->in('Modules/*/tests');

// Package test discovery
uses(TestCase::class)
    ->in('src/MicroweberPackages/*/tests');

// Global before each hook
beforeEach(function () {
    // Set up any global test state here
})->in('Modules');

// Global expectations
expect()->extend('toBeJsonApi', function () {
    return $this->toBeInstanceOf('Illuminate\Testing\Fluent\AssertableJson');
});
