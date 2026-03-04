<x-filament-panels::page>
    <div class="agent-chat-container">
        <!-- Chat Info Header -->
        <div class="chat-info-header mb-4">
            <div class="bg-white rounded-lg border p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-3">
                        <div class="agent-icon">
                            @switch($record->agent_type)
                                @case('customer')
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    @break
                                @case('shop')
                                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    @break
                                @case('content')
                                    <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    @break
                                @case('media')
                                    <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-image"></i>
                                    </div>
                                    @break
                                @default
                                    <div class="w-10 h-10 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-robot"></i>
                                    </div>
                            @endswitch
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold">{{ $record->title }}</h3>
                            <p class="text-sm text-gray-600">
                                {{ ucfirst($record->agent_type) }} Assistant
                                @if($record->description)
                                    • {{ $record->description }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">
                            {{ $record->getMessageCount() }} messages
                        </div>
                        <div class="text-xs text-gray-400">
                            Created {{ $record->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="chat-messages mb-4" style="height: 500px; overflow-y: auto;">
            <div class="bg-white rounded-lg border p-4 h-full">
                @if(empty($chatMessages))
                    <div class="flex items-center justify-center h-full text-gray-500">
                        <div class="text-center">
                            <i class="fas fa-comments fa-3x mb-4 text-gray-300"></i>
                            <h4 class="text-lg font-medium mb-2">Start a Conversation</h4>
                            <p>Send a message to begin chatting with the AI assistant.</p>
                        </div>
                    </div>
                @else
                    <div class="gap-y-4" id="messages-container">
                        @foreach($chatMessages as $message)
                            <div class="message-item {{ $message['role'] === 'user' ? 'flex justify-end' : 'flex justify-start' }}">
                                <div class="max-w-3xl {{ $message['role'] === 'user' ? 'order-2' : 'order-1' }}">
                                    @if($message['role'] === 'user')
                                        <!-- User Message -->
                                        <div class="bg-blue-500 text-white rounded-lg px-4 py-3">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-medium text-sm">
                                                    <i class="fas fa-user me-1"></i>You
                                                </span>
                                                <span class="text-xs opacity-75">{{ $message['created_at'] }}</span>
                                            </div>
                                            <div class="whitespace-pre-wrap">{{ $message['content'] }}</div>
                                        </div>
                                    @elseif($message['role'] === 'assistant')
                                        <!-- AI Assistant Message -->
                                        <div class="bg-gray-100 rounded-lg px-4 py-3">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-medium text-sm text-blue-600">
                                                    @switch($message['agent_type'])
                                                        @case('customer')
                                                            <i class="fas fa-users me-1"></i>Customer Service
                                                            @break
                                                        @case('shop')
                                                            <i class="fas fa-shopping-cart me-1"></i>Shop Assistant
                                                            @break
                                                        @case('content')
                                                            <i class="fas fa-edit me-1"></i>Content Manager
                                                            @break
                                                        @case('media')
                                                            <i class="fas fa-image me-1"></i>Media Manager
                                                            @break
                                                        @default
                                                            <i class="fas fa-robot me-1"></i>AI Assistant
                                                    @endswitch
                                                </span>
                                                <div class="text-xs text-gray-500 flex items-center gap-x-2">
                                                    <span>{{ $message['created_at'] }}</span>
                                                    @if($message['processing_time'])
                                                        <span>• {{ number_format($message['processing_time'], 2) }}s</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="prose prose-sm max-w-full">
                                                {!! $message['content'] !!}
                                            </div>
                                        </div>
                                    @else
                                        <!-- System Message -->
                                        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-medium text-sm text-red-600">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>System
                                                </span>
                                                <span class="text-xs text-red-500">{{ $message['created_at'] }}</span>
                                            </div>
                                            <div class="text-red-700">{{ $message['content'] }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($isProcessing)
                            <div class="message-item flex justify-start">
                                <div class="max-w-3xl">
                                    <div class="bg-gray-100 rounded-lg px-4 py-3">
                                        <div class="flex items-center gap-x-2">
                                            <div class="flex gap-x-1">
                                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                            </div>
                                            <span class="text-sm text-gray-600">AI is thinking...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Message Input -->
        <div class="message-input">
            <div class="bg-white rounded-lg border p-4">
                @if($record->is_active)
                    <form wire:submit="sendMessage" class="gap-y-3">
                        <div class="form-group">
                            <label for="userMessage" class="block text-sm font-medium text-gray-700 mb-2">Your Message</label>
                            <textarea
                                wire:model="userMessage"
                                id="userMessage"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                placeholder="Type your message here..."
                                @disabled($isProcessing)
                                autofocus
                                required
                            ></textarea>
                            @error('userMessage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-x-2 text-sm text-gray-500">
                                <i class="fas fa-info-circle"></i>
                                <span>Messages are processed by {{ ucfirst($record->agent_type) }} Assistant</span>
                            </div>

                            <button
                                type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-x-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                wire:loading.attr="disabled"
                                wire:target="sendMessage"
                                @disabled($isProcessing)
                            >
                                <span wire:loading.remove wire:target="sendMessage">
                                    <i class="fas fa-paper-plane"></i>
                                    <span class="ml-2">Send</span>
                                </span>
                                <span wire:loading wire:target="sendMessage" class="flex items-center">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    <span class="ml-2">Sending...</span>
                                </span>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-lock fa-2x mb-2"></i>
                        <p>This chat is inactive. Activate it to send messages.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions mt-4">
            <div class="bg-white rounded-lg border p-4">
                <h6 class="text-sm font-medium text-gray-700 mb-3">Quick Actions:</h6>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @switch($record->agent_type)
                        @case('customer')
                            <button
                                type="button"
                                class="p-2 text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 rounded border transition-colors"
                                wire:click="$set('userMessage', 'Find customer with email john@example.com')"
                            >
                                <i class="fas fa-search me-1"></i>Find Customer
                            </button>
                            @break
                        @case('shop')
                            <button
                                type="button"
                                class="p-2 text-xs bg-green-50 hover:bg-green-100 text-green-700 rounded border transition-colors"
                                wire:click="$set('userMessage', 'Search for products under €50')"
                            >
                                <i class="fas fa-search me-1"></i>Search Products
                            </button>
                            @break
                        @case('content')
                            <button
                                type="button"
                                class="p-2 text-xs bg-yellow-50 hover:bg-yellow-100 text-yellow-700 rounded border transition-colors"
                                wire:click="$set('userMessage', 'Help me write SEO content')"
                            >
                                <i class="fas fa-edit me-1"></i>Content Help
                            </button>
                            @break
                        @default
                            <button
                                type="button"
                                class="p-2 text-xs bg-gray-50 hover:bg-gray-100 text-gray-700 rounded border transition-colors"
                                wire:click="$set('userMessage', 'What can you help me with?')"
                            >
                                <i class="fas fa-question-circle me-1"></i>General Help
                            </button>
                    @endswitch
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-scroll script -->
    <script>
        document.addEventListener('livewire:updated', () => {
            scrollToBottom();
        });

        document.addEventListener('DOMContentLoaded', () => {
            scrollToBottom();
        });

        function scrollToBottom() {
            const chatMessages = document.querySelector('.chat-messages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }

    // Auto-refresh messages every 30 seconds
    setInterval(() => {
        if (!$wire.isProcessing) {
            $wire.dispatch('refresh-messages');
        }
    }, 30000);
    </script>

    <!-- Custom styles -->
    <style>
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .message-item {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }

        .prose p {
            margin-bottom: 0.75rem;
        }

        .prose ul, .prose ol {
            margin-bottom: 0.75rem;
        }
    </style>
</x-filament-panels::page>
