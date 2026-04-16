<div
    x-data="agentChat()"
    x-init="initChat()"
    class="agent-chat-component"
>
    {{-- Offline Indicator --}}
    <div
        x-show="isOffline"
        x-transition
        class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 mb-4 rounded-lg"
    >
        <div class="flex items-center">
            <x-heroicon-o-signal-slash class="w-5 h-5 text-red-500 mr-3"/>
            <div>
                <p class="font-medium text-red-800 dark:text-red-300">Connection Lost</p>
                <p class="text-sm text-red-600 dark:text-red-400">Please check your internet connection. Messages will be sent when you reconnect.</p>
            </div>
        </div>
    </div>

    {{-- Rate Limit Warning --}}
    @if($rateLimitMessage)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-4 rounded-lg">
        <div class="flex items-center">
            <x-heroicon-o-clock class="w-5 h-5 text-yellow-500 mr-3"/>
            <div>
                <p class="font-medium text-yellow-800">Rate Limited</p>
                <p class="text-sm text-yellow-600">{{ $rateLimitMessage }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Error Message --}}
    @if($errorMessage && !$isOffline)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-500 mr-2"/>
                <span class="text-red-700">{{ $errorMessage }}</span>
            </div>
            <button
                wire:click="retryLastMessage"
                type="button"
                class="text-sm text-red-600 hover:text-red-800 underline"
            >
                Retry
            </button>
        </div>
    </div>
    @endif

    {{-- Chat Messages Container --}}
    <div
        x-ref="messagesContainer"
        class="chat-messages mb-4 bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700 p-4"
        style="height: 500px; overflow-y: auto;"
    >
        @if(empty($chatMessages))
            <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                <div class="text-center">
                    <x-heroicon-o-chat-bubble-left-right class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600"/>
                    <h4 class="text-lg font-medium mb-2 dark:text-white">Start a Conversation</h4>
                    <p>Send a message to begin chatting with the AI assistant.</p>
                </div>
            </div>
        @else
            <div class="space-y-4" id="messages-list">
                @foreach($chatMessages as $index => $message)
                    <div
                        wire:key="message-{{ $message['id'] }}"
                        class="message-item {{ $message['role'] === 'user' ? 'flex justify-end' : 'flex justify-start' }}"
                    >
                        <div class="max-w-3xl {{ $message['role'] === 'user' ? 'order-2' : 'order-1' }}">
                            @if($message['role'] === 'user')
                                {{-- User Message --}}
                                <div class="bg-blue-500 text-white rounded-lg px-4 py-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-medium text-sm flex items-center">
                                            <x-heroicon-o-user class="w-4 h-4 mr-1"/>
                                            You
                                        </span>
                                        <span class="text-xs opacity-75">{{ $message['created_at'] }}</span>
                                    </div>
                                    <div class="whitespace-pre-wrap">{{ $message['content'] }}</div>
                                </div>
                            @elseif($message['role'] === 'assistant')
                                {{-- AI Assistant Message --}}
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-sm text-blue-600 dark:text-blue-400 flex items-center">
                                            @switch($message['agent_type'])
                                                @case('customer')
                                                    <x-heroicon-o-users class="w-4 h-4 mr-1"/>
                                                    Customer Service
                                                    @break
                                                @case('shop')
                                                    <x-heroicon-o-shopping-cart class="w-4 h-4 mr-1"/>
                                                    Shop Assistant
                                                    @break
                                                @case('content')
                                                    <x-heroicon-o-pencil-square class="w-4 h-4 mr-1"/>
                                                    Content Manager
                                                    @break
                                                @case('media')
                                                    <x-heroicon-o-photo class="w-4 h-4 mr-1"/>
                                                    Media Manager
                                                    @break
                                                @default
                                                    <x-heroicon-o-sparkles class="w-4 h-4 mr-1"/>
                                                    AI Assistant
                                            @endswitch
                                        </span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-x-2">
                                            <span>{{ $message['created_at'] }}</span>
                                            @if($message['processing_time'])
                                                <span>• {{ number_format($message['processing_time'], 2) }}s</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="prose prose-sm dark:prose-invert max-w-full">
                                        {!! $message['content'] !!}
                                    </div>
                                </div>
                            @else
                                {{-- System Message --}}
                                <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-medium text-sm text-red-600 flex items-center">
                                            <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1"/>
                                            System
                                        </span>
                                        <span class="text-xs text-red-500">{{ $message['created_at'] }}</span>
                                    </div>
                                    <div class="text-red-700">{{ $message['content'] }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- Streaming Placeholder --}}
                @if($isStreaming)
                    <div
                        wire:key="streaming-message"
                        class="message-item flex justify-start"
                    >
                        <div class="max-w-3xl">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-3">
                                <div class="flex items-center gap-x-2 mb-2">
                                    <span class="font-medium text-sm text-blue-600 dark:text-blue-400 flex items-center">
                                        <x-heroicon-o-sparkles class="w-4 h-4 mr-1"/>
                                        AI Assistant
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Thinking...</span>
                                </div>
                                <div class="flex items-center gap-x-2">
                                    <div class="flex gap-x-1">
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">AI is processing...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- File Upload Preview --}}
    @if(!empty($uploadedFiles))
        <div class="mb-4 bg-gray-50 dark:bg-gray-800 rounded-lg border dark:border-gray-700 p-3">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attachments:</div>
            <div class="flex flex-wrap gap-2">
                @foreach($uploadedFiles as $index => $file)
                    <div class="flex items-center bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:text-gray-200">
                        @if(str_starts_with($file['type'], 'image/'))
                            <img src="{{ $file['temporaryUrl'] }}" alt="Preview" class="w-8 h-8 object-cover rounded mr-2">
                        @else
                            <x-heroicon-o-document class="w-5 h-5 text-gray-400 mr-2"/>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="truncate max-w-xs">{{ $file['name'] }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $file['size'] }}</div>
                        </div>
                        <button
                            wire:click="removeAttachment({{ $index }})"
                            type="button"
                            class="ml-2 text-red-500 hover:text-red-700"
                        >
                            <x-heroicon-o-x-mark class="w-4 h-4"/>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

{{-- Message Input --}}
<div class="message-input bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700 p-4">
    @if($this->chat && $this->chat->is_active)
            <form wire:submit="sendMessage" class="space-y-3">
                <div class="form-group">
                    <label for="userMessage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Message</label>

                    {{-- File Upload Button --}}
                    <div class="flex items-center gap-2 mb-2">
                        <label class="cursor-pointer inline-flex items-center px-3 py-1.5 text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <x-heroicon-o-paper-clip class="w-4 h-4 mr-1"/>
                            Attach File
                            <input
                                type="file"
                                wire:model="attachments"
                                multiple
                                class="hidden"
                                accept="image/*,.pdf,.txt,.doc,.docx"
                            >
                        </label>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Max 5MB per file</span>
                    </div>

                    <textarea
                        wire:model="userMessage"
                        id="userMessage"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Type your message here..."
                        @disabled($isProcessing || $isOffline)
                        @keydown.enter.prevent="if (!\$event.shiftKey) { \$wire.sendMessage() }"
                        autofocus
                    ></textarea>
                    @error('userMessage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
            <div class="flex items-center gap-x-2 text-sm text-gray-500 dark:text-gray-400">
                <x-heroicon-o-information-circle class="w-4 h-4"/>
                <span>Messages are processed by {{ ucfirst($this->chat?->agent_type ?? 'AI') }} Assistant</span>
            </div>

                    <button
                        type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-x-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:target="sendMessage"
                        @disabled($isProcessing || $isOffline)
                    >
                        <span wire:loading.remove wire:target="sendMessage" class="flex items-center">
                            <x-heroicon-o-paper-airplane class="w-4 h-4 mr-2"/>
                            Send
                        </span>
                        <span wire:loading wire:target="sendMessage" class="flex items-center">
                            <x-heroicon-o-arrow-path class="w-4 h-4 mr-2 animate-spin"/>
                            Sending...
                        </span>
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <x-heroicon-o-lock-closed class="w-10 h-10 mx-auto mb-2 text-gray-400 dark:text-gray-500"/>
                <p>This chat is inactive. Activate it to send messages.</p>
            </div>
        @endif
    </div>

    <script>
        function agentChat() {
            return {
                isOffline: @entangle('isOffline'),
                shouldScrollToBottom: @entangle('shouldScrollToBottom'),

                initChat() {
                    this.scrollToBottom();
                    this.setupIntersectionObserver();
                    this.setupOnlineStatus();

                    // Listen for Livewire events
                    Livewire.on('message-added', () => {
                        this.scrollToBottom();
                    });
                },

                scrollToBottom() {
                    if (this.shouldScrollToBottom) {
                        this.\$nextTick(() => {
                            const container = this.\$refs.messagesContainer;
                            if (container) {
                                container.scrollTop = container.scrollHeight;
                            }
                        });
                    }
                },

                setupIntersectionObserver() {
                    const container = this.\$refs.messagesContainer;
                    if (!container) return;

                    // Check if user is near bottom before scrolling
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.shouldScrollToBottom = true;
                            } else {
                                // Only disable auto-scroll if user scrolled up significantly
                                const scrollBottom = container.scrollTop + container.clientHeight;
                                const scrollThreshold = container.scrollHeight - 100;
                                if (scrollBottom < scrollThreshold) {
                                    this.shouldScrollToBottom = false;
                                }
                            }
                        });
                    }, { threshold: 0.1 });

                    // Observe the last message or a sentinel element
                    const sentinel = container.querySelector('.sentinel-bottom');
                    if (sentinel) {
                        observer.observe(sentinel);
                    }
                },

                setupOnlineStatus() {
                    window.addEventListener('online', () => {
                        this.isOffline = false;
                        Livewire.dispatch('connection-restored');
                    });

                    window.addEventListener('offline', () => {
                        this.isOffline = true;
                        Livewire.dispatch('connection-lost');
                    });

                    // Initial check
                    if (!navigator.onLine) {
                        this.isOffline = true;
                        Livewire.dispatch('connection-lost');
                    }
                }
            }
        }
    </script>

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

        .dark .chat-messages::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .dark .chat-messages::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }

        /* Dark mode overrides — bypass oklch/rgba Tailwind v4 bug */
        .dark .agent-chat-component .chat-messages,
        .dark .agent-chat-component .message-input {
            background-color: rgb(31 41 55) !important;
            border-color: rgb(55 65 81) !important;
        }

        .dark .agent-chat-component .message-input label[for="userMessage"] {
            color: rgb(209 213 219) !important;
        }

        .dark .agent-chat-component .message-input label.cursor-pointer {
            color: rgb(209 213 219) !important;
            background-color: rgb(55 65 81) !important;
        }

        .dark .agent-chat-component .message-input label.cursor-pointer:hover {
            background-color: rgb(75 85 99) !important;
        }

        .dark .agent-chat-component .message-input textarea {
            background-color: rgb(17 24 39) !important;
            border-color: rgb(55 65 81) !important;
            color: white !important;
        }

        .dark .agent-chat-component .message-input textarea:focus {
            border-color: rgb(59 130 246) !important;
        }

        .dark .agent-chat-component .bg-gray-100 {
            background-color: rgb(55 65 81) !important;
        }

        .dark .agent-chat-component .prose {
            color: rgb(229 231 235) !important;
        }
    </style>
</div>
