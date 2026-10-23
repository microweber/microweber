{{-- ============================================================================
     Shared component gallery — raw theme-CSS components in every variant.
     Included by BOTH the KitchenSink page (Components tab) and the SamplePage
     (flat, single-page). One source → the visual-regression reference for the
     theme CSS refactor; screenshot it in light + dark before/after each change.
     Needs no page state — pure markup + two local PHP arrays.
     ============================================================================ --}}
@php
    $btnColors = ['primary' => 'Primary', 'success' => 'Success', 'danger' => 'Danger', 'warning' => 'Warning', 'info' => 'Info', 'gray' => 'Gray'];
    $sizes = ['xs' => 'XS', 'sm' => 'SM', 'md' => 'MD', 'lg' => 'LG', 'xl' => 'XL'];
@endphp

<div class="space-y-8">

    {{-- Buttons -------------------------------------------------- --}}
    <section>
        <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Buttons — solid</h2>
        <div class="flex flex-wrap items-center gap-3">
            @foreach ($btnColors as $c => $label)
                <x-filament::button :color="$c">{{ $label }}</x-filament::button>
            @endforeach
        </div>

        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Buttons — outlined</h2>
        <div class="flex flex-wrap items-center gap-3">
            @foreach ($btnColors as $c => $label)
                <x-filament::button :color="$c" outlined>{{ $label }}</x-filament::button>
            @endforeach
        </div>

        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Buttons — sizes, icon, disabled</h2>
        <div class="flex flex-wrap items-center gap-3">
            @foreach ($sizes as $s => $label)
                <x-filament::button color="primary" :size="$s">{{ $label }}</x-filament::button>
            @endforeach
            <x-filament::button color="primary" icon="heroicon-m-plus">With icon</x-filament::button>
            <x-filament::button color="success" icon="heroicon-m-check" icon-position="after">Icon after</x-filament::button>
            <x-filament::button color="primary" disabled>Disabled</x-filament::button>
            <x-filament::button color="gray" :disabled="true" outlined>Disabled outline</x-filament::button>
        </div>

        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Icon buttons</h2>
        <div class="flex flex-wrap items-center gap-3">
            @foreach ($btnColors as $c => $label)
                <x-filament::icon-button icon="heroicon-m-pencil-square" :color="$c" :label="$label" />
            @endforeach
            <x-filament::icon-button icon="heroicon-m-trash" color="danger" label="Delete" size="lg" />
        </div>
    </section>

    {{-- Badges --------------------------------------------------- --}}
    <section>
        <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Badges</h2>
        <div class="flex flex-wrap items-center gap-3">
            @foreach ($btnColors as $c => $label)
                <x-filament::badge :color="$c">{{ $label }}</x-filament::badge>
            @endforeach
        </div>
        <div class="mt-3 flex flex-wrap items-center gap-3">
            <x-filament::badge color="success" icon="heroicon-m-check-circle">With icon</x-filament::badge>
            <x-filament::badge color="warning" size="lg">Large</x-filament::badge>
            <x-filament::badge color="gray" size="sm">Small</x-filament::badge>
        </div>
    </section>

    {{-- Links ---------------------------------------------------- --}}
    <section>
        <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Links</h2>
        <div class="flex flex-wrap items-center gap-4">
            @foreach ($btnColors as $c => $label)
                <x-filament::link href="#" :color="$c">{{ $label }} link</x-filament::link>
            @endforeach
            <x-filament::link href="#" icon="heroicon-m-arrow-top-right-on-square">Link with icon</x-filament::link>
        </div>
    </section>

    {{-- Sections ------------------------------------------------- --}}
    <section class="space-y-4">
        <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Sections</h2>
        <x-filament::section>
            <x-slot name="heading">Section with heading</x-slot>
            <x-slot name="description">A short description under the heading.</x-slot>
            <p class="text-sm text-gray-600 dark:text-gray-300">Section body content goes here. Cards, forms, and tables typically sit inside sections.</p>
        </x-filament::section>
        <x-filament::section :aside="true">
            <x-slot name="heading">Aside section</x-slot>
            <x-slot name="description">Heading + description sit beside the content.</x-slot>
            <p class="text-sm text-gray-600 dark:text-gray-300">Aside layout body content.</p>
        </x-filament::section>
        <x-filament::section collapsible>
            <x-slot name="heading">Collapsible section</x-slot>
            <p class="text-sm text-gray-600 dark:text-gray-300">Click the heading to collapse.</p>
        </x-filament::section>
    </section>

    {{-- Table ---------------------------------------------------- --}}
    <section>
        <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Table (representative markup)</h2>
        <div class="fi-ta-ctn rounded-xl overflow-hidden">
            <table class="fi-ta-table w-full text-start text-sm table-auto">
                <thead>
                    <tr class="fi-ta-row">
                        <th class="fi-ta-header-cell px-4 py-3 text-start align-middle font-semibold">Name</th>
                        <th class="fi-ta-header-cell px-4 py-3 text-start align-middle font-semibold">Status</th>
                        <th class="fi-ta-header-cell px-4 py-3 text-start align-middle font-semibold">Role</th>
                        <th class="fi-ta-header-cell px-4 py-3 text-end align-middle font-semibold w-px whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([['Ada Lovelace', 'success', 'Published', 'Admin'], ['Alan Turing', 'warning', 'Draft', 'Editor'], ['Grace Hopper', 'danger', 'Archived', 'Viewer']] as [$name, $badge, $state, $role])
                        <tr class="fi-ta-row">
                            <td class="fi-ta-cell px-4 py-3 align-middle">{{ $name }}</td>
                            <td class="fi-ta-cell px-4 py-3 align-middle"><x-filament::badge :color="$badge">{{ $state }}</x-filament::badge></td>
                            <td class="fi-ta-cell px-4 py-3 align-middle">{{ $role }}</td>
                            <td class="fi-ta-cell px-4 py-3 align-middle w-px whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <x-filament::icon-button icon="heroicon-m-pencil-square" color="primary" label="Edit" size="sm" />
                                    <x-filament::icon-button icon="heroicon-m-trash" color="danger" label="Delete" size="sm" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- Misc: avatars, loading ----------------------------------- --}}
    <section>
        <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Avatars &amp; loading</h2>
        <div class="flex flex-wrap items-center gap-4">
            <x-filament::avatar src="https://ui-avatars.com/api/?name=Ada+Lovelace" alt="Ada" />
            <x-filament::loading-indicator class="h-6 w-6 text-primary-500" />
        </div>
    </section>

</div>
