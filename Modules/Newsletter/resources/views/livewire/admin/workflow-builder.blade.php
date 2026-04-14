<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
    <div class="border-b border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-200">
        The drag-and-drop canvas JavaScript is not wired into this page yet, so this builder uses a server-rendered workflow editor fallback.
    </div>

    <div class="grid gap-0 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="bg-gray-50/80 dark:bg-gray-950/50">
            <div class="border-b border-gray-200 px-4 py-4 dark:border-gray-700">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Workflow steps</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Add nodes from the palette, select a step to edit it, and connect steps from the configuration panel.
                        </p>
                    </div>

                    <button
                        type="button"
                        wire:click="saveWorkflow"
                        class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"
                    >
                        Save workflow
                    </button>
                </div>
            </div>

            <div class="p-4">
                @if (count($nodes))
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3" data-testid="workflow-builder-node-list">
                        @foreach ($nodes as $node)
                            @php
                                $outgoingConnections = collect($connections)->filter(fn (array $connection) => $connection['source'] === $node['id']);
                            @endphp

                            <button
                                type="button"
                                wire:key="workflow-node-{{ $node['id'] }}"
                                wire:click="selectNode('{{ $node['id'] }}')"
                                class="rounded-xl border p-4 text-left transition {{ $selectedNodeId === $node['id'] ? 'border-indigo-500 bg-indigo-50 shadow-sm dark:border-indigo-400 dark:bg-indigo-950/40' : 'border-gray-200 bg-white hover:border-indigo-300 hover:shadow-sm dark:border-gray-700 dark:bg-gray-900' }}"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            {{ str_replace('_', ' ', $node['type']) }}
                                        </div>
                                        <div class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                            {{ $node['name'] }}
                                        </div>
                                    </div>

                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                                        {{ str_replace('_', ' ', $node['key']) }}
                                    </span>
                                </div>

                                @if (! empty($node['description']))
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $node['description'] }}
                                    </p>
                                @endif

                                <div class="mt-4 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>Position: {{ $node['x'] }}, {{ $node['y'] }}</span>
                                    <span>{{ $outgoingConnections->count() }} connection{{ $outgoingConnections->count() === 1 ? '' : 's' }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="flex min-h-[360px] items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center dark:border-gray-700 dark:bg-gray-900">
                        <div>
                            <x-heroicon-o-rectangle-stack class="mx-auto mb-4 h-12 w-12 text-gray-400" />
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No workflow steps yet</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Start by adding a trigger from the palette, then add conditions and actions to shape the automation flow.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="border-t border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900 lg:border-l lg:border-t-0">
            @if ($showNodePanel && $selectedNodeId)
                @php
                    $selectedNode = collect($nodes)->firstWhere('id', $selectedNodeId);
                    $selectedConnections = collect($connections)->filter(fn (array $connection) => $connection['source'] === $selectedNodeId);
                @endphp

                <div class="p-5">
                    <div class="mb-5 flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Node configuration</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Update the selected node and connect it to the next step.
                            </p>
                        </div>

                        <button type="button" wire:click="closeNodePanel" class="text-gray-400 transition hover:text-gray-600 dark:hover:text-gray-200">
                            <x-heroicon-o-x-mark class="h-5 w-5" />
                        </button>
                    </div>

                    <form wire:submit="updateNodeConfig" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Node name</label>
                            <input
                                type="text"
                                wire:model="nodeConfig.name"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea
                                wire:model="nodeConfig.description"
                                rows="2"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            ></textarea>
                        </div>

                        @if ($selectedNode && $selectedNode['key'] === 'send_email')
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Campaign ID</label>
                                    <input
                                        type="number"
                                        wire:model="nodeConfig.config.campaign_id"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Template ID</label>
                                    <input
                                        type="number"
                                        wire:model="nodeConfig.config.template_id"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    >
                                </div>
                            </div>
                        @endif

                        @if ($selectedNode && in_array($selectedNode['key'], ['add_to_list', 'remove_from_list', 'is_in_list'], true))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">List ID</label>
                                <input
                                    type="number"
                                    wire:model="nodeConfig.config.list_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                >
                            </div>
                        @endif

                        @if ($selectedNode && in_array($selectedNode['key'], ['apply_tag', 'has_tag'], true))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tag ID</label>
                                <input
                                    type="number"
                                    wire:model="nodeConfig.config.tag_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                >
                            </div>
                        @endif

                        @if ($selectedNode && $selectedNode['key'] === 'wait')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delay (minutes)</label>
                                <input
                                    type="number"
                                    min="1"
                                    wire:model="nodeConfig.config.delay_minutes"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                >
                            </div>
                        @endif

                        @if ($selectedNode && $selectedNode['key'] === 'wait_until')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Until date</label>
                                <input
                                    type="datetime-local"
                                    wire:model="nodeConfig.config.until_date"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                >
                            </div>
                        @endif

                        @if ($selectedNode && in_array($selectedNode['key'], ['field_equals', 'field_contains'], true))
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Field</label>
                                    <input
                                        type="text"
                                        wire:model="nodeConfig.config.field"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Value</label>
                                    <input
                                        type="text"
                                        wire:model="nodeConfig.config.value"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    >
                                </div>
                            </div>
                        @endif

                        @if ($selectedNode && $selectedNode['key'] === 'webhook')
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Webhook URL</label>
                                    <input
                                        type="url"
                                        wire:model="nodeConfig.config.url"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Method</label>
                                    <select
                                        wire:model="nodeConfig.config.method"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                    >
                                        <option value="POST">POST</option>
                                        <option value="GET">GET</option>
                                        <option value="PUT">PUT</option>
                                        <option value="PATCH">PATCH</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-3">
                            <button
                                type="submit"
                                class="flex-1 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700"
                            >
                                Save changes
                            </button>

                            <button
                                type="button"
                                wire:click="deleteNode('{{ $selectedNodeId }}')"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                            >
                                Delete
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 space-y-4 border-t border-gray-200 pt-6 dark:border-gray-700">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Outgoing connections</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Connect this node to the next step in the automation path.
                            </p>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Connect to</label>
                                <select
                                    wire:model="connectionTargetId"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                >
                                    <option value="">Select a target node</option>
                                    @foreach ($nodes as $node)
                                        @if ($node['id'] !== $selectedNodeId)
                                            <option value="{{ $node['id'] }}">{{ $node['name'] }} ({{ str_replace('_', ' ', $node['type']) }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <button
                                type="button"
                                wire:click="createConnection"
                                class="w-full rounded-lg border border-indigo-200 px-4 py-2 text-sm font-medium text-indigo-700 transition hover:bg-indigo-50 dark:border-indigo-800 dark:text-indigo-300 dark:hover:bg-indigo-950/40"
                            >
                                Add connection
                            </button>
                        </div>

                        <div class="space-y-2">
                            @forelse ($selectedConnections as $connection)
                                @php
                                    $targetNode = collect($nodes)->firstWhere('id', $connection['target']);
                                @endphp

                                <div class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 text-sm dark:border-gray-700">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $targetNode['name'] ?? $connection['target'] }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $targetNode ? str_replace('_', ' ', $targetNode['type']) : 'Unknown node' }}
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        wire:click="removeConnection('{{ $connection['id'] }}')"
                                        class="text-sm font-medium text-red-600 transition hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                    >
                                        Remove
                                    </button>
                                </div>
                            @empty
                                <div class="rounded-lg border border-dashed border-gray-300 px-3 py-4 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                    No outgoing connections yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <div class="flex h-full flex-col">
                    <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-900 dark:text-white">Node palette</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Click any node below to add it to the workflow.
                        </p>
                    </div>

                    <div class="flex-1 space-y-5 overflow-y-auto p-5">
                        @foreach ($this->availableNodeTypes as $type => $typeDefinition)
                            <div>
                                <h4 class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    {{ $typeDefinition['label'] }}
                                </h4>

                                <div class="space-y-2">
                                    @foreach ($typeDefinition['nodes'] as $nodeDefinition)
                                        <button
                                            type="button"
                                            wire:key="workflow-palette-{{ $type }}-{{ $nodeDefinition['key'] }}"
                                            wire:click="addNode('{{ $type }}', '{{ $nodeDefinition['key'] }}')"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-left text-sm font-medium text-gray-700 transition hover:border-indigo-300 hover:bg-indigo-50 dark:border-gray-700 dark:text-gray-200 dark:hover:border-indigo-700 dark:hover:bg-indigo-950/40"
                                        >
                                            {{ $nodeDefinition['label'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
