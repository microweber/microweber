<div>

    <div wire:ignore>
        <style>
            .js-modal-livewire.active {
                display: block;
            }

            .js-modal-livewire {
                display: none;
                position: fixed;
                z-index: 1100;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0, 0, 0);
                background-color: rgba(0, 0, 0, 0.4);
            }

            .js-modal-livewire-content {
                margin: auto;
                background-color: #fff;
                width: 100%;
                overflow: auto;
            }

            @media only screen and (min-width: 600px) {
                .js-modal-livewire {
                    padding-top: 100px;
                }

                .js-modal-livewire-content {
                    max-width: 480px;
                    max-height: calc(100vh - 100px);
                }
            }

            @media only screen and (min-width: 768px) {
                .js-modal-livewire {
                    padding-top: 8%;
                }

                .js-modal-livewire-content {
                    max-height: calc(100vh - 100px);
                    overflow: auto;
                }
            }
        </style>
    </div>


    <div id="modal-holder-livewire">


        @if($components)
            @foreach($components as $id => $component)
                <div class="js-modal-livewire {{$activeComponent ? 'active' : ''}}" id="js-modal-livewire-id-{{ $id }}"
                     wire:key="{{ $id }}">
                    <div class="js-modal-livewire-content">
                        @livewire($component['name'], $component['attributes'], key($id))
                    </div>
                </div>

            @endforeach
        @endif
    </div>


    <div wire:ignore>
        <script>

            document.addEventListener('livewire:init', function () {


                Livewire.on('closeMwTopDialogIframe', () => {
                    let dialog = mw.top().dialog.get('#mw-livewire-component-iframe');
                    if (dialog) {
                        dialog.remove();
                    }
                });

                Livewire.on('openMwTopDialogIframe', (componentName, jsonParams) => {

                    let params = [];
                    params.componentName = componentName;
                    if (jsonParams) {
                        jsonParams.componentName = componentName;
                        params = new URLSearchParams(jsonParams).toString();
                    }

                    let mwNativeModal = mw.top().dialogIframe({
                        url: "{{ route('admin.livewire.components.render-component') }}?" + params,
                        width: 900,
                        height: 900,
                        id: 'mw-livewire-component-iframe',
                        skin: 'square_clean',
                        center: false,
                        resize: true,
                        overlayClose: true,
                        draggable: true
                    });
                    mwNativeModal.dialogHeader.style.display = 'none';
                });

                // simple modal
                Livewire.on('closeModal', (force = false, skipPreviousModals = 0, destroySkipped = false) => {
                    let openedModals = document.querySelectorAll('.js-modal-livewire');
                    for (let i = 0; i < openedModals.length; i++) {
                        let openedModalId = openedModals[i].getAttribute('wire:key');
                        let modal = document.getElementById("js-modal-livewire-id-" + openedModalId);
                        if (modal) {
                            modal.style.display = "none";
                            //Livewire.dispatch('destroyComponent', ['id', openedModalId]);
                        }
                    }
                });


                /*  Livewire.on('activeModalComponentChanged', (data) => {




                      let modal = document.getElementById("js-modal-livewire-id-" + data.id);

                      if(!modal) {
                         console.log('Modal not found', data);
                      }

                      if (modal) {
                          modal.style.display = "block";
                          if (data.modalSettings) {
                              modal.querySelector('.js-modal-livewire-content').style.width = data.modalSettings.width;
                          }
                      }
                  });*/

            });

        </script>

    </div>
</div>
