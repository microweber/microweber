<div>
    <script>{!! file_get_contents(resource_path() . '/views/vendor/livewire-ui-modal/bootstrap-modal.js') !!}</script>

    <div

        x-data="LivewireUIBootstrapModal()"
        x-init="init()"
        x-on:close.stop="setShowPropertyTo(false)"
        x-on:keydown.escape.window="closeModalOnEscape()"
        x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
        x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
        x-show="show"

        class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @forelse($components as $id => $component)
                        <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}" wire:key="{{ $id }}">
                            @livewire($component['name'], $component['attributes'], key($id))
                        </div>
                    @empty
                    @endforelse
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="setShowPropertyTo(false)">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="waw">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</div>
