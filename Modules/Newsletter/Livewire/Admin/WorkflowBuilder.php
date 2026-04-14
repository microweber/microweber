<?php

namespace Modules\Newsletter\Livewire\Admin;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Component;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterTemplate;
use Modules\Newsletter\Models\Workflow;
use Modules\Newsletter\Models\WorkflowNode;

class WorkflowBuilder extends Component implements HasForms
{
    use InteractsWithForms;

    public Workflow $workflow;
    public array $nodes = [];
    public array $connections = [];
    public ?string $selectedNodeId = null;
    public bool $showNodePanel = false;
    public ?string $connectionTargetId = null;

    // Node configuration form
    public array $nodeConfig = [];

    protected $listeners = [
        'nodeAdded' => 'handleNodeAdded',
        'nodeUpdated' => 'handleNodeUpdated',
        'nodeDeleted' => 'handleNodeDeleted',
        'connectionAdded' => 'handleConnectionAdded',
        'connectionDeleted' => 'handleConnectionDeleted',
        'saveWorkflow' => 'saveWorkflow',
    ];

    public function mount(Workflow $workflow): void
    {
        $this->workflow = $workflow;
        $this->loadNodes();
    }

    public function loadNodes(): void
    {
        $this->workflow->load('nodes');

        $this->nodes = $this->workflow->nodes->map(function ($node) {
            return [
                'id' => $node->node_id,
                'type' => $node->node_type,
                'key' => $node->node_key,
                'name' => $node->name,
                'description' => $node->description,
                'config' => $node->config,
                'x' => $node->position_x,
                'y' => $node->position_y,
            ];
        })->toArray();

        // Build connections
        $this->connections = [];
        foreach ($this->workflow->nodes as $node) {
            if ($node->connections) {
                foreach ($node->connections as $conn) {
                    $this->connections[] = [
                        'id' => $conn['id'] ?? uniqid('conn_'),
                        'source' => $node->node_id,
                        'target' => $conn['target'],
                        'sourcePort' => $conn['sourcePort'] ?? 'default',
                    ];
                }
            }
        }
    }

    public function selectNode(string $nodeId): void
    {
        $this->selectedNodeId = $nodeId;
        $this->showNodePanel = true;
        $this->connectionTargetId = null;

        // Find the node
        $node = collect($this->nodes)->firstWhere('id', $nodeId);
        if ($node) {
            $this->nodeConfig = [
                'name' => $node['name'],
                'description' => $node['description'] ?? '',
                'config' => $node['config'] ?? [],
            ];
        }

        $this->dispatch('nodeSelected', nodeId: $nodeId);
    }

    public function closeNodePanel(): void
    {
        $this->showNodePanel = false;
        $this->selectedNodeId = null;
        $this->nodeConfig = [];
        $this->connectionTargetId = null;
    }

    public function addNode(string $type, string $key): void
    {
        $definition = $this->getNodeDefinition($type, $key);

        if (! $definition) {
            $this->notify('danger', 'Unable to add the selected node.');

            return;
        }

        $nodeIndex = count($this->nodes);
        $nodeId = 'node_' . Str::ulid();

        $this->handleNodeAdded([
            'id' => $nodeId,
            'type' => $type,
            'key' => $key,
            'name' => $definition['label'],
            'description' => $definition['description'],
            'x' => 80 + (($nodeIndex % 2) * 260),
            'y' => 80 + (intdiv($nodeIndex, 2) * 160),
        ]);

        $this->selectNode($nodeId);
    }

    public function deleteNode(string $nodeId): void
    {
        $this->handleNodeDeleted($nodeId);

        if ($this->selectedNodeId === $nodeId) {
            $this->closeNodePanel();
        }

        $this->notify('success', 'Node deleted successfully.');
    }

    public function createConnection(): void
    {
        if (! $this->selectedNodeId || ! $this->connectionTargetId) {
            $this->notify('danger', 'Select a source node and a target node first.');

            return;
        }

        if ($this->selectedNodeId === $this->connectionTargetId) {
            $this->notify('danger', 'A node cannot connect to itself.');

            return;
        }

        $existingConnection = collect($this->connections)->first(fn (array $connection): bool => $connection['source'] === $this->selectedNodeId
            && $connection['target'] === $this->connectionTargetId);

        if ($existingConnection) {
            $this->notify('warning', 'This connection already exists.');

            return;
        }

        $this->handleConnectionAdded([
            'id' => 'conn_' . Str::ulid(),
            'source' => $this->selectedNodeId,
            'target' => $this->connectionTargetId,
            'sourcePort' => 'default',
        ]);

        $this->connectionTargetId = null;
        $this->notify('success', 'Connection added successfully.');
    }

    public function removeConnection(string $connectionId): void
    {
        $this->handleConnectionDeleted($connectionId);

        $this->notify('success', 'Connection removed successfully.');
    }

    public function handleNodeAdded(array $data): void
    {
        WorkflowNode::create([
            'workflow_id' => $this->workflow->id,
            'node_id' => $data['id'],
            'node_type' => $data['type'],
            'node_key' => $data['key'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'config' => $data['config'] ?? [],
            'position_x' => $data['x'] ?? 0,
            'position_y' => $data['y'] ?? 0,
            'sort_order' => $this->workflow->nodes()->count(),
        ]);

        $this->nodes[] = [
            'id' => $data['id'],
            'type' => $data['type'],
            'key' => $data['key'],
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'config' => $data['config'] ?? [],
            'x' => $data['x'] ?? 0,
            'y' => $data['y'] ?? 0,
        ];

        $this->notify('success', 'Node added successfully.');
    }

    public function handleNodeUpdated(array $data): void
    {
        $node = WorkflowNode::where('node_id', $data['id'])
            ->where('workflow_id', $this->workflow->id)
            ->first();

        if ($node) {
            $node->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'config' => $data['config'] ?? [],
                'position_x' => $data['x'] ?? $node->position_x,
                'position_y' => $data['y'] ?? $node->position_y,
            ]);
        }

        // Update local nodes array
        $nodeIndex = collect($this->nodes)->search(fn ($n) => $n['id'] === $data['id']);
        if ($nodeIndex !== false) {
            $this->nodes[$nodeIndex] = array_merge($this->nodes[$nodeIndex], $data);
        }
    }

    public function handleNodeDeleted(string $nodeId): void
    {
        foreach ($this->workflow->nodes()->where('node_id', '!=', $nodeId)->get() as $workflowNode) {
            if (! $workflowNode->connections) {
                continue;
            }

            $connections = collect($workflowNode->connections)
                ->reject(fn (array $connection): bool => ($connection['target'] ?? null) === $nodeId)
                ->values()
                ->toArray();

            if (count($connections) !== count($workflowNode->connections)) {
                $workflowNode->update([
                    'connections' => $connections,
                ]);
            }
        }

        WorkflowNode::where('node_id', $nodeId)
            ->where('workflow_id', $this->workflow->id)
            ->delete();

        $this->nodes = collect($this->nodes)
            ->reject(fn ($n) => $n['id'] === $nodeId)
            ->values()
            ->toArray();

        // Remove related connections
        $this->connections = collect($this->connections)
            ->reject(fn ($c) => $c['source'] === $nodeId || $c['target'] === $nodeId)
            ->values()
            ->toArray();
    }

    public function handleConnectionAdded(array $data): void
    {
        $sourceNode = WorkflowNode::where('node_id', $data['source'])
            ->where('workflow_id', $this->workflow->id)
            ->first();

        if ($sourceNode) {
            $connections = $sourceNode->connections ?? [];
            $connections[] = [
                'id' => $data['id'],
                'target' => $data['target'],
                'sourcePort' => $data['sourcePort'] ?? 'default',
            ];
            $sourceNode->connections = $connections;
            $sourceNode->save();
        }

        $this->connections[] = [
            'id' => $data['id'],
            'source' => $data['source'],
            'target' => $data['target'],
            'sourcePort' => $data['sourcePort'] ?? 'default',
        ];
    }

    public function handleConnectionDeleted(string $connectionId): void
    {
        // Find and remove the connection from nodes
        foreach ($this->workflow->nodes()->get() as $node) {
            if ($node->connections) {
                $connections = collect($node->connections)
                    ->reject(fn ($c) => ($c['id'] ?? '') === $connectionId)
                    ->values()
                    ->toArray();
                
                if (count($connections) !== count($node->connections)) {
                    $node->connections = $connections;
                    $node->save();
                    break;
                }
            }
        }

        $this->connections = collect($this->connections)
            ->reject(fn ($c) => $c['id'] === $connectionId)
            ->values()
            ->toArray();
    }

    public function saveWorkflow(): void
    {
        // Reload all nodes to ensure positions are saved
        foreach ($this->nodes as $nodeData) {
            WorkflowNode::where('node_id', $nodeData['id'])
                ->where('workflow_id', $this->workflow->id)
                ->update([
                    'position_x' => $nodeData['x'] ?? 0,
                    'position_y' => $nodeData['y'] ?? 0,
                ]);
        }

        $this->notify('success', 'Workflow saved successfully.');
    }

    public function updateNodeConfig(): void
    {
        if (! $this->selectedNodeId) {
            $this->notify('danger', 'Select a node before updating its configuration.');

            return;
        }

        $node = WorkflowNode::where('node_id', $this->selectedNodeId)
            ->where('workflow_id', $this->workflow->id)
            ->first();

        if ($node) {
            $node->update([
                'name' => $this->nodeConfig['name'] ?? $node->name,
                'description' => $this->nodeConfig['description'] ?? null,
                'config' => array_merge($node->config ?? [], $this->nodeConfig['config'] ?? []),
            ]);

            // Update local array
            $nodeIndex = collect($this->nodes)->search(fn ($n) => $n['id'] === $this->selectedNodeId);
            if ($nodeIndex !== false) {
                $this->nodes[$nodeIndex]['name'] = $this->nodeConfig['name'] ?? $this->nodes[$nodeIndex]['name'];
                $this->nodes[$nodeIndex]['config'] = array_merge(
                    $this->nodes[$nodeIndex]['config'] ?? [],
                    $this->nodeConfig['config'] ?? []
                );
            }
        }

        $this->notify('success', 'Node configuration updated.');
    }

    public function getAvailableNodeTypesProperty(): array
    {
        return [
            'trigger' => [
                'label' => 'Trigger',
                'icon' => 'heroicon-o-bolt',
                'color' => 'warning',
                'nodes' => [
                    ['key' => 'cart_abandoned', 'label' => 'Cart Abandoned', 'icon' => 'heroicon-o-shopping-cart'],
                    ['key' => 'order_placed', 'label' => 'Order Placed', 'icon' => 'heroicon-o-clipboard-document-check'],
                    ['key' => 'order_paid', 'label' => 'Order Paid', 'icon' => 'heroicon-o-credit-card'],
                    ['key' => 'user_registered', 'label' => 'User Registered', 'icon' => 'heroicon-o-user-plus'],
                    ['key' => 'user_subscribed', 'label' => 'User Subscribed', 'icon' => 'heroicon-o-envelope'],
                ],
            ],
            'condition' => [
                'label' => 'Condition',
                'icon' => 'heroicon-o-arrows-right-left',
                'color' => 'info',
                'nodes' => [
                    ['key' => 'field_equals', 'label' => 'Field Equals', 'icon' => 'heroicon-o-equals'],
                    ['key' => 'field_contains', 'label' => 'Field Contains', 'icon' => 'heroicon-o-magnifying-glass'],
                    ['key' => 'is_in_list', 'label' => 'Is in List', 'icon' => 'heroicon-o-list-bullet'],
                    ['key' => 'has_tag', 'label' => 'Has Tag', 'icon' => 'heroicon-o-tag'],
                ],
            ],
            'action' => [
                'label' => 'Action',
                'icon' => 'heroicon-o-play',
                'color' => 'success',
                'nodes' => [
                    ['key' => 'send_email', 'label' => 'Send Email', 'icon' => 'heroicon-o-envelope'],
                    ['key' => 'add_to_list', 'label' => 'Add to List', 'icon' => 'heroicon-o-plus-circle'],
                    ['key' => 'remove_from_list', 'label' => 'Remove from List', 'icon' => 'heroicon-o-minus-circle'],
                    ['key' => 'apply_tag', 'label' => 'Apply Tag', 'icon' => 'heroicon-o-tag'],
                    ['key' => 'update_contact', 'label' => 'Update Contact', 'icon' => 'heroicon-o-user'],
                    ['key' => 'webhook', 'label' => 'Webhook', 'icon' => 'heroicon-o-globe-alt'],
                ],
            ],
            'delay' => [
                'label' => 'Delay',
                'icon' => 'heroicon-o-clock',
                'color' => 'gray',
                'nodes' => [
                    ['key' => 'wait', 'label' => 'Wait', 'icon' => 'heroicon-o-clock'],
                    ['key' => 'wait_until', 'label' => 'Wait Until', 'icon' => 'heroicon-o-calendar'],
                ],
            ],
            'end' => [
                'label' => 'End',
                'icon' => 'heroicon-o-stop',
                'color' => 'danger',
                'nodes' => [
                    ['key' => 'end', 'label' => 'End', 'icon' => 'heroicon-o-check-circle'],
                ],
            ],
        ];
    }

    public function getNodeConfigFormSchema(): array
    {
        $node = collect($this->nodes)->firstWhere('id', $this->selectedNodeId);
        
        if (!$node) {
            return [];
        }

        $baseSchema = [
            TextInput::make('nodeConfig.name')
                ->label('Node Name')
                ->required(),
            
            Textarea::make('nodeConfig.description')
                ->label('Description')
                ->rows(2),
        ];

        // Add type-specific fields
        $typeSpecificSchema = $this->getNodeTypeSpecificSchema($node);

        return array_merge($baseSchema, $typeSpecificSchema);
    }

    protected function getNodeTypeSpecificSchema(array $node): array
    {
        $schema = [];

        switch ($node['key']) {
            case 'send_email':
                $schema = [
                    Select::make('nodeConfig.config.campaign_id')
                        ->label('Campaign')
                        ->options(fn () => NewsletterCampaign::pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('Select a campaign'),
                    
                    Select::make('nodeConfig.config.template_id')
                        ->label('Template')
                        ->options(fn () => NewsletterTemplate::pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('Select a template'),
                ];
                break;

            case 'add_to_list':
            case 'remove_from_list':
                $schema = [
                    Select::make('nodeConfig.config.list_id')
                        ->label('List')
                        ->options(fn () => NewsletterList::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ];
                break;

            case 'apply_tag':
                $schema = [
                    Select::make('nodeConfig.config.tag_id')
                        ->label('Tag')
                        ->options(fn () => \Modules\Tag\Models\Tag::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ];
                break;

            case 'wait':
                $schema = [
                    Grid::make()
                        ->schema([
                            TextInput::make('nodeConfig.config.delay_minutes')
                                ->label('Delay (minutes)')
                                ->numeric()
                                ->default(60)
                                ->required(),
                        ]),
                ];
                break;

            case 'wait_until':
                $schema = [
                    TextInput::make('nodeConfig.config.until_date')
                        ->label('Until Date')
                        ->type('datetime-local')
                        ->required(),
                ];
                break;

            case 'field_equals':
            case 'field_contains':
                $schema = [
                    TextInput::make('nodeConfig.config.field')
                        ->label('Field Name')
                        ->placeholder('e.g., email, first_name')
                        ->required(),
                    
                    TextInput::make('nodeConfig.config.value')
                        ->label('Value')
                        ->required(),
                ];
                break;

            case 'is_in_list':
                $schema = [
                    Select::make('nodeConfig.config.list_id')
                        ->label('List')
                        ->options(fn () => NewsletterList::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ];
                break;

            case 'has_tag':
                $schema = [
                    Select::make('nodeConfig.config.tag_id')
                        ->label('Tag')
                        ->options(fn () => \Modules\Tag\Models\Tag::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                ];
                break;

            case 'webhook':
                $schema = [
                    TextInput::make('nodeConfig.config.url')
                        ->label('Webhook URL')
                        ->url()
                        ->required(),
                    
                    Select::make('nodeConfig.config.method')
                        ->label('Method')
                        ->options([
                            'POST' => 'POST',
                            'GET' => 'GET',
                            'PUT' => 'PUT',
                            'PATCH' => 'PATCH',
                        ])
                        ->default('POST'),
                ];
                break;

            case 'update_contact':
                $schema = [
                    Section::make('Field Updates')
                        ->schema([
                            TextInput::make('nodeConfig.config.updates.first_name')
                                ->label('First Name'),
                            TextInput::make('nodeConfig.config.updates.last_name')
                                ->label('Last Name'),
                            TextInput::make('nodeConfig.config.updates.phone')
                                ->label('Phone'),
                            TextInput::make('nodeConfig.config.updates.company')
                                ->label('Company'),
                        ])
                        ->columns(2),
                ];
                break;
        }

        // Add common options
        $schema[] = Section::make('Options')
            ->schema([
                Toggle::make('nodeConfig.config.continue_on_error')
                    ->label('Continue on Error')
                    ->default(false),
            ]);

        return $schema;
    }

    protected function getNodeDefinition(string $type, string $key): ?array
    {
        $typeDefinition = $this->availableNodeTypes[$type] ?? null;

        if (! $typeDefinition) {
            return null;
        }

        $nodeDefinition = collect($typeDefinition['nodes'] ?? [])
            ->first(fn (array $node): bool => $node['key'] === $key);

        if (! $nodeDefinition) {
            return null;
        }

        return [
            'label' => $nodeDefinition['label'],
            'description' => $nodeDefinition['description'] ?? null,
        ];
    }

    protected function notify(string $type, string $message): void
    {
        $this->dispatch('notify', [
            'type' => $type,
            'message' => $message,
        ]);
    }

    public function render()
    {
        return view('microweber-module-newsletter::livewire.admin.workflow-builder');
    }
}
