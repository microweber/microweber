@props(['id', 'maxWidth'])

@php
$id = $id ?? md5($attributes->wire('model'));

$maxWidth = [
    'sm' => ' modal-sm',
    'md' => '',
    'lg' => ' modal-lg',
    'xl' => ' modal-xl',
][$maxWidth ?? 'md'];
@endphp

<!-- Modal -->

<div x-data="{
        mwDialogComponentUi: false,
        show: @entangle($attributes->wire('model')).defer,
    }"
    x-init="() => {

      el = document.getElementById('modal-id-{{ $id }}')
      
      close = document.getElementById('js-close-modal-{{ $id }}');
       if (close) {
            close.addEventListener('click', () => {
                show = false;
            });
       }

        $watch('show', value => {

            if (value) {

             el.style.display = 'block';
             this.mwDialogComponentUi = mw.top().dialog({
                content: el,
                overlay: true,
                overlayClose: true,
                onremove: () => {
                    show = false;
                },
             });
             this.mwDialogComponentUi.dialogHeader.style.display = 'none';
             this.mwDialogComponentUi.dialogContainer.style.padding = '0px';

            } else {
               if (this.mwDialogComponentUi) {
                this.mwDialogComponentUi.remove();
              }
            }
       });

    }"

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
