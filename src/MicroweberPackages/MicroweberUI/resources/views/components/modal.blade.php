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
        mwDialogComponentUi{{ $id }}: false,
        show{{ $id }}: @entangle($attributes->wire('model')).defer,
    }"
    x-init="() => {

      el{{ $id }} = document.getElementById('modal-id-{{ $id }}')

      close{{ $id }} = document.getElementById('js-close-modal-{{ $id }}');
       if (close{{ $id }}) {
            close{{ $id }}.addEventListener('click', () => {
                show{{ $id }} = false;
            });
       }

        $watch('show{{ $id }}', value => {

            if (value) {

             el{{ $id }}.style.display = 'block';
             this.mwDialogComponentUi{{ $id }} = mw.dialog({
                content: el{{ $id }},
                onremove: () => {
                    show{{ $id }} = false;
                },
             });
             this.mwDialogComponentUi{{ $id }}.dialogHeader.style.display = 'none';
             this.mwDialogComponentUi{{ $id }}.dialogContainer.style.padding = '0px';

            } else {
               if (this.mwDialogComponentUi{{ $id }}) {
                this.mwDialogComponentUi{{ $id }}.remove();
                this.mwDialogComponentUi{{ $id }} = null;
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
