<div>
    @if($showSectionSelector)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-medium mb-4">Select Page Sections</h2>
            <div class="space-y-4">
                <x-filament::grid :default="2">
                    @foreach($availableSections as $value => $label)
                        <label class="flex items-center space-x-3">
                            <x-filament::input.checkbox
                                wire:model.live="selectedSections"
                                :value="$value"
                            />
                            <span class="text-sm">{{ $label }}</span>
                        </label>
                    @endforeach
                </x-filament::grid>

                <div class="flex justify-end mt-6">
                    <x-filament::button
                        wire:click="confirmSectionSelection"
                        color="primary"
                    >
                        Generate Content
                    </x-filament::button>
                </div>
            </div>
        </div>
    @endif

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
                :disabled="$currentSection !== null"
                color="primary"
            >
                Process All Sections
            </x-filament::button>
        </div>

        <!-- Sections -->
        <div class="space-y-4">
            @foreach($sections as $index => $section)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium">{{ $section['name'] }}</h4>
                        <div class="flex items-center space-x-2">
                            @switch($section['status'])
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
                                wire:click="processSection({{ $index }})"
                                :disabled="$currentSection !== null || $section['status'] === 'processing'"
                                size="sm"
                                color="primary"
                            >
                                {{ $section['status'] === 'completed' ? 'Reprocess' : 'Process' }}
                            </x-filament::button>
                        </div>
                    </div>

                    <!-- Section Progress -->
                    @if($section['status'] === 'processing')
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
                                {!! nl2br(e($section['content'])) !!}
                            </div>
                        </div>

                        @if($section['status'] === 'completed')
                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-2">Markdown</h5>
                                <div class="bg-gray-50 rounded p-3">
                                    <pre class="text-sm">{{ $section['markdown'] }}</pre>
                                </div>
                            </div>

                            <div>
                                <h5 class="text-sm font-medium text-gray-500 mb-2">HTML Preview</h5>
                                <div class="prose max-w-none bg-white border rounded p-4">
                                    {!! $section['html'] !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
