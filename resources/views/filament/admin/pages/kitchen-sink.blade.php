<x-filament-panels::page>

    <div id="filepick1"></div>

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


    <div x-data="{ activeTab: 'form' }">

        <x-filament::tabs>
            <x-filament::tabs.item
                alpine-active="activeTab === 'form'"
                x-on:click="activeTab = 'form'"
            >
                Form
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
