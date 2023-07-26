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
        show: @entangle($attributes->wire('model')).defer,
    }"
    x-init="() => {

        let mwDialogComponent = false;
        let el = document.getElementById('modal-id-{{ $id }}')

        $watch('show', value => {
            if (value) {
             el.style.display = 'block';
             mwDialogComponent = mw.top().dialog({
                content: el,
                overlay: true,
                overlayClose: true,
                onremove: () => {
                    show = false;
                },
             });
             mwDialogComponent.dialogHeader.style.display = 'none';
             mwDialogComponent.dialogContainer.style.padding = '0px';

            } else {
               if (mwDialogComponent) {
                mwDialogComponent.remove();
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
    <div class="modal-dialog{{ $maxWidth }}">
        {{ $slot }}
    </div>
</div>
