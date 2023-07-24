<div>
    <style>
        #js-modal-livewire-ui {
            display: none;
        }
    </style>
    <script>
        let mwDialogLivewire = false;

        Livewire.on('activeModalComponentChanged', (data) => {

            console.log(data);

            let mwDialogLivewireSettings = {
                content: document.getElementById('js-modal-livewire-ui'),
            };

            if (data.modalSettings) {
                mwDialogLivewireSettings = Object.assign(mwDialogLivewireSettings, data.modalSettings);

                mwDialogLivewireSettings.draggableHandle = mwDialogLivewireSettings.draggableHandleSelector;
                delete mwDialogLivewireSettings.draggableHandleSelector;
            }

            mwDialogLivewire = mw.top().dialog(mwDialogLivewireSettings);
            mwDialogLivewire.dialogHeader.style.display = 'none';
            mwDialogLivewire.dialogContainer.style.padding = '0px';
            document.getElementById('js-modal-livewire-ui').style.display = 'block';

            if (mwDialogLivewireSettings.closeHandleSelector) {
                setTimeout(function() {
                    let modalLivewireUiClose = document.querySelector(mwDialogLivewireSettings.closeHandleSelector);
                    modalLivewireUiClose.addEventListener('click', function () {
                        mwDialogLivewire.remove();
                    });
                }, 500);
            }

            $(mwDialogLivewire).on('Remove', () => {
                Livewire.emit('destroyComponent', data.id);
                // document.getElementById('js-modal-livewire-ui').style.display = 'none';
            });

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
