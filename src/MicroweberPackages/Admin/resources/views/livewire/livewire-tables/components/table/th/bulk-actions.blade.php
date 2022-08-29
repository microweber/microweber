@aware(['component'])

@if ($component->bulkActionsAreEnabled() && $component->hasBulkActions())
    @php
        $theme = $component->getTheme();
    @endphp

    @if ($theme === 'tailwind')
        <x-livewire-tables::table.th.plain>
            <div class="inline-flex rounded-md shadow-sm">
                <input
                    wire:model="selectAll"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm transition duration-150 ease-in-out focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:bg-gray-600"
                />
            </div>
        </x-livewire-tables::table.th.plain>
    @elseif ($theme === 'bootstrap-4' || $theme === 'bootstrap-5')
        <x-livewire-tables::table.th.plain>
            <input
                wire:model="selectAll"
                type="checkbox"
            />
        </x-livewire-tables::table.th.plain>
    @endif
@endif
