<div>
    <script>
        let mwDialogLivewire = false;

        Livewire.on('activeModalComponentChanged', (data) => {

            let mwDialogLivewireSettings = {
                content: document.getElementById('js-modal-livewire-ui'),
                onremove: () => {
                    Livewire.emit('destroyComponent', data.id);
                },
            };

            if (data.modalSettings) {
                mwDialogLivewireSettings = Object.assign(mwDialogLivewireSettings, data.modalSettings);

                mwDialogLivewireSettings.draggableHandle = mwDialogLivewireSettings.draggableHandleSelector;
                delete mwDialogLivewireSettings.draggableHandleSelector;
            }

            mwDialogLivewire = mw.top().dialog(mwDialogLivewireSettings);
            mwDialogLivewire.dialogHeader.style.display = 'none';
            mwDialogLivewire.dialogContainer.style.padding = '0px';

            if (mwDialogLivewireSettings.closeHandleSelector) {
                setTimeout(() => {
                    let modalLivewireUiClose = false;
                    if (self != top) {
                        modalLivewireUiClose = mw.top().$(mwDialogLivewireSettings.closeHandleSelector)[0];
                    } else {
                        modalLivewireUiClose = document.querySelector(mwDialogLivewireSettings.closeHandleSelector);
                    }

                    if (modalLivewireUiClose) {
                        modalLivewireUiClose.addEventListener('click', function () {
                            mwDialogLivewire.remove();
                        });
                    }
                }, 500);
            }

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
