<div>
    <div class="space-y-6">
        <!-- Overall Progress -->
        <div class="bg-white rounded-lg p-4 shadow">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-medium">Overall Progress</h3>
                <span class="text-sm font-medium">{{ $overallProgress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-primary-600 h-2.5 rounded-full transition-all" style="width: {{ $overallProgress }}%"></div>
            </div>
        </div>

        <!-- Process All Button -->
        <div class="flex justify-end">
            <x-filament::button
                wire:click="processAll"
                :disabled="$currentLayout !== null"
                color="primary"
            >
                Process All Layouts
            </x-filament::button>
        </div>

        <!-- Layouts -->
        <div class="space-y-4">
            @foreach($layouts as $index => $layout)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-medium">{{ $layout['name'] }}</h4>
                            <p class="text-sm text-gray-500">Category: {{ $layout['category'] }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            @switch($layout['status'])
                                @case('pending')
                                    <x-filament::badge color="gray">Pending</x-filament::badge>
                                    @break
                                @case('processing')
                                    <x-filament::badge color="warning">Processing</x-filament::badge>
                                    @break
                                @case('completed')
                                    <x-filament::badge color="success">Completed</x-filament::badge>
                                    @break
                                @case('error')
                                    <x-filament::badge color="danger">Error</x-filament::badge>
                                    @break
                            @endswitch
                            
                            <x-filament::button
                                wire:click="processLayout({{ $index }})"
                                :disabled="$currentLayout !== null || $layout['status'] === 'processing'"
                                size="sm"
                                color="primary"
                            >
                                {{ $layout['status'] === 'completed' ? 'Reprocess' : 'Process' }}
                            </x-filament::button>
                        </div>
                    </div>

                    <!-- Section Progress -->
                    @if($layout['status'] === 'processing')
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium">Processing...</span>
                                <span class="text-sm font-medium">{{ $processingStatus[$index] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full transition-all" 
                                     style="width: {{ $processingStatus[$index] }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Content Preview -->
                    <div class="space-y-4">
                        <div>
                            <h5 class="text-sm font-medium text-gray-500 mb-2">Original Content</h5>
                            <div class="bg-gray-50 rounded p-3 text-sm">
                                {!! nl2br(e($layout['content'])) !!}
                            </div>
                        </div>

                        @if($layout['status'] === 'completed')
                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-2">Markdown</h5>
                                <div class="bg-gray-50 rounded p-3">
                                    <pre class="text-sm">{{ $layout['markdown'] }}</pre>
                                </div>
                            </div>

                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-2">HTML Preview</h5>
                                <div class="prose max-w-none bg-white border rounded p-4">
                                    {!! $layout['html'] !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
