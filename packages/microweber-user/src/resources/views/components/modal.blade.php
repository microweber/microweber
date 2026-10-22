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

@once
    {{-- Fallback styling for contexts without Bootstrap CSS/JS (e.g. the
         Filament panels). When Bootstrap is present this class is never added,
         so the public site keeps its native Bootstrap modal. --}}
    <style>
        .mw-user-modal-backdrop { position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1050; }
        body.mw-user-modal-open { overflow: hidden; }
        .modal.mw-user-modal-fallback { position: fixed; inset: 0; z-index: 1055; display: block; overflow-y: auto; padding: 1.75rem 1rem; }
        .modal.mw-user-modal-fallback .modal-dialog { max-width: 500px; margin: 1.75rem auto; }
        .modal.mw-user-modal-fallback .mw-modal-content { background: #fff; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,.2); overflow: hidden; }
        .modal.mw-user-modal-fallback .mw-modal-header,
        .modal.mw-user-modal-fallback .mw-modal-footer { display: flex; align-items: center; gap: .5rem; padding: 1rem 1.25rem; }
        .modal.mw-user-modal-fallback .mw-modal-header { justify-content: space-between; border-bottom: 1px solid #eee; }
        .modal.mw-user-modal-fallback .mw-modal-footer { justify-content: flex-end; border-top: 1px solid #eee; }
        .modal.mw-user-modal-fallback .mw-modal-body { padding: 1.25rem; }
        .modal.mw-user-modal-fallback .mw-modal-title { margin: 0; font-size: 1.1rem; font-weight: 600; }
    </style>
@endonce

<!-- Modal -->
<div
    x-data="{
        show: @entangle($attributes->wire('model')).defer,
    }"
    x-init="() => {

        let el = document.querySelector('#modal-id-{{ $id }}')

        // Use Bootstrap's modal when it is available (public site); otherwise
        // fall back to a self-contained modal so the component also works where
        // Bootstrap JS is not loaded (Filament panels) — previously this threw
        // 'ReferenceError: bootstrap is not defined' on every render.
        let hasBootstrap = (typeof bootstrap !== 'undefined' && bootstrap && bootstrap.Modal)
        let modal = hasBootstrap ? new bootstrap.Modal(el) : null
        let backdrop = null

        let openFallback = () => {
            el.classList.add('show', 'mw-user-modal-fallback')
            el.style.display = 'block'
            el.removeAttribute('aria-hidden')
            if (!backdrop) {
                backdrop = document.createElement('div')
                backdrop.className = 'mw-user-modal-backdrop'
                backdrop.addEventListener('click', () => { show = false })
                document.body.appendChild(backdrop)
            }
            document.body.classList.add('mw-user-modal-open')
        }

        let closeFallback = () => {
            el.classList.remove('show', 'mw-user-modal-fallback')
            el.style.display = 'none'
            el.setAttribute('aria-hidden', 'true')
            if (backdrop) { backdrop.remove(); backdrop = null }
            document.body.classList.remove('mw-user-modal-open')
        }

        $watch('show', value => {
            if (hasBootstrap) {
                value ? modal.show() : modal.hide()
            } else {
                value ? openFallback() : closeFallback()
            }
        });

        if (hasBootstrap) {
            el.addEventListener('hide.bs.modal', function (event) {
              show = false
            })
        } else {
            window.addEventListener('keydown', function (event) {
              if (event.key === 'Escape') { show = false }
            })
        }
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
