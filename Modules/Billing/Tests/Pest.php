<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Billing\Tests\Unit\BillingTestCase;

// Use the module's test case for all tests in this directory
uses(BillingTestCase::class)->in(__DIR__);

// Use RefreshDatabase for Feature tests only
uses(RefreshDatabase::class)->in('Feature');

beforeEach(function () {
    // Billing module-specific setup
})->in(__DIR__);
