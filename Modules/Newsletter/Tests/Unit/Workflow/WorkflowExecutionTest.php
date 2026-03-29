<?php

namespace Modules\Newsletter\Tests\Unit\Workflow;

use Modules\Newsletter\Models\Workflow;
use Modules\Newsletter\Models\WorkflowExecution;
use Modules\Newsletter\Tests\NewsletterTestCase;

class WorkflowExecutionTest extends NewsletterTestCase
{

    public function test_can_create_execution(): void
    {
        $workflow = Workflow::factory()->create();

        $execution = WorkflowExecution::create([
            'workflow_id' => $workflow->id,
            'execution_key' => 'test_' . uniqid(),
            'status' => WorkflowExecution::STATUS_PENDING,
            'trigger_source' => WorkflowExecution::SOURCE_EVENT,
            'trigger_data' => ['email' => 'test@example.com'],
        ]);

        $this->assertDatabaseHas('workflow_executions', [
            'workflow_id' => $workflow->id,
            'status' => 'pending',
        ]);

        $this->assertEquals(['email' => 'test@example.com'], $execution->trigger_data);
    }

    public function test_can_mark_execution_as_started(): void
    {
        $execution = WorkflowExecution::factory()->create([
            'status' => WorkflowExecution::STATUS_PENDING,
            'started_at' => null,
        ]);

        $execution->markAsStarted();

        $this->assertEquals(WorkflowExecution::STATUS_RUNNING, $execution->status);
        $this->assertNotNull($execution->started_at);
    }

    public function test_can_mark_execution_as_completed(): void
    {
        $execution = WorkflowExecution::factory()->create([
            'status' => WorkflowExecution::STATUS_RUNNING,
            'completed_at' => null,
            'total_steps' => 5,
        ]);

        $execution->markAsCompleted();

        $this->assertEquals(WorkflowExecution::STATUS_COMPLETED, $execution->status);
        $this->assertNotNull($execution->completed_at);
        $this->assertEquals(5, $execution->current_step);
    }

    public function test_can_mark_execution_as_failed(): void
    {
        $execution = WorkflowExecution::factory()->create([
            'status' => WorkflowExecution::STATUS_RUNNING,
        ]);

        $execution->markAsFailed('Test error message');

        $this->assertEquals(WorkflowExecution::STATUS_FAILED, $execution->status);
        $this->assertEquals('Test error message', $execution->error_message);
        $this->assertNotNull($execution->completed_at);
    }

    public function test_can_mark_execution_as_cancelled(): void
    {
        $execution = WorkflowExecution::factory()->create([
            'status' => WorkflowExecution::STATUS_PENDING,
        ]);

        $execution->markAsCancelled();

        $this->assertEquals(WorkflowExecution::STATUS_CANCELLED, $execution->status);
        $this->assertNotNull($execution->completed_at);
    }

    public function test_can_update_execution_progress(): void
    {
        $execution = WorkflowExecution::factory()->create([
            'current_step' => 0,
            'execution_log' => [],
        ]);

        $execution->updateProgress(1, 'Step 1 completed');

        $this->assertEquals(1, $execution->current_step);
        $this->assertNotNull($execution->execution_log);
    }

    public function test_can_check_execution_status(): void
    {
        $completed = WorkflowExecution::factory()->create([
            'status' => WorkflowExecution::STATUS_COMPLETED,
        ]);
        $running = WorkflowExecution::factory()->create([
            'status' => WorkflowExecution::STATUS_RUNNING,
        ]);
        $failed = WorkflowExecution::factory()->create([
            'status' => WorkflowExecution::STATUS_FAILED,
        ]);

        $this->assertTrue($completed->isCompleted());
        $this->assertFalse($completed->isRunning());
        $this->assertFalse($completed->hasFailed());

        $this->assertTrue($running->isRunning());
        $this->assertFalse($running->isCompleted());

        $this->assertTrue($failed->hasFailed());
        $this->assertFalse($failed->isCompleted());
    }

    public function test_can_get_execution_duration(): void
    {
        $execution = WorkflowExecution::factory()->create([
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
        ]);

        $this->assertEquals(300, $execution->getDuration()); // 5 minutes in seconds
    }

    public function test_get_statistics_returns_correct_data(): void
    {
        $workflow = Workflow::factory()->create();

        WorkflowExecution::factory()->count(3)->create([
            'workflow_id' => $workflow->id,
            'status' => WorkflowExecution::STATUS_COMPLETED,
        ]);

        WorkflowExecution::factory()->count(2)->create([
            'workflow_id' => $workflow->id,
            'status' => WorkflowExecution::STATUS_FAILED,
        ]);

        WorkflowExecution::factory()->create([
            'workflow_id' => $workflow->id,
            'status' => WorkflowExecution::STATUS_PENDING,
        ]);

        WorkflowExecution::factory()->create([
            'workflow_id' => $workflow->id,
            'status' => WorkflowExecution::STATUS_CANCELLED,
        ]);

        $stats = WorkflowExecution::getStatistics($workflow->id);

        $this->assertEquals(7, $stats['total']);
        $this->assertEquals(1, $stats['pending']);
        $this->assertEquals(3, $stats['completed']);
        $this->assertEquals(2, $stats['failed']);
        $this->assertEquals(1, $stats['cancelled']);
        $this->assertGreaterThan(0, $stats['success_rate']);
    }
}
