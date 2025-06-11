<x-filament-panels::page>

    <div id="filepick1"></div>

    <script>
        var fm = mw.FileManager({
            element: '#filepick1',
            canSelectFolder: false,

            stickyHeader: true,

            type: '*',


            selectable: true,
            multiselect: true,
            stickyHeader: false,
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
            <div>
                Icons path: /src/MicroweberPackages/Admin/resources/mw-svg/
            </div>
            <div class="grid grid-cols-4 gap-4 mt-2">
                @foreach($this->getIcons() as $icon)
                    <div
                        class="flex flex-col bg-blue-500/10 dark:bg-white/5 transition duration-150 group-hover:bg-white rounded-xl p-4">
                        <div class="flex items-center justify-center ">
                            @svg('mw-'.$icon, "h-12 w-12 text-black/90 dark:text-white")
                        </div>
                        <div class="w-full text-center mt-2">
                            mw-{{$icon}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <div x-show="activeTab == 'form'" class="mt-4">
            {{$this->form}}
        </div>

        <div x-show="activeTab == 'other'" class="mt-4">
            <h1>
                Modal
                <pre>
    mw.dialog({
        content: 'dialog'
    })
        </pre>

                <x-filament::button color="gray" @click="mw.dialog({
                        content: 'dialog'
                    })">
                    New user
                </x-filament::button>
            </h1>

            <hr>

            <h1>
                File picker
                <pre>
mw.filePickerDialog( (url) => {
    console.log(url)
});
        </pre>
                <x-filament::button color="gray" @click="mw.filePickerDialog( (url) => {
            console.log(url)
        });">
                    Pick file
                </x-filament::button>
            </h1>

            <hr>
            <h1>
                Url picker
                <pre>
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
            </h1>

            <hr>
            <h1>
                Tree

                <pre>
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
            </h1>

            <hr>
            <h1>
                Editor

                <div class="richtext-example"></div>

                <pre>
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
                        const editor = mw.richTextEditor({
                            target: '.richtext-example'
                        });

                        editor.on('change', val => {
                            console.log(val)
                        })
                    })

                </script>
            </h1>

            <hr>

            <h1>
                Confirm dialog

                <pre>
    const dialogConfirm = await mw.confirm('Confirm your choice?').promise();
    console.log(dialogConfirm);

        </pre>

                <x-filament::button color="gray" @click="const dialogConfirm = await mw.confirm('Confirm your choice?').promise();
            console.log(dialogConfirm)  ">
                    Confirm
                </x-filament::button>
            </h1>

            <h1>
                Alert dialog

                <pre>
 mw.alert('Hello world');


        </pre>

                <x-filament::button color="gray" @click="mw.alert('Hello world');">
                    Alert
                </x-filament::button>
            </h1>

            <h1>
                Prompt dialog

                <pre>
const dialogPrompt = await mw.prompt('Enter your name').promise();
console.log(dialogPrompt);

        </pre>

                <x-filament::button color="gray" @click="const dialogPrompt = await mw.prompt('Enter your name').promise();
            console.log(dialogPrompt)">
                    Prompt
                </x-filament::button>
            </h1>


            <hr>


            <h1>
                Iframe auto height

                <pre>
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


            </h1>

            <hr>


            <h1>
                Icon picker
                <pre>
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


            </h1>

            <hr>
            <h1>
                Schema Form
                <pre>

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
            </h1>


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


            <hr>
            <h2>Other elements with Tailwind classes</h2>
            <img src="{{ public_asset('vendor/microweber-packages/payment/img/paypal.png') }}"
                 style="height: 36px; width: 36px;"
                 class="max-w-none object-cover object-center ring-white dark:ring-gray-900  !object-contain">

            <br>

            <div style="display:none">

            </div>

        </div>
    </div>


</x-filament-panels::page>
