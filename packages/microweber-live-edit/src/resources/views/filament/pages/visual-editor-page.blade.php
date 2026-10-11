<x-filament-panels::page>
    <div class="h-full flex flex-col">
        @if($contentId)
            <livewire:visual-editor :content-id="$contentId" />
        @else
            <div class="flex items-center justify-center h-96">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ _e('No content selected') }}</h3>
                    <p class="text-gray-500 mb-4">{{ _e('Please select content to edit or create new content.') }}</p>
                    <x-filament::button
                        tag="a"
                        :href="route('filament.admin.resources.content.index')"
                    >
                        {{ _e('Browse Content') }}
                    </x-filament::button>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
