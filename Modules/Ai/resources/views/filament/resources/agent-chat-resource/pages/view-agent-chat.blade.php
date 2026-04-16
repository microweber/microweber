<x-filament-panels::page>
    <div class="agent-chat-container">
        <!-- Chat Info Header -->
        <div class="chat-info-header mb-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-3">
                        <div class="agent-icon">
                            @switch($record->agent_type)
                                @case('customer')
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center">
                                        <x-heroicon-o-users class="w-6 h-6"/>
                                    </div>
                                    @break
                                @case('shop')
                                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center">
                                        <x-heroicon-o-shopping-cart class="w-6 h-6"/>
                                    </div>
                                    @break
                                @case('content')
                                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-full flex items-center justify-center">
                                        <x-heroicon-o-pencil-square class="w-6 h-6"/>
                                    </div>
                                    @break
                                @case('media')
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full flex items-center justify-center">
                                        <x-heroicon-o-photo class="w-6 h-6"/>
                                    </div>
                                    @break
                                @default
                                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full flex items-center justify-center">
                                        <x-heroicon-o-sparkles class="w-6 h-6"/>
                                    </div>
                            @endswitch
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold dark:text-white">{{ $record->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ ucfirst($record->agent_type) }} Assistant
                                @if($record->description)
                                    • {{ $record->description }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $record->getMessageCount() }} messages
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            Created {{ $record->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Livewire Chat Component -->
        <livewire:ai.agent-chat-component :chat-id="$record->id" />

        <!-- Quick Actions -->
        <div class="quick-actions mt-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700 p-4">
                <h6 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Quick Actions:</h6>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @switch($record->agent_type)
                        @case('customer')
                            <button
                                type="button"
                                class="p-2 text-xs bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded border dark:border-gray-600 transition-colors"
                                onclick="document.getElementById('userMessage').value = 'Find customer with email john@example.com'; document.getElementById('userMessage').dispatchEvent(new Event('input'));"
                            >
                                <x-heroicon-o-magnifying-glass class="w-3 h-3 inline mr-1"/>
                                Find Customer
                            </button>
                            @break
                        @case('shop')
                            <button
                                type="button"
                                class="p-2 text-xs bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 rounded border dark:border-gray-600 transition-colors"
                                onclick="document.getElementById('userMessage').value = 'Search for products under €50'; document.getElementById('userMessage').dispatchEvent(new Event('input'));"
                            >
                                <x-heroicon-o-magnifying-glass class="w-3 h-3 inline mr-1"/>
                                Search Products
                            </button>
                            @break
                        @case('content')
                            <button
                                type="button"
                                class="p-2 text-xs bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 rounded border dark:border-gray-600 transition-colors"
                                onclick="document.getElementById('userMessage').value = 'Help me write SEO content'; document.getElementById('userMessage').dispatchEvent(new Event('input'));"
                            >
                                <x-heroicon-o-pencil class="w-3 h-3 inline mr-1"/>
                                Content Help
                            </button>
                            @break
                        @default
                            <button
                                type="button"
                                class="p-2 text-xs bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded border dark:border-gray-600 transition-colors"
                                onclick="document.getElementById('userMessage').value = 'What can you help me with?'; document.getElementById('userMessage').dispatchEvent(new Event('input'));"
                            >
                                <x-heroicon-o-question-mark-circle class="w-3 h-3 inline mr-1"/>
                                General Help
                            </button>
                    @endswitch
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
