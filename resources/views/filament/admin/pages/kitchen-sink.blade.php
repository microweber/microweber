<x-filament-panels::page>

    <div class="grid grid-cols-4 gap-4">
        @foreach($this->getIcons() as $icon)
            <x-filament::button
                icon="mw-{{$icon}}"
                color="gray">
                mw-{{$icon}}
            </x-filament::button>
        @endforeach
    </div>

    {{$this->form}}


    <h1>
        Modal
        <pre>
    mw.dialog({
        content: 'dialog'
    })
        </pre>

        <x-filament::button color="gray"  @click="mw.dialog({
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
        <x-filament::button color="gray"  @click="mw.filePickerDialog( (url) => {
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
        <x-filament::button color="gray"  @click="const linkEditor = new mw.LinkEditor({
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
            const editor = new mw.richTextEditor({
                target: '.richtext-example'
            });
            const editor = new mw.richTextEditor({
                target: document.querySelector('.some-selector')
            })

            editor.on('change', val => {
                console.log(val)
            })
        </pre>
        <script>
            addEventListener('load', () => {
                const editor = new mw.richTextEditor({
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

        <x-filament::button color="gray"  @click="const dialogConfirm = await mw.confirm('Confirm your choice?').promise();
            console.log(dialogConfirm)  ">
            Confirm
        </x-filament::button>
    </h1>

    <h1>
        Alert dialog

        <pre>
 mw.alert('Hello world');


        </pre>

        <x-filament::button color="gray"  @click="mw.alert('Hello world');">
        Alert
        </x-filament::button>
    </h1>

    <h1>
        Prompt dialog

        <pre>
const dialogPrompt = await mw.prompt('Enter your name').promise();
console.log(dialogPrompt);

        </pre>

        <x-filament::button color="gray"  @click="const dialogPrompt = await mw.prompt('Enter your name').promise();
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




        <x-filament::button color="gray"  @click="mw.dialogIframe({
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
        <x-filament::button color="gray"  @click="const picker = mw.app.iconPicker.pickIcon(document.querySelector('.icon-example')); await picker.promise()">
            <span class="icon-example mw-micon-Add-Bag"></span>
            Pick icon
        </x-filament::button>


    </h1>

    <hr>

    <script>
        mw.button = (type, content)  => {
            return `<x-filament::button color="gray">${content}</x-filament::button>`;
        }
    </script>

</x-filament-panels::page>
