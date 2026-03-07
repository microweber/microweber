<?php

use Tests\TestCase;

// Use the base TestCase for all tests in this module
uses(Tests\TestCase::class)->in(__DIR__);

// Uncomment to use RefreshDatabase for Feature tests
// use Illuminate\Foundation\Testing\RefreshDatabase;
// uses(RefreshDatabase::class)->in('Feature');

beforeEach(function () {
    // Product module-specific setup
})->in(__DIR__);

afterEach(function () {
    // Product module-specific cleanup
})->in(__DIR__);