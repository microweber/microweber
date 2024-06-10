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
            New user
        </x-filament::button>
    </h1>

    <hr>
    <h1>
        Url picker
        <code>
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
        </code>
    </h1>

    <hr>
    <h1>
        Tree
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
    </h1>

    <hr>

    <script>
        mw.button = (type, content)  => {
            return `<x-filament::button color="gray">${content}</x-filament::button>`;
        }
    </script>

</x-filament-panels::page>
