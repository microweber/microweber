<script>
/*
 * "Save & Publish" button + 30s draft auto-save.
 *
 * The visible label is "Save & Publish" so the user knows the
 * action publishes changes to the live site immediately rather
 * than expecting a separate publish step. The aria-label still
 * references the Ctrl+S shortcut.
 *
 * The 30-second draft auto-save:
 *   - Only fires AFTER the canvas is loaded (no auto-save on a
 *     blank/loading editor)
 *   - Only fires if `_dirty` is set (user has edited since the
 *     last save)
 *   - Calls save() in SILENT mode so the success toast doesn't
 *     spam every 30 seconds
 *   - Failure toast still fires so the user is alerted to lost
 *     work
 *
 * The dirty flag is tracked via canvas activity events: editor
 * input, paste, and Mutation events all set _dirty = true. Each
 * successful save resets _dirty = false.
 *
 * NOVICE #2 (task-2026-05-13-899d57) — two novice-customer fixes
 * grafted onto save():
 *
 *   1. ONE-TIME confirmation dialog before manual save & publish.
 *      A first-time site owner ("Brenda") who clicks the big black
 *      button expecting Word-style "save the draft" gets a clear
 *      warning that this publishes to the live internet right now.
 *      `mw-publish-confirmed` flag in localStorage suppresses the
 *      dialog forever after the first acknowledgement, so it
 *      doesn't annoy power users. Silent auto-saves and undo-
 *      triggered saves bypass the confirmation outright.
 *
 *   2. "Undo last publish" button in the success toast. Captures
 *      the canvas state at canvas-load (= the server's pre-edit
 *      state) and after every successful save. When the user
 *      clicks Undo, the previously-captured snapshot is reinstated
 *      to the canvas via innerHTML replacement, the dirty flag is
 *      set, and save({fromUndo: true}) re-publishes the rolled-
 *      back state. The whole thing is best-effort: if iframe
 *      access throws (cross-origin guard, race during canvas
 *      reload, etc.) the undo flow degrades to an error toast
 *      asking the user to reload the editor manually — never
 *      worse than not having the button at all.
 */

const AUTO_SAVE_INTERVAL_MS = 30000;
const PUBLISH_CONFIRM_FLAG_KEY = 'mw-publish-confirmed';
const PUBLISH_CONFIRM_PROMPT = (
    'Save & Publish makes your changes visible to everyone on the live site right now.\n\n'
    + 'Continue? (You will only see this message once.)'
);
const UNDO_CONFIRM_PROMPT = (
    'Restore the previous version?\n\n'
    + 'Your latest changes will be replaced with what was on the site before your most recent save, then re-published.'
);

export default {
    computed: {
        // AI-988 / task-2026-06-04-le988 — platform-aware save shortcut
        // label. macOS editors press Cmd (⌘); Windows/Linux press Ctrl.
        // Drives both the visible tooltip and the screen-reader
        // aria-label so the announced shortcut matches the key the user
        // must actually press. The keydown handler below accepts BOTH
        // ctrlKey and metaKey so the shortcut fires on every platform.
        saveShortcutLabel() {
            var isMac = false;
            try {
                var p = (navigator.userAgentData && navigator.userAgentData.platform)
                    || navigator.platform || navigator.userAgent || '';
                isMac = /Mac|iPod|iPhone|iPad/i.test(p);
            } catch (_) { /* default to Ctrl */ }
            return isMac ? '⌘S' : 'Ctrl+S';
        }
    },
    data() {
        return {
            _dirty: false,
            _autoSaveTimer: null,
            _autoSaveActive: false,
            // AI-988 — retained reference to the document-level keydown
            // handler so beforeUnmount can detach it (previously leaked).
            _mwSaveKeydownListener: null,
            // NOVICE #2: canvas HTML snapshot taken on canvas-load
            // and refreshed at the END of every successful save —
            // captures "the state the server had immediately BEFORE
            // the next save will commit", which is what an Undo
            // button needs to roll back to.
            _undoSnapshot: null,
        };
    },
    methods: {

        publishConfirmed() {
            // Returns true if the user has already acknowledged the
            // one-time publish warning, OR if localStorage is
            // unavailable (private mode, quota exceeded, etc.) — in
            // the unavailable case we degrade gracefully and skip
            // the prompt rather than block the user from saving.
            try {
                if (typeof window === 'undefined' || !window.localStorage) return true;
                return window.localStorage.getItem(PUBLISH_CONFIRM_FLAG_KEY) === '1';
            } catch (_) {
                return true;
            }
        },

        rememberPublishConfirmed() {
            try {
                if (typeof window === 'undefined' || !window.localStorage) return;
                window.localStorage.setItem(PUBLISH_CONFIRM_FLAG_KEY, '1');
            } catch (_) { /* no-op */ }
        },

        captureCanvasSnapshot() {
            try {
                var canvasWin = mw.app.canvas.getWindow();
                if (canvasWin && canvasWin.document && canvasWin.document.body) {
                    return canvasWin.document.body.innerHTML;
                }
            } catch (_) { /* no-op */ }
            return null;
        },

        save(options) {
            options = options || {};
            var silent = options.silent === true;
            var fromUndo = options.fromUndo === true;
            var self = this;

            // NOVICE #2 step 1 — one-time confirmation. Only fires
            // on user-initiated manual saves (not silent auto-saves,
            // not undo-triggered re-saves).
            if (!silent && !fromUndo && !this.publishConfirmed()) {
                var ok = false;
                try { ok = window.confirm(PUBLISH_CONFIRM_PROMPT); } catch (_) { ok = true; }
                if (!ok) return;
                this.rememberPublishConfirmed();
            }

            var btn = document.getElementById('save-button');
            if (!btn) return;
            btn.classList.add('btn-loading');
            btn.disabled = true;
            var mountedActionValidationFailed = false;
            var onMountedActionValidationFailed = function () {
                mountedActionValidationFailed = true;
            };
            window.addEventListener('liveEditMountedActionValidationFailed', onMountedActionValidationFailed, { once: true });

            // If a Filament module-settings slideOver is open (e.g.
            // "Add New Post" / "Add Page" / module settings form),
            // submit it as part of the page save. The user expects the
            // live-edit SAVE button to be a one-click "save everything
            // visible" rather than only saving the page DOM. Dispatch
            // a window event that the iframe-page Alpine listener
            // picks up and forwards to $wire.callMountedAction(); the
            // Filament action's own submit handler then runs the
            // form's create/update logic.
            try {
                window.dispatchEvent(new Event('liveEditSaveCallMountedAction'));
            } catch (_) { /* no-op */ }

            var canvasWindow = mw.app.canvas.getWindow();
            if(canvasWindow.mw && typeof canvasWindow.mw.drag === 'undefined'){
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                if (!silent) {
                    mw.notification.error('Something went wrong with saving the page.',7500);
                }
                return;
            }
            var saved = canvasWindow.mw.drag.save();

            var finishSave = function (result) {
                setTimeout(function () {
                    btn.classList.remove('btn-loading');
                    btn.disabled = false;
                    window.removeEventListener('liveEditMountedActionValidationFailed', onMountedActionValidationFailed);

                    if (mountedActionValidationFailed) {
                        if (!silent) {
                            mw.notification.error('Please fix the highlighted required field before saving.', 7500);
                        }
                        return;
                    }

                    if (result === 'success') {
                        self.$data._dirty = false;

                        // NOVICE #2 step 2 — the snapshot to undo TO
                        // is the one captured BEFORE this save fired
                        // (= what the server had pre-save). After we
                        // surface the undo button we update the
                        // snapshot to the just-saved state so the
                        // NEXT save's undo points one step further
                        // back in the publish history.
                        var prevSnapshot = self.$data._undoSnapshot;
                        var freshSnapshot = self.captureCanvasSnapshot();
                        self.$data._undoSnapshot = freshSnapshot;

                        if (silent) {
                            // No success toast on silent draft saves
                            // — the user is editing, they don't want
                            // a status toast every 30s.
                            return;
                        }
                        if (fromUndo) {
                            // The user just clicked Undo, the rolled-
                            // back state is now published. Confirm
                            // unambiguously without offering another
                            // Undo (which would yo-yo state).
                            mw.notification.success('Reverted to the previous version and re-published.', 5000);
                            return;
                        }
                        // Include a real SVG check glyph so the
                        // page-saved toast reads as a definitive
                        // confirmation, not just a status sentence
                        // the user might miss.
                        var canOfferUndo = typeof prevSnapshot === 'string' && prevSnapshot.length > 0;
                        var savedToastHtml = '<span class="mw-notification-saved-toast">'
                            + '<svg xmlns="http://www.w3.org/2000/svg" class="mw-notification-saved-toast__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
                            + '<polyline points="20 6 9 17 4 12"></polyline></svg> '
                            + '<span class="mw-notification-saved-toast__label">Saved &amp; published</span>';
                        if (canOfferUndo) {
                            // Dispatch a window event on click — the
                            // component listens for it in mounted()
                            // and calls undoLastPublish(). Using an
                            // event (rather than a global window.fn)
                            // keeps the public namespace clean and
                            // survives component teardown gracefully.
                            savedToastHtml += ' <button type="button" class="mw-notification-saved-toast__undo" '
                                + 'onclick="try{window.dispatchEvent(new CustomEvent(\'liveEditUndoLastPublish\'));}catch(e){}" '
                                + 'aria-label="Undo last publish — restore the previous version">Undo</button>';
                        }
                        savedToastHtml += '</span>';
                        mw.notification.success(savedToastHtml, 7500);
                        return;
                    }

                    // Failure path — toast EVEN on silent auto-save
                    // so the user is alerted to lost work.
                    mw.notification.error('Something went wrong with saving the page.', 7500);
                }, 500);
            };

            if (saved) {
                saved.done(function () {
                    finishSave('success');
                });

                saved.fail(function () {
                    finishSave('error');
                });
            } else {
                finishSave('success');
            }
        },

        undoLastPublish() {
            var snapshot = this.$data._undoSnapshot;
            if (!snapshot || typeof snapshot !== 'string' || !snapshot.length) {
                mw.notification.error('No previous version available to restore.', 5000);
                return;
            }
            var confirmed = false;
            try { confirmed = window.confirm(UNDO_CONFIRM_PROMPT); } catch (_) { confirmed = true; }
            if (!confirmed) return;

            try {
                var canvasWin = mw.app.canvas.getWindow();
                if (!canvasWin || !canvasWin.document || !canvasWin.document.body) {
                    throw new Error('Canvas not reachable.');
                }
                canvasWin.document.body.innerHTML = snapshot;
                this.$data._dirty = true;
                this.save({ fromUndo: true });
            } catch (err) {
                var msg = (err && err.message) ? err.message : 'unknown error';
                mw.notification.error('Could not restore previous version (' + msg + '). Please reload the editor.', 7500);
            }
        },

        startAutoSave() {
            if (this.$data._autoSaveActive) return;
            this.$data._autoSaveActive = true;
            var self = this;
            this.$data._autoSaveTimer = setInterval(function () {
                if (!self.$data._dirty) return;
                self.save({ silent: true });
            }, AUTO_SAVE_INTERVAL_MS);
        },

        stopAutoSave() {
            if (this.$data._autoSaveTimer) {
                clearInterval(this.$data._autoSaveTimer);
                this.$data._autoSaveTimer = null;
            }
            this.$data._autoSaveActive = false;
        },

        markDirty() {
            this.$data._dirty = true;
        }
    },
    mounted() {
        //save on ctrl + s
        var saveButtonInstance = this;

        // NOVICE #2 — listen for the Undo button's CustomEvent.
        // Bind once at mount, unbind at beforeUnmount.
        this._mwUndoListener = function () { saveButtonInstance.undoLastPublish(); };
        try { window.addEventListener('liveEditUndoLastPublish', this._mwUndoListener); } catch (_) { /* no-op */ }

        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            mw.app.editor.on('Ctrl+S', function (event) {
                event.preventDefault();
                saveButtonInstance.save();
            });
            // Start 30s draft auto-save once the canvas is ready.
            // Mark dirty on any DOM-level edit signal (Editor
            // change event + click-to-edit dblclick).
            try {
                saveButtonInstance.startAutoSave();
                var canvasWin = mw.app.canvas.getWindow();
                if (canvasWin && canvasWin.document) {
                    var markDirty = function () { saveButtonInstance.markDirty(); };
                    canvasWin.document.addEventListener('input', markDirty, true);
                    canvasWin.document.addEventListener('dblclick', markDirty, true);
                    canvasWin.document.addEventListener('drop', markDirty, true);
                }
                if (mw.app.editor && typeof mw.app.editor.on === 'function') {
                    mw.app.editor.on('change', function () { saveButtonInstance.markDirty(); });
                }
            } catch (_) { /* no-op */ }

            // NOVICE #2 — capture the initial canvas snapshot for
            // the undo-publish flow. This is the "server state at
            // session start"; subsequent saves will rotate this
            // forward to the most recent pre-save state.
            try {
                saveButtonInstance.$data._undoSnapshot = saveButtonInstance.captureCanvasSnapshot();
            } catch (_) { /* no-op */ }
        });
        // AI-988 / task-2026-06-04-le988 — Cmd/Ctrl+S triggers Save.
        // Accept metaKey (Cmd on macOS) in addition to ctrlKey
        // (Windows/Linux) so keyboard-first editors on every platform
        // can save without reaching for the mouse. Retained on the
        // instance so beforeUnmount can detach it.
        this._mwSaveKeydownListener = function (event) {
            if ((event.ctrlKey || event.metaKey) && event.keyCode === 83) {
                event.preventDefault();
                saveButtonInstance.save();
            }
        };
        document.addEventListener('keydown', this._mwSaveKeydownListener);
    },
    beforeUnmount() {
        this.stopAutoSave();
        try { window.removeEventListener('liveEditUndoLastPublish', this._mwUndoListener); } catch (_) { /* no-op */ }
        try { if (this._mwSaveKeydownListener) document.removeEventListener('keydown', this._mwSaveKeydownListener); } catch (_) { /* no-op */ }
    },
    beforeDestroy() {
        // Vue 2 fallback name
        this.stopAutoSave();
        try { window.removeEventListener('liveEditUndoLastPublish', this._mwUndoListener); } catch (_) { /* no-op */ }
        try { if (this._mwSaveKeydownListener) document.removeEventListener('keydown', this._mwSaveKeydownListener); } catch (_) { /* no-op */ }
    }
}
</script>
<template>
    <!-- AI-274 / AI-268 (task-2026-05-13-b07dd6): `title` matches the
         existing aria-label so pointer users see the same Ctrl+S hint
         that screen-reader users already received via the aria-label.
         task-2026-05-16-3a464f: user asked for a shorter "Save" label —
         the act-of-publishing is implicit on a live edit save and the
         shorter copy fits cleaner in narrow viewports. Functional
         behaviour is unchanged (save() still does the publish step
         behind the scenes); only the visible/announced copy changes.

         task-2026-05-16-8b10a3 / AI-699 — v2-style solid black pill.
         Button now binds `mw-save-button--idle` when `_dirty === false`
         (no unsaved changes — visually muted; cursor: default; no
         pulse) and `mw-save-button--has-changes` when `_dirty === true`
         (active — full saturation + accent-ring pulse once per minute).
         The `_dirty` flag is the single source of truth for save
         state — already wired to input / dblclick / drop / Editor
         change / save() events in this component. CSS for both states
         lives in `general-styles.css` so the pill renders inside the
         Live Edit admin frame where ESE tokens (--ese-text /
         --ese-surface / --radius-pill / --space-sm / --space-md /
         --ese-accent / --t-slow) resolve from :root. Legacy
         `btn btn-dark` classes preserved as back-compat in case
         external code targets them. -->
    <button
        class="btn btn-dark live-edit-toolbar-buttons"
        :class="{
            'mw-save-button': true,
            'mw-save-button--has-changes': _dirty,
            'mw-save-button--idle': !_dirty
        }"
        :aria-pressed="_dirty ? 'true' : 'false'"
        id="save-button"
        :aria-label="'Save (' + saveShortcutLabel + ')'"
        :title="'Save (' + saveShortcutLabel + ')'"
        @click="save()">
            <span class="font-weight-bold">Save</span>
     </button>
</template>
