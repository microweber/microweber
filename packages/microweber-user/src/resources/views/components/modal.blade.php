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
<div
    x-data="{
        show: @entangle($attributes->wire('model')).defer,
    }"
    x-init="() => {

        let el = document.querySelector('#modal-id-{{ $id }}')

        let modal = new bootstrap.Modal(el);

        $watch('show', value => {
            if (value) {
                modal.show()
            } else {
                modal.hide()
            }
        });

        el.addEventListener('hide.bs.modal', function (event) {
          show = false
        })
    }"
    wire:ignore.self
    class="modal fade"
    tabindex="-1"
    id="modal-id-{{ $id }}"
    aria-labelledby="modal-id-{{ $id }}"
    aria-hidden="true"
    x-ref="modal-id-{{ $id }}"
>
    <div class="modal-dialog{{ $maxWidth }}">
        {{ $slot }}
    </div>
</div>
