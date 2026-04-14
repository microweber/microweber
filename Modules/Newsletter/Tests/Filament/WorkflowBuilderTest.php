<?php

namespace Modules\Newsletter\Tests\Filament;

use Filament\Facades\Filament;
use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\WorkflowResource;
use Modules\Newsletter\Livewire\Admin\WorkflowBuilder;
use Modules\Newsletter\Models\Workflow;
use Modules\Newsletter\Models\WorkflowNode;
use Modules\Newsletter\Tests\NewsletterTestCase;
use PHPUnit\Framework\Attributes\Test;

class WorkflowBuilderTest extends NewsletterTestCase
{
    #[Test]
    public function workflow_index_page_renders_registered_resource_navigation(): void
    {
        $this->loginAsAdmin();
        $workflow = Workflow::factory()->create([
            'name' => 'Welcome automation',
        ]);

        $response = $this->get(WorkflowResource::getUrl('index', [], false, 'admin-newsletter'));

        $response->assertSuccessful();
        $response->assertSee('Automation Workflows');
        $response->assertSee('Executions');
        $response->assertSee($workflow->name);
    }

    #[Test]
    public function workflow_edit_page_renders_the_builder(): void
    {
        $this->loginAsAdmin();
        $workflow = Workflow::factory()->create([
            'name' => 'Cart recovery flow',
        ]);

        $response = $this->get(WorkflowResource::getUrl('edit', [
            'record' => $workflow,
        ], false, 'admin-newsletter'));

        $response->assertSuccessful();
        $response->assertSee('Workflow builder');
        $response->assertSee('Node palette');
        $response->assertSee('Cart recovery flow');
    }

    #[Test]
    public function workflow_builder_can_add_connect_update_and_delete_nodes(): void
    {
        $this->loginAsAdmin();
        $workflow = Workflow::factory()->create();

        Filament::setCurrentPanel(
            Filament::getPanel('admin-newsletter'),
        );

        $component = Livewire::test(WorkflowBuilder::class, [
            'workflow' => $workflow,
        ]);

        $component->call('addNode', 'trigger', Workflow::TRIGGER_CART_ABANDONED)
            ->call('addNode', 'action', WorkflowNode::KEY_SEND_EMAIL);

        $nodes = $component->get('nodes');
        $triggerNodeState = collect($nodes)->first(fn (array $node): bool => $node['type'] === WorkflowNode::TYPE_TRIGGER);
        $actionNodeState = collect($nodes)->first(fn (array $node): bool => $node['key'] === WorkflowNode::KEY_SEND_EMAIL);

        $this->assertCount(2, $nodes);
        $this->assertNotNull($triggerNodeState);
        $this->assertNotNull($actionNodeState);
        $this->assertDatabaseCount('workflow_nodes', 2);

        $component->call('selectNode', $triggerNodeState['id'])
            ->set('connectionTargetId', $actionNodeState['id'])
            ->call('createConnection')
            ->set('nodeConfig.name', 'Recovered cart trigger')
            ->set('nodeConfig.description', 'Starts the recovery flow.')
            ->call('updateNodeConfig');

        $triggerNode = WorkflowNode::query()
            ->where('workflow_id', $workflow->id)
            ->where('node_id', $triggerNodeState['id'])
            ->firstOrFail();

        $this->assertSame('Recovered cart trigger', $triggerNode->name);
        $this->assertSame('Starts the recovery flow.', $triggerNode->description);
        $this->assertCount(1, $triggerNode->connections ?? []);
        $this->assertSame($actionNodeState['id'], $triggerNode->connections[0]['target'] ?? null);

        $component->call('deleteNode', $actionNodeState['id']);

        $this->assertDatabaseMissing('workflow_nodes', [
            'workflow_id' => $workflow->id,
            'node_id' => $actionNodeState['id'],
        ]);

        $triggerNode = WorkflowNode::query()
            ->where('workflow_id', $workflow->id)
            ->where('node_id', $triggerNodeState['id'])
            ->firstOrFail();

        $this->assertSame([], $triggerNode->connections ?? []);
    }
}
