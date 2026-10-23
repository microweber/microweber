<x-filament-panels::page>

    {{-- Media picker bootstrap (shared with the kitchen sink) --}}
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
            fm.on('selectionChanged', function (val) { console.log(val) });
        });
    </script>

    <div class="space-y-12">

        <p class="text-sm text-gray-600 dark:text-gray-300">
            Every UI component on a single page. The sections below are the SAME partials
            and the SAME Filament form (shared <code class="px-1 rounded bg-gray-100 dark:bg-gray-800">RichFormSchemas</code>)
            used by the tabbed <a href="{{ url('admin/kitchen-sink') }}" class="text-primary-600 dark:text-primary-400 underline">Kitchen Sink</a> —
            one source, two layouts.
        </p>

        {{-- Components gallery ------------------------------------------- --}}
        <section>
            <h2 class="mb-4 text-lg font-bold tracking-tight text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Components</h2>
            @include('modules.kitchen_sink::partials.components-gallery')
        </section>

        {{-- Form (shared RichFormSchemas + all Filament inputs) ---------- --}}
        <section>
            <h2 class="mb-4 text-lg font-bold tracking-tight text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Form components</h2>
            {{ $this->form }}
        </section>

        {{-- Media -------------------------------------------------------- --}}
        <section>
            <h2 class="mb-4 text-lg font-bold tracking-tight text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Media browser</h2>
            <div id="filepick1"></div>
        </section>

        {{-- Icons -------------------------------------------------------- --}}
        <section>
            <h2 class="mb-4 text-lg font-bold tracking-tight text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Icons</h2>
            @include('modules.kitchen_sink::partials.icon-gallery')
        </section>

        {{-- Interactive widgets ------------------------------------------ --}}
        <section>
            <h2 class="mb-4 text-lg font-bold tracking-tight text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Interactive widgets</h2>
            @include('modules.kitchen_sink::partials.interactive-widgets')
        </section>

    </div>

</x-filament-panels::page>
