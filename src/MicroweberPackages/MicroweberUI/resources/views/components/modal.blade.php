@props(['id', 'maxWidth'])



<!-- Modal -->
<div wire:ignore >

    @php
        $id = $id ?? md5($attributes->wire('model'));


        $maxWidth = [
            'sm' => ' modal-sm',
            'md' => '',
            'lg' => ' modal-lg',
            'xl' => ' modal-xl',
        ][$maxWidth ?? 'md'];
    @endphp


 <script>
     if(typeof window.createModalDialogLivewire{{ $id }} === 'undefined') {
         window.createModalDialogLivewire{{ $id }} = function (id) {
             var config = {};
             var el = document.getElementById('modal-id-' + id);
             var close = document.getElementById('js-close-modal-' + id);
             var hasModal = false;

             config['show' + id] = @entangle($attributes->wire('model'));

             return {
                 ...config,
                 show: 'show' + id,
                 init: function () {

                     if (close) {
                         close.addEventListener('click', () => {
                             this.show = false;
                         });
                     }

                     this.$watch(this.show, (value) => {
                         if (value) {
                             el.style.display = 'block';
                             this['mwDialogComponentUi' + id] = mw.dialog({
                                 content: el,
                                 id: 'mwDialogComponentUi' + id,
                                 onremove: () => {
                                     this.show = false;
                                 },
                             });

                             this['mwDialogComponentUi' + id].dialogHeader.style.display = 'none';
                             this['mwDialogComponentUi' + id].dialogContainer.style.padding = '0px';
                         } else {
                             if (this['mwDialogComponentUi' + id]) {
                                 this['mwDialogComponentUi' + id].remove();
                                 this['mwDialogComponentUi' + id] = null;
                             }

                             this.show = false;
                         }
                     });
                 },
             };


         };
     }
 </script>

<div x-data="createModalDialogLivewire{{ $id }}('{{ $id }}')"
     x-show="show{{ $id }}"
     x-init="init()"


    wire:ignore.self

     style="display:none"

    tabindex="-1"
    id="modal-id-{{ $id }}"

    x-ref="modal-id-{{ $id }}"

>
    <div class="mw-modal">
        <div class="mw-modal-dialog{{ $maxWidth }}">
            {{ $slot }}


        </div>
    </div>

</div>
</div>
