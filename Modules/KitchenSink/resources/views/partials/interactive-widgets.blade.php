{{-- ============================================================================
     Shared interactive widgets — Microweber JS APIs (mw.dialog, file/url/icon
     pickers, tree, rich-text editor, dialogs, schema form). Included by BOTH the
     KitchenSink page (Other tab) and the SamplePage (flat). One deduped copy of
     each demo (the tab previously listed every widget twice).
     ============================================================================ --}}
<div class="rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 space-y-4 text-gray-900 dark:text-gray-100">

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

    {{-- Modal --}}
    <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
        Modal
        <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
mw.dialog({
    content: 'dialog'
})
        </pre>
        <x-filament::button color="gray" @click="mw.dialog({ content: 'dialog' })">New user</x-filament::button>
    </h2>

    <hr class="my-6 border-gray-200 dark:border-gray-700">

    {{-- File picker --}}
    <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
        File picker
        <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
mw.filePickerDialog( (url) => {
    console.log(url)
});
        </pre>
        <x-filament::button color="gray" @click="mw.filePickerDialog( (url) => { console.log(url) });">Pick file</x-filament::button>
    </h2>

    <hr class="my-6 border-gray-200 dark:border-gray-700">

    {{-- Url picker --}}
    <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
        Url picker
        <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
var linkEditor = new mw.LinkEditor({ mode: 'dialog' });
linkEditor.setValue('http://google.com');
linkEditor.promise().then((data) => { linkEditor.dialog.remove(); });
        </pre>
        <x-filament::button color="gray" @click="const linkEditor = new mw.LinkEditor({ mode: 'dialog' }); linkEditor.setValue('http://google.com'); linkEditor.promise().then((data) => { console.log(data); linkEditor.dialog.remove(); });">Pick URL</x-filament::button>
    </h2>

    <hr class="my-6 border-gray-200 dark:border-gray-700">

    {{-- Tree --}}
    <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
        Tree
        <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
mw.widget.tree('.my-element');
        </pre>
        <div class="tree-example"></div>
        <div class="tree-example-context"></div>
        <script>
            addEventListener('DOMContentLoaded', function () {
                if (typeof mw === 'undefined' || !mw.widget || typeof mw.widget.tree !== 'function') return;
                mw.widget.tree('.tree-example');
                mw.widget.tree('.tree-example-context', {
                    options: {
                        contextMenu: [
                            { title: 'Edit', icon: 'mdi mdi-pencil', action: function (element, data, menuitem) {} },
                            { title: 'Delete', icon: 'mdi mdi-trash', action: function (element, data, menuitem) { element.remove(); } },
                        ]
                    }
                });
            });
        </script>
    </h2>

    <hr class="my-6 border-gray-200 dark:border-gray-700">

    {{-- Rich-text editor --}}
    <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
        Editor
        <div class="richtext-example"></div>
        <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
const editor = mw.richTextEditor({ target: '.richtext-example' });
editor.on('change', val => { console.log(val) });
        </pre>
        <script>
            addEventListener('load', function () {
                if (typeof mw === 'undefined' || typeof mw.richTextEditor !== 'function') return;
                if (typeof tinymce === 'undefined') return;
                var editor = mw.richTextEditor({ target: '.richtext-example' });
                editor.on('change', function (val) { console.log(val); });
            });
        </script>
    </h2>

    <hr class="my-6 border-gray-200 dark:border-gray-700">

    {{-- Dialogs: confirm / alert / prompt --}}
    <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Dialogs</h2>
    <div class="flex flex-wrap items-center gap-3">
        <x-filament::button color="gray" @click="const r = await mw.confirm('Confirm your choice?').promise(); console.log(r)">Confirm</x-filament::button>
        <x-filament::button color="gray" @click="mw.alert('Hello world');">Alert</x-filament::button>
        <x-filament::button color="gray" @click="const r = await mw.prompt('Enter your name').promise(); console.log(r)">Prompt</x-filament::button>
    </div>

    <hr class="my-6 border-gray-200 dark:border-gray-700">

    {{-- Iframe auto height --}}
    <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
        Iframe auto height
        <pre class="my-2 p-3 text-xs rounded-md bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 overflow-x-auto whitespace-pre-wrap break-words">
mw.tools.iframeAutoHeight(document.querySelector('.mw-dialog iframe'))
        </pre>
        <x-filament::button color="gray" @click="mw.dialogIframe({ url: window.location.href, width:'90%', height:'auto' }); mw.tools.iframeAutoHeight(document.querySelector('.mw-dialog iframe'))">Dialog iframe</x-filament::button>
    </h2>

    <hr class="my-6 border-gray-200 dark:border-gray-700">

    {{-- Icon picker --}}
    <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">
        Icon picker
        <script>
            addEventListener('DOMContentLoaded', function () {
                if (typeof mw === 'undefined' || typeof mw.iconLoader !== 'function') return;
                mw.iconLoader()
                    .addIconSet('iconsMindLine')
                    .addIconSet('iconsMindSolid')
                    .addIconSet('fontAwesome')
                    .addIconSet('materialDesignIcons');
            });
        </script>
        <x-filament::button color="gray" @click="const picker = mw.app.iconPicker.pickIcon(document.querySelector('.icon-example')); await picker.promise()">
            <span class="icon-example mw-micon-Add-Bag"></span>
            Pick icon
        </x-filament::button>
    </h2>

    <hr class="my-6 border-gray-200 dark:border-gray-700">

    {{-- Schema form (vanilla mw.schemaForm) --}}
    <h2 class="mb-3 text-base font-semibold tracking-tight text-gray-900 dark:text-white">Schema form</h2>
    <form id="schema-form"></form>
    <div class="mt-3">
        <button type="submit" form="schema-form" class="btn">Submit</button>
    </div>
    <script>
        addEventListener('load', function () {
            if (typeof mw === 'undefined' || typeof mw.schemaForm !== 'function') return;
            if (!document.querySelector('#schema-form')) return;
            var exampleData = [
                { type: 'text', name: 'firstName', value: 'John', label: 'Enter your name', placeholder: 'e.g.: Bill', required: false },
                { type: 'email', name: 'mail', value: 'test@test.com', label: 'Enter your email', placeholder: 'contact@example.com', required: true },
                { type: 'select', name: 'car', value: 1, label: 'Enter your car', placeholder: 'Choose a car model', required: true, options: [ {value: 1, title: 'Bentley'}, {value: 2, title: 'Brabus'} ] },
                { type: 'checkbox', name: 'car2', value: 2, label: 'Enter your car', placeholder: 'Choose a car model', required: true, options: [ {value: 1, title: 'Bentley'}, {value: 2, title: 'Brabus'} ] },
            ];
            var schemaForm = mw.schemaForm({ target: '#schema-form', data: exampleData });
            schemaForm.on('change', function (value) { console.log(value); });
            schemaForm.setValue([{name: 'car', value: 1}]);
            document.querySelector('#schema-form').addEventListener('submit', function (e) { e.preventDefault(); console.log(schemaForm.getValue()); });
        });
    </script>

</div>
