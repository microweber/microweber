<div class="flex h-full">
    {{-- Workflow Canvas Area --}}
    <div class="flex-1 relative bg-gray-50 dark:bg-gray-900">
        <div id="workflow-canvas" class="w-full h-full relative overflow-auto">
            <div class="workflow-grid min-h-full min-w-full p-8" style="min-height: 600px;">
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <x-heroicon-o-rectangle-stack class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Visual Workflow Builder
                        </h3>
                        <p class="text-gray-500 mt-2">
                            Drag nodes from the sidebar to build your automation workflow
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar - Node Panel --}}
    @if($showNodePanel && $selectedNodeId)
    <div class="w-96 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Node Configuration
                </h3>
                <button wire:click="closeNodePanel" class="text-gray-400 hover:text-gray-600">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            </div>

            <form wire:submit="updateNodeConfig">
                {{-- Dynamic form fields will be rendered here --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Node Name
                        </label>
                        <input type="text" wire:model="nodeConfig.name" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>
                        <textarea wire:model="nodeConfig.description" rows="2"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>

                    {{-- Type-specific configuration --}}
                    @php
                        $node = collect($nodes)->firstWhere('id', $selectedNodeId);
                    @endphp

                    @if($node && $node['key'] === 'send_email')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Campaign
                        </label>
                        <select wire:model="nodeConfig.config.campaign_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Select a campaign</option>
                            {{-- Campaigns would be populated here --}}
                        </select>
                    </div>
                    @endif

                    @if($node && in_array($node['key'], ['add_to_list', 'remove_from_list']))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            List
                        </label>
                        <select wire:model="nodeConfig.config.list_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Select a list</option>
                            {{-- Lists would be populated here --}}
                        </select>
                    </div>
                    @endif

                    @if($node && $node['key'] === 'wait')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Delay (minutes)
                        </label>
                        <input type="number" wire:model="nodeConfig.config.delay_minutes" min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    @endif
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Save Changes
                    </button>
                    <button type="button" wire:click="deleteNode('{{ $selectedNodeId }}')"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    {{-- Node Palette Sidebar --}}
    <div class="w-64 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700">
        <div class="p-4">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                Node Palette
            </h3>
            
            <div class="space-y-4">
                {{-- Triggers --}}
                <div>
                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                        Triggers
                    </h4>
                    <div class="space-y-1">
                        <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-bolt class="w-4 h-4 text-amber-500" />
                            Cart Abandoned
                        </button>
                        <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-clipboard-document-check class="w-4 h-4 text-amber-500" />
                            Order Placed
                        </button>
                        <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-user-plus class="w-4 h-4 text-amber-500" />
                            User Registered
                        </button>
                    </div>
                </div>

                {{-- Conditions --}}
                <div>
                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                        Conditions
                    </h4>
                    <div class="space-y-1">
                        <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-arrows-right-left class="w-4 h-4 text-blue-500" />
                            If/Else
                        </button>
                        <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-list-bullet class="w-4 h-4 text-blue-500" />
                            Is in List
                        </button>
                    </div>
                </div>

                {{-- Actions --}}
                <div>
                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                        Actions
                    </h4>
                    <div class="space-y-1">
                        <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-envelope class="w-4 h-4 text-green-500" />
                            Send Email
                        </button>
                        <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-plus-circle class="w-4 h-4 text-green-500" />
                            Add to List
                        </button>
                        <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-tag class="w-4 h-4 text-green-500" />
                            Apply Tag
                        </button>
                        <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-clock class="w-4 h-4 text-green-500" />
                            Wait
                        </button>
                    </div>
                </div>

                {{-- End --}}
                <div>
                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                        End
                    </h4>
                    <div class="space-y-1">
                        <button class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-stop class="w-4 h-4 text-red-500" />
                            End
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <button wire:click="saveWorkflow"
                class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <x-heroicon-o-check class="w-4 h-4" />
                Save Workflow
            </button>
        </div>
    </div>
    @endif
</div>
