<x-filament-panels::page>

    <script>
        addEventListener('load', function () {
            if (typeof mw === 'undefined' || typeof mw.FileManager !== 'function') return;
            var fm = mw.FileManager({
                element: '#filepick1',
                canSelectFolder: false,
                stickyHeader: false,
                type: '*',
                selectable: true,
                multiselect: true,
                options: true,
                selectableRow: false,
            });
            fm.on('insert', function (val) { console.log(val) });
            fm.on('insertByValue', function (val) { console.log(val) });
            fm.on('selectionChanged', function (val) { console.log(val) });
        });
    </script>

    <div x-data="{ activeTab: 'components' }"
         x-init="$watch('activeTab', () => $nextTick(() => {
             /* JS editors (EasyMDE/CodeMirror) init with 0 height while their tab is
                display:none; re-measure them when the tab becomes visible. */
             window.dispatchEvent(new Event('resize'));
             document.querySelectorAll('.CodeMirror').forEach(c => c.CodeMirror && c.CodeMirror.refresh());
         }))">

        <x-filament::tabs>
            <x-filament::tabs.item alpine-active="activeTab === 'components'" x-on:click="activeTab = 'components'">Components</x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="activeTab === 'form'" x-on:click="activeTab = 'form'">Form</x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="activeTab === 'media'" x-on:click="activeTab = 'media'">Media</x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="activeTab === 'icons'" x-on:click="activeTab = 'icons'">Icons</x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="activeTab === 'other'" x-on:click="activeTab = 'other'">Other</x-filament::tabs.item>
        </x-filament::tabs>

        {{-- Icons --}}
        <div x-show="activeTab == 'icons'" class="mt-4">
            <div class="text-sm text-gray-600 dark:text-gray-300">
                Icons package: <code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100">microweber-packages/svg-icons</code>
                &mdash; path: <code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100">packages/microweber-svg-icons/resources/svg/</code>
            </div>
            @include('modules.kitchen_sink::partials.icon-gallery')
        </div>

        {{-- Form (Filament schema — reuses RichFormSchemas) --}}
        <div x-show="activeTab == 'form'" class="mt-4">
            {{ $this->form }}
        </div>

        {{-- Media --}}
        <div x-show="activeTab == 'media'" class="mt-4">
            <div id="filepick1"></div>
        </div>

        {{-- Components gallery (shared partial) --}}
        <div x-show="activeTab == 'components'" class="mt-4">
            @include('modules.kitchen_sink::partials.components-gallery')
        </div>

        {{-- Interactive widgets (shared partial) --}}
        <div x-show="activeTab == 'other'" class="mt-4">
            @include('modules.kitchen_sink::partials.interactive-widgets')
        </div>

    </div>

</x-filament-panels::page>
