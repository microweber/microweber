<x-filament-panels::page>

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

        <script>
            addEventListener('DOMContentLoaded', e => mw.widget.tree('.tree-example'))
        </script>
    </h1>

    <hr>
    <h1>
        Editor
    </h1>

    <hr>

    <h1>
        Confirm dialog
    </h1>

    <hr>


    <h1>
        Iframe auto height
    </h1>

    <hr>


    <h1>
        Icon picker
        <pre>
            mw.app.iconPicker.pickIcon('.icon-example')
        </pre>
        <div class="icon-example"></div>

        <script>
            addEventListener('DOMContentLoaded', e => mw.app.iconPicker.pickIcon('.icon-example'))
        </script>
    </h1>

    <hr>

    <script>
        mw.button = (type, content)  => {
            return `<x-filament::button color="gray">${content}</x-filament::button>`;
        }
    </script>

</x-filament-panels::page>
