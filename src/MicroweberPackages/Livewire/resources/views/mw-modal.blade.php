<div>
    <script>
        Livewire.on('closeModal', (force = false, skipPreviousModals = 0, destroySkipped = false) => {
            let openedModals = document.querySelectorAll('.js-modal-livewire');
            for (let i = 0; i < openedModals.length; i++) {
                let openedModalId = openedModals[i].getAttribute('wire:key');
                let modal = document.getElementById("js-modal-livewire-id-" + openedModalId);
                modal.style.display = "block";
                Livewire.emit('destroyComponent', openedModalId);
            }
        });
        Livewire.on('activeModalComponentChanged', (data) => {
            let modal = document.getElementById("js-modal-livewire-id-" + data.id);
            modal.style.display = "block";
        });
    </script>
    <style>

        .js-modal-livewire {
            display: none;
            position: fixed;
            z-index: 1100;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .js-modal-livewire-content {
            margin: auto;
            background-color: #fff;
            width: 800px;
            max-height: calc(100% - 100px);
            overflow: auto;
        }
    </style>
    <div>
        @forelse($components as $id => $component)
            <div class="js-modal-livewire" id="js-modal-livewire-id-{{ $id }}" wire:key="{{ $id }}">
                <div class="js-modal-livewire-content">
                    @livewire($component['name'], $component['attributes'], key($id))
                </div>
            </div>
        @empty
        @endforelse
    </div>
</div>
