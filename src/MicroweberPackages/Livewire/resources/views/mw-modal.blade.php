<div>
    <style>
        #js-modal-livewire-ui {

        }
    </style>
    <script>
        let mwDialogLivewire = false;

        Livewire.on('activeModalComponentChanged', (data) => {

            console.log(data);

            mwDialogLivewire = mw.top().dialog({
                content: document.getElementById('js-modal-livewire-ui'),
                width: 900,
                overlay: true,
                overlayClose: true,
                draggableHandle: '#js-modal-livewire-ui-draggable-handle',
            });
            mwDialogLivewire.dialogHeader.style.display = 'none';
            mwDialogLivewire.dialogContainer.style.padding = '0px';

            let modalLivewireUiClose = document.getElementById('js-modal-livewire-ui-close');
            if (modalLivewireUiClose) {
                modalLivewireUiClose.addEventListener('click', function () {
                    mwDialogLivewire.remove();
                });
            }

            // document.getElementById('js-modal-livewire-ui').style.display = 'block';
        });
    </script>
    <div id="js-modal-livewire-ui">

        @forelse($components as $id => $component)
            <div wire:key="{{ $id }}">
                @livewire($component['name'], $component['attributes'], key($id))
            </div>
        @empty
        @endforelse
    </div>
</div>
