<?php

namespace Modules\Newsletter\Tests\Unit\Workflow;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Newsletter\Models\Workflow;
use Modules\Newsletter\Models\WorkflowNode;
use Modules\Newsletter\Tests\NewsletterTestCase;

class WorkflowModelTest extends NewsletterTestCase
{
    use RefreshDatabase;

    public function test_can_create_workflow(): void
    {
        $workflow = Workflow::create([
            'name' => 'Test Workflow',
            'description' => 'Test description',
            'trigger_type' => Workflow::TRIGGER_TYPE_EVENT,
            'trigger_event' => Workflow::TRIGGER_CART_ABANDONED,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('workflows', [
            'name' => 'Test Workflow',
            'trigger_type' => 'event',
            'trigger_event' => 'cart_abandoned',
        ]);

        $this->assertTrue($workflow->is_active);
    }

    public function test_can_create_trigger_node(): void
    {
        $workflow = Workflow::factory()->create();

        $node = WorkflowNode::create([
            'workflow_id' => $workflow->id,
            'node_id' => 'node_' . uniqid(),
            'node_type' => WorkflowNode::TYPE_TRIGGER,
            'node_key' => 'cart_abandoned',
            'name' => 'Cart Abandoned Trigger',
            'config' => [],
            'position_x' => 100,
            'position_y' => 100,
        ]);

        $this->assertDatabaseHas('workflow_nodes', [
            'workflow_id' => $workflow->id,
            'node_type' => 'trigger',
            'node_key' => 'cart_abandoned',
        ]);

        $this->assertEquals(100, $node->position_x);
    }

    public function test_can_evaluate_trigger_conditions(): void
    {
        $workflow = Workflow::factory()->create([
            'trigger_conditions' => [
                ['field' => 'cart_total', 'operator' => 'greater_than', 'value' => 50],
            ],
        ]);

        // Should trigger
        $this->assertTrue($workflow->shouldTrigger(['cart_total' => 100]));

        // Should not trigger
        $this->assertFalse($workflow->shouldTrigger(['cart_total' => 30]));
    }

    public function test_can_evaluate_multiple_conditions(): void
    {
        $workflow = Workflow::factory()->create([
            'trigger_conditions' => [
                ['field' => 'cart_total', 'operator' => 'greater_than', 'value' => 50],
                ['field' => 'email', 'operator' => 'not_empty', 'value' => null],
            ],
        ]);

        $this->assertTrue($workflow->shouldTrigger(['cart_total' => 100, 'email' => 'test@example.com']));
        $this->assertFalse($workflow->shouldTrigger(['cart_total' => 30, 'email' => 'test@example.com']));
    }

    public function test_get_trigger_types_returns_array(): void
    {
        $types = Workflow::getTriggerTypes();

        $this->assertIsArray($types);
        $this->assertArrayHasKey('event', $types);
        $this->assertArrayHasKey('schedule', $types);
        $this->assertArrayHasKey('manual', $types);
    }

    public function test_get_trigger_events_returns_array(): void
    {
        $events = Workflow::getTriggerEvents();

        $this->assertIsArray($events);
        $this->assertArrayHasKey('cart_abandoned', $events);
        $this->assertArrayHasKey('order_placed', $events);
        $this->assertArrayHasKey('user_registered', $events);
    }

    public function test_can_increment_execution_stats(): void
    {
        $workflow = Workflow::factory()->create([
            'execution_count' => 0,
            'success_count' => 0,
            'failure_count' => 0,
        ]);

        $workflow->incrementExecution(true);

        $this->assertEquals(1, $workflow->execution_count);
        $this->assertEquals(1, $workflow->success_count);
        $this->assertEquals(0, $workflow->failure_count);
        $this->assertNotNull($workflow->last_triggered_at);

        $workflow->incrementExecution(false);

        $this->assertEquals(2, $workflow->execution_count);
        $this->assertEquals(1, $workflow->success_count);
        $this->assertEquals(1, $workflow->failure_count);
    }

    public function test_can_get_active_workflows_by_trigger(): void
    {
        Workflow::factory()->create([
            'trigger_type' => Workflow::TRIGGER_TYPE_EVENT,
            'trigger_event' => Workflow::TRIGGER_CART_ABANDONED,
            'is_active' => true,
        ]);

        Workflow::factory()->create([
            'trigger_type' => Workflow::TRIGGER_TYPE_EVENT,
            'trigger_event' => Workflow::TRIGGER_ORDER_PLACED,
            'is_active' => true,
        ]);

        Workflow::factory()->create([
            'trigger_type' => Workflow::TRIGGER_TYPE_EVENT,
            'trigger_event' => Workflow::TRIGGER_CART_ABANDONED,
            'is_active' => false, // Inactive
        ]);

        $workflows = Workflow::getActiveByTrigger(Workflow::TRIGGER_CART_ABANDONED);

        $this->assertCount(1, $workflows);
        $this->assertEquals(Workflow::TRIGGER_CART_ABANDONED, $workflows->first()->trigger_event);
    }
}
