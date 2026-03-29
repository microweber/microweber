<?php

namespace Modules\Newsletter\Tests;

use Tests\TestCase;
use App\Models\User;

abstract class NewsletterTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clean up newsletter-related tables before each test for isolation
        \Modules\Newsletter\Models\WorkflowExecution::query()->delete();
        \Modules\Newsletter\Models\WorkflowNode::query()->delete();
        \Modules\Newsletter\Models\Workflow::query()->delete();
    }
}
