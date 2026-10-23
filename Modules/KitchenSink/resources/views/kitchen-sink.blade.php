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
            fm.on('insert', function (val) {
                console.log(val)
            });
            fm.on('insertByValue', function (val) {
                console.log(val)
            });
            fm.on('selectionChanged', function (val) {
                console.log(val)
            });
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
            <x-filament::tabs.item
                alpine-active="activeTab === 'components'"
                x-on:click="activeTab = 'components'"
            >
                Components
            </x-filament::tabs.item>

            <x-filament::tabs.item
                alpine-active="activeTab === 'form'"
                x-on:click="activeTab = 'form'"
            >
                Form
            </x-filament::tabs.item>

            <x-filament::tabs.item
                alpine-active="activeTab === 'media'"
                x-on:click="activeTab = 'media'"
            >
                Media
            </x-filament::tabs.item>

            <x-filament::tabs.item
                alpine-active="activeTab === 'icons'"
                x-on:click="activeTab = 'icons'"
            >
                Icons
            </x-filament::tabs.item>

            <x-filament::tabs.item
                alpine-active="activeTab === 'other'"
                x-on:click="activeTab = 'other'"
            >
                Other
            </x-filament::tabs.item>

        </x-filament::tabs>

        <div x-show="activeTab == 'icons'" class="mt-4">
            <div class="text-sm text-gray-600 dark:text-gray-300">
                Icons package: <code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100">microweber-packages/svg-icons</code>
                &mdash; path: <code class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100">packages/microweber-svg-icons/resources/svg/</code>
            </div>
            <div class="grid grid-cols-4 gap-4 mt-3">
                @foreach($this->getIcons() as $icon)
                    {{-- Hover state intentionally same-direction in
                         both modes: brighten the tile, do not invert
                         to white in dark mode (the previous
                         group-hover:bg-white made tiles glow white
                         on hover under the dark theme). --}}
                    <div
                        class="group flex flex-col items-center justify-center rounded-xl p-4 bg-blue-500/10 dark:bg-white/5 hover:bg-blue-500/20 dark:hover:bg-white/10 transition duration-150">
                        <div class="flex items-center justify-center">
                            @svg('mw-'.$icon, "h-12 w-12 text-gray-900 dark:text-white")
                        </div>
                        <div class="w-full text-center mt-2 text-xs font-medium text-gray-900 dark:text-white break-words leading-tight">
                            mw-{{$icon}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <div x-show="activeTab == 'form'" class="mt-4">
            {{$this->form}}
        </div>

        <div x-show="activeTab == 'media'" class="mt-4">
            <div id="filepick1"></div>
        </div>

        {{-- ============================================================= --}}
        {{-- Components gallery — raw theme-CSS components in every variant. --}}
        {{-- This is the visual-regression reference for the theme CSS       --}}
        {{-- refactor (consolidation / !important removal): screenshot this   --}}
        {{-- tab in light + dark before/after each change and compare.        --}}
        {{-- ============================================================= --}}
        <div x-show="activeTab == 'components'" class="mt-4 space-y-8">

            @php
                $btnColors = ['primary' => 'Primary', 'success' => 'Success', 'danger' => 'Danger', 'warning' => 'Warning', 'info' => 'Info', 'gray' => 'Gray'];
                $sizes = ['xs' => 'XS', 'sm' => 'SM', 'md' => 'MD', 'lg' => 'LG', 'xl' => 'XL'];
            @endphp

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

            {{-- Misc: avatars, loading, inline badges -------------------- --}}
            <section>
                <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Avatars &amp; loading</h2>
                <div class="flex flex-wrap items-center gap-4">
                    <x-filament::avatar src="https://ui-avatars.com/api/?name=Ada+Lovelace" alt="Ada" />
                    <x-filament::loading-indicator class="h-6 w-6 text-primary-500" />
                </div>
            </section>

        </div>

    <div x-show="activeTab == 'other'" class="mt-4 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 space-y-4 text-gray-900 dark:text-gray-100">
        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            Modal
            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
mw.dialog({
    content: 'dialog'
})
            </pre>

            <x-filament::button color="gray" @click="mw.dialog({
                content: 'dialog'
            })">
                New user
            </x-filament::button>
        </h2>

        <hr class="my-6 border-gray-200 dark:border-gray-700">

        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            File picker
            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
mw.filePickerDialog( (url) => {
    console.log(url)
});
            </pre>
            <x-filament::button color="gray" @click="mw.filePickerDialog( (url) => {
                console.log(url)
            });">
                Pick file
            </x-filament::button>
        </h2>

        <hr class="my-6 border-gray-200 dark:border-gray-700">
        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            Url picker
            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
var linkEditor = new mw.LinkEditor({
    mode: 'dialog',
});
var val = 'http://google.com'
if(val) {
    linkEditor.setValue(val);
}

linkEditor.promise().then(function (data){
    var modal = linkEditor.dialog;
    if(data) {


    }
    modal.remove();
});
            </pre>

            <div class="px-8">
            </div>
            <x-filament::button color="gray" @click="const linkEditor = new mw.LinkEditor({
                mode: 'dialog',
            });
            const val = 'http://google.com'
            if(val) {
                linkEditor.setValue(val);
            }

            linkEditor.promise().then(function (data){
                const modal = linkEditor.dialog;
                console.log(data);
                modal.remove();
            });">
                Pick URL
            </x-filament::button>
        </h2>

        <hr class="my-6 border-gray-200 dark:border-gray-700">
        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            Tree

            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
mw.widget.tree('.my-element');
mw.widget.tree(nodeElement);
            </pre>
            <div class="tree-example"></div>
            <div class="tree-example-context"></div>

            <script>
                addEventListener('DOMContentLoaded', e => mw.widget.tree('.tree-example'))
                addEventListener('DOMContentLoaded', e => mw.widget.tree('.tree-example-context', {
                    options: {
                        contextMenu: [

                            {
                                title: 'Edit',
                                icon: 'mdi mdi-pencil',
                                action: function (element, data, menuitem) {

                                }
                            },
                            {
                                title: 'Delete',
                                icon: 'mdi mdi-trash',
                                action: function (element, data, menuitem) {
                                    element.remove()
                                }
                            },

                        ]
                    }

                }))
            </script>
        </h2>

        <hr class="my-6 border-gray-200 dark:border-gray-700">
        <style>
            .dark .richtext-example .tox .tox-edit-area__iframe { background-color: #1a1a2e; }
            .dark .richtext-example .tox:not(.tox-tinymce-inline) .tox-editor-header,
            .dark .richtext-example .tox .tox-toolbar,
            .dark .richtext-example .tox .tox-toolbar__primary,
            .dark .richtext-example .tox .tox-toolbar__overflow,
            .dark .richtext-example .tox .tox-statusbar { background-color: #1f2937; border-color: #374151; }
            .dark .richtext-example .tox .tox-tbtn svg { fill: #d1d5db; }
            .dark .richtext-example .tox .tox-tbtn { color: #d1d5db; }
            .dark .richtext-example .tox .tox-tbtn:hover { background-color: #374151; }
            .dark .richtext-example .tox:not(.tox-tinymce-inline) { border-color: #374151; }
        </style>
        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            Editor

            <div class="richtext-example"></div>

            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
const editor = mw.richTextEditor({
    target: '.richtext-example'
});
const editor = mw.richTextEditor({
    target: document.querySelector('.some-selector')
})

editor.on('change', val => {
    console.log(val)
})
            </pre>
            <script>
                addEventListener('load', function () {
                    if (typeof mw === 'undefined' || typeof mw.richTextEditor !== 'function') return;
                    if (typeof tinymce === 'undefined') return;
                    var editor = mw.richTextEditor({
                        target: '.richtext-example'
                    });
                    editor.on('change', function (val) {
                        console.log(val);
                    });
                });
            </script>
        </h2>

        <hr class="my-6 border-gray-200 dark:border-gray-700">

        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            Confirm dialog

            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
const dialogConfirm = await mw.confirm('Confirm your choice?').promise();
console.log(dialogConfirm);

            </pre>

            <x-filament::button color="gray" @click="const dialogConfirm = await mw.confirm('Confirm your choice?').promise();
            console.log(dialogConfirm) ">
                Confirm
            </x-filament::button>
        </h2>

        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            Alert dialog
            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
mw.alert('Hello world');


            </pre>

            <x-filament::button color="gray" @click="mw.alert('Hello world');">
                Alert
            </x-filament::button>
        </h2>

        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            Prompt dialog

            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
const dialogPrompt = await mw.prompt('Enter your name').promise();
console.log(dialogPrompt);

            </pre>

            <x-filament::button color="gray" @click="const dialogPrompt = await mw.prompt('Enter your name').promise();
            console.log(dialogPrompt)">
                Prompt
            </x-filament::button>
        </h2>


        <hr class="my-6 border-gray-200 dark:border-gray-700">

        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            Iframe auto height

            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
<code>
mw.tools.iframeAutoHeight(document.querySelector('.mw-dialog iframe'))
</code>
            </pre>


            <x-filament::button color="gray" @click="mw.dialogIframe({
                url: 'http://127.0.0.2/admin/kitchen-sink/',
                width:'90%',
                height:'auto',

            }); mw.tools.iframeAutoHeight(document.querySelector('.mw-dialog iframe'))">
                Dialog iframe
            </x-filament::button>


        </h2>

        <hr class="my-6 border-gray-200 dark:border-gray-700">


        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            Icon picker
            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
addEventListener('DOMContentLoaded', e => {
    mw.iconLoader()
    .addIconSet('iconsMindLine')
    .addIconSet('iconsMindSolid')
    .addIconSet('fontAwesome')
    .addIconSet('materialDesignIcons');

    document.querySelector('.my-button').addEventListener('click', async e => {
        const result = await mw.app.iconPicker.pickIcon('.icon-example').promise();
        console.log(result);
        console.log(result.icon);
    });
});


            </pre>

            <script>

                addEventListener('DOMContentLoaded', e => {
                    mw.iconLoader()
                    .addIconSet('iconsMindLine')
                    .addIconSet('iconsMindSolid')
                    .addIconSet('fontAwesome')
                    .addIconSet('materialDesignIcons');
                })

            </script>
            <x-filament::button color="gray"
                                @click="const picker = mw.app.iconPicker.pickIcon(document.querySelector('.icon-example')); await picker.promise()">
                <span class="icon-example mw-micon-Add-Bag"></span>
                Pick icon
            </x-filament::button>


        </h2>

        <hr class="my-6 border-gray-200 dark:border-gray-700">
        <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
            Schema Form
            <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">

const exampleData = [
    {
        type: 'text',
        name: 'firstName',
        value: 'John',
        label: 'Enter your name',
        placeholder: 'e.g.: Bill',
        required: false,
    },
    {
        type: 'email',
        name: 'mail',
        value: 'test@test.com',
        label: 'Enter your email',
        placeholder: 'contact@example.com',
        required: true
    },
    {
        type: 'select',
        name: 'car',
        value: 1,
        label: 'Enter your car',
        placeholder: 'Choose a car model',
        required: true,
        options: [
            {value: 1, title: 'Bentley'},
            {value: 2, title: 'Brabus'},
        ]
    },
    {
        type: 'checkbox',
        name: 'car2',
        value: 2,
        label: 'Enter your car',
        placeholder: 'Choose a car model',
        required: true,
        options: [
            {value: 1, title: 'Bentley'},
            {value: 2, title: 'Brabus'},
        ]
    },
];

const schemaForm = mw.schemaForm({
    target: node,
    data: exampleData
});

schemaForm.on('change', value => {
    console.log(value);
});

schemaForm.setValue([{name: 'car', value: 1}])

console.log(schemaForm.getValue());


            </pre>
        </h2>

            <hr class="my-6 border-gray-200 dark:border-gray-700">

            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                File picker
                <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
mw.filePickerDialog( (url) => {
    console.log(url)
});
        </pre>
                <x-filament::button color="gray" @click="mw.filePickerDialog( (url) => {
            console.log(url)
        });">
                    Pick file
                </x-filament::button>
            </h2>

            <hr class="my-6 border-gray-200 dark:border-gray-700">
            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                Url picker
                <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
var linkEditor = new mw.LinkEditor({
    mode: 'dialog',
});
var val = 'http://google.com'
if(val) {
    linkEditor.setValue(val);
}

linkEditor.promise().then(function (data){
    var modal = linkEditor.dialog;
    if(data) {


    }
    modal.remove();
});
        </pre>

                <div class="px-8">
                </div>
                <x-filament::button color="gray" @click="const linkEditor = new mw.LinkEditor({
            mode: 'dialog',
        });
        const val = 'http://google.com'
        if(val) {
            linkEditor.setValue(val);
        }

        linkEditor.promise().then(function (data){
            const modal = linkEditor.dialog;
            console.log(data);
            modal.remove();
        });">
                    Pick URL
                </x-filament::button>
            </h2>

            <hr class="my-6 border-gray-200 dark:border-gray-700">
            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                Tree

                <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
     mw.widget.tree('.my-element');
     mw.widget.tree(nodeElement);
        </pre>
                <div class="tree-example"></div>
                <div class="tree-example-context"></div>

                <script>
                    addEventListener('DOMContentLoaded', e => mw.widget.tree('.tree-example'))
                    addEventListener('DOMContentLoaded', e => mw.widget.tree('.tree-example-context', {
                        options: {
                            contextMenu: [

                                {
                                    title: 'Edit',
                                    icon: 'mdi mdi-pencil',
                                    action: function (element, data, menuitem) {

                                    }
                                },
                                {
                                    title: 'Delete',
                                    icon: 'mdi mdi-trash',
                                    action: function (element, data, menuitem) {
                                        element.remove()
                                    }
                                },

                            ]
                        }

                    }))
                </script>
            </h2>

            <hr class="my-6 border-gray-200 dark:border-gray-700">
            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                Editor

                <div class="richtext-example"></div>

                <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
            const editor = mw.richTextEditor({
                target: '.richtext-example'
            });
            const editor = mw.richTextEditor({
                target: document.querySelector('.some-selector')
            })

            editor.on('change', val => {
                console.log(val)
            })
        </pre>
                <script>
                    addEventListener('load', () => {
                        if (typeof tinymce !== 'undefined') {
                            const editor = mw.richTextEditor({
                                target: '.richtext-example'
                            });
                            editor.on('change', val => {
                                console.log(val)
                            })
                        }
                    })

                </script>
            </h2>

            <hr class="my-6 border-gray-200 dark:border-gray-700">

            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                Confirm dialog

                <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
    const dialogConfirm = await mw.confirm('Confirm your choice?').promise();
    console.log(dialogConfirm);

        </pre>

                <x-filament::button color="gray" @click="const dialogConfirm = await mw.confirm('Confirm your choice?').promise();
            console.log(dialogConfirm)  ">
                    Confirm
                </x-filament::button>
            </h2>

            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                Alert dialog

                <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
 mw.alert('Hello world');


        </pre>

                <x-filament::button color="gray" @click="mw.alert('Hello world');">
                    Alert
                </x-filament::button>
            </h2>

            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                Prompt dialog

                <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
const dialogPrompt = await mw.prompt('Enter your name').promise();
console.log(dialogPrompt);

        </pre>

                <x-filament::button color="gray" @click="const dialogPrompt = await mw.prompt('Enter your name').promise();
            console.log(dialogPrompt)">
                    Prompt
                </x-filament::button>
            </h2>


            <hr class="my-6 border-gray-200 dark:border-gray-700">


            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                Iframe auto height

                <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
<code>
    mw.tools.iframeAutoHeight(document.querySelector('.mw-dialog iframe'))
</code>
        </pre>


                <x-filament::button color="gray" @click="mw.dialogIframe({
            url: 'http://127.0.0.2/admin/kitchen-sink/',
            width:'90%',
            height:'auto',

        }); mw.tools.iframeAutoHeight(document.querySelector('.mw-dialog iframe'))">
                    Dialog iframe
                </x-filament::button>


            </h2>

            <hr class="my-6 border-gray-200 dark:border-gray-700">


            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                Icon picker
                <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
    addEventListener('DOMContentLoaded', e => {
        mw.iconLoader()
            .addIconSet('iconsMindLine')
            .addIconSet('iconsMindSolid')
            .addIconSet('fontAwesome')
            .addIconSet('materialDesignIcons');

        document.querySelector('.my-button').addEventListener('click', async e => {
            const result = await mw.app.iconPicker.pickIcon('.icon-example').promise();
            console.log(result);
            console.log(result.icon);
        });
    });


        </pre>

                <script>

                    addEventListener('DOMContentLoaded', e => {
                        mw.iconLoader()
                            .addIconSet('iconsMindLine')
                            .addIconSet('iconsMindSolid')
                            .addIconSet('fontAwesome')
                            .addIconSet('materialDesignIcons');
                    })

                </script>
                <x-filament::button color="gray"
                                    @click="const picker = mw.app.iconPicker.pickIcon(document.querySelector('.icon-example')); await picker.promise()">
                    <span class="icon-example mw-micon-Add-Bag"></span>
                    Pick icon
                </x-filament::button>


            </h2>

            <hr class="my-6 border-gray-200 dark:border-gray-700">
            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                Schema Form
                <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">

    const exampleData = [
            {
                type: 'text',
                name: 'firstName',
                value: 'John',
                label: 'Enter your name',
                placeholder: 'e.g.: Bill',
                required: false,
            },
            {
                type: 'email',
                name: 'mail',
                value: 'test@test.com',
                label: 'Enter your email',
                placeholder: 'contact@example.com',
                required: true
            },
            {
                type: 'select',
                name: 'car',
                value: 1,
                label: 'Enter your car',
                placeholder: 'Choose a car model',
                required: true,
                options: [
                    {value: 1, title: 'Bentley'},
                    {value: 2, title: 'Brabus'},
                ]
            },
            {
                type: 'checkbox',
                name: 'car2',
                value: 2,
                label: 'Enter your car',
                placeholder: 'Choose a car model',
                required: true,
                options: [
                    {value: 1, title: 'Bentley'},
                    {value: 2, title: 'Brabus'},
                ]
            },
        ];

                const schemaForm = mw.schemaForm({
                    target: node,
                    data: exampleData
                });

                schemaForm.on('change', value => {
                    console.log(value);
                });

                schemaForm.setValue([{name: 'car', value: 1}])

                console.log(schemaForm.getValue());


            </pre>
            </h2>


            <script>

                addEventListener('load', () => {
                    const exampleData = [
                        {
                            type: 'text',
                            name: 'firstName',
                            value: 'John',
                            label: 'Enter your name',
                            placeholder: 'e.g.: Bill',
                            required: false,
                        },
                        {
                            type: 'email',
                            name: 'mail',
                            value: 'test@test.com',
                            label: 'Enter your email',
                            placeholder: 'contact@example.com',
                            required: true
                        },
                        {
                            type: 'select',
                            name: 'car',
                            value: 1,
                            label: 'Enter your car',
                            placeholder: 'Choose a car model',
                            required: true,
                            options: [
                                {value: 1, title: 'Bentley'},
                                {value: 2, title: 'Brabus'},
                            ]
                        },
                        {
                            type: 'checkbox',
                            name: 'car2',
                            value: 2,
                            label: 'Enter your car',
                            placeholder: 'Choose a car model',
                            required: true,
                            options: [
                                {value: 1, title: 'Bentley'},
                                {value: 2, title: 'Brabus'},
                            ]
                        },
                    ];

                    const schemaForm = mw.schemaForm({
                        target: '#schema-form',
                        data: exampleData
                    });

                    schemaForm.on('change', value => {
                        console.log(value);
                    });

                    schemaForm.setValue([{name: 'car', value: 1}])

                    document.querySelector('#schema-form').addEventListener('submit', e => {
                        console.log(schemaForm.getValue());
                        e.preventDefault();
                    });

                    console.log(schemaForm.getValue());
                })

            </script>

            <form id="schema-form"></form>

            <br>

            <button type="submit" form="schema-form" class="btn">Submit</button>


            <hr class="my-6 border-gray-200 dark:border-gray-700">
            <h2 class="mt-6 mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Other elements with Tailwind classes</h2>
            <img src="{{ public_asset('modules/payment/img/paypal.png') }}"
                 style="height: 36px; width: 36px;"
                 class="max-w-none object-cover object-center ring-white dark:ring-gray-900  !object-contain">

            <br>

            <div style="display:none">

            </div>

        </div>
    </div>


</x-filament-panels::page>
