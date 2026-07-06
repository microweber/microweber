{{--
    Filament Modal Teleport — JS (+ minimal CSS) injected via PanelsRenderHook::BODY_END
    Package: microweber-packages/filament-modal-teleport
--}}
<style data-mw-modal-teleport>
{{-- $cssPath is resolved by ModalTeleportPlugin (PHP __DIR__ is reliable);
     resolving it here with __DIR__ would point at the compiled-view cache dir. --}}
{!! file_get_contents($cssPath) !!}
</style>

<script data-mw-modal-teleport>
/**
 * Filament Modal Inert Fix  (package: microweber-filament-modal-teleport)
 * ──────────────────────────────────────────────────────────────────────
 * ROOT CAUSE (diagnosed in-browser, not guessed):
 * When a Filament modal opens, its focus trap marks the background content
 * `inert` so nothing behind the modal is interactive/focusable. Filament
 * normally teleports the modal to the top of the panel first, so it sits
 * OUTSIDE the inerted region. But form/schema-action modals (e.g. the
 * "Apply template" modal on AdminTemplatePage) render INLINE inside `.fi-main`
 * and are NOT teleported — so `.fi-main`, an ANCESTOR of the modal, gets
 * `inert`, and `inert` propagates down into the modal itself. The modal is
 * then visible but non-interactive: every click over it falls through to the
 * nearest non-inert ancestor (`.fi-main-ctn`), so it looks dead.
 *
 * Why not the "obvious" fixes (all tested and rejected):
 *   • CSS (opacity/transform/filter/contain/z-index/pointer-events/overflow)
 *     — `inert` is not CSS; none restore hit-testing.
 *   • DOM teleport of the modal out of `.fi-main` — DOES make it clickable but
 *     the Livewire component root (`.fi-page`) is nested INSIDE `.fi-main-ctn`,
 *     so moving the modal out severs its `wire:model`/`wire:submit` binding and
 *     the action silently no-ops (verified: template did not apply).
 *
 * THE FIX (in place — no DOM move, so Livewire keeps working):
 * whenever an element that CONTAINS an open modal is marked `inert`, clear the
 * `inert` on that element. The modal's own Alpine focus-trap still keeps focus
 * inside the modal, and its overlay still blocks background clicks, so removing
 * the ancestor `inert` only re-enables the modal itself. Self-heals via a
 * MutationObserver in case Filament re-applies `inert` on a later update.
 *
 * Works on any Filament v3/v4/v5 panel — `.fi-modal` / `.fi-main` are core
 * structural classes and `inert` is the standard focus-trap mechanism.
 */
(function () {
    'use strict';

    // An open modal is one whose window container is present and shown. We treat
    // any `.fi-modal` that carries `.fi-modal-open` OR contains a visible
    // `.fi-modal-window-ctn` as open.
    function containsOpenModal(el) {
        if (el.querySelector('.fi-modal.fi-modal-open')) { return true; }
        var ctns = el.querySelectorAll('.fi-modal .fi-modal-window-ctn');
        for (var i = 0; i < ctns.length; i++) {
            var s = window.getComputedStyle(ctns[i]);
            if (s.display !== 'none' && s.visibility !== 'hidden') { return true; }
        }
        return false;
    }

    // Clear `inert` on any element that is (wrongly) inerting an open modal
    // nested inside it. Scoped: only elements that actually contain an open
    // modal are touched, so legitimate `inert` (loading overlays, etc.) is left.
    function unInertOpenModals() {
        var inerted = document.querySelectorAll('[inert]');
        for (var i = 0; i < inerted.length; i++) {
            if (containsOpenModal(inerted[i])) {
                inerted[i].removeAttribute('inert');
            }
        }
    }

    // React to `inert` being (re)applied anywhere in the panel, and to modals
    // opening. attributeFilter keeps this cheap; a microtask debounce coalesces
    // bursts from a single Alpine/Livewire update.
    var pending = false;
    function schedule() {
        if (pending) { return; }
        pending = true;
        requestAnimationFrame(function () { pending = false; unInertOpenModals(); });
    }

    function start() {
        unInertOpenModals();
        new MutationObserver(schedule).observe(document.body, {
            subtree: true,
            attributes: true,
            attributeFilter: ['inert', 'class', 'style'],
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
}());
</script>
