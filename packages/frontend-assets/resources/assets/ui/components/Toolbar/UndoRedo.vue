<template>
    <!-- AI-274 / AI-268 (task-2026-05-13-b07dd6): hover-tooltip + keyboard
         shortcut affordance. `title` is the visible-on-hover hint for
         pointer users; `aria-label` continues to feed screen readers
         (the two attributes serve different a11y surfaces and intentionally
         carry the same human copy + shortcut hint). Mobile users see the
         same affordance via long-press on most touch keyboards. -->
    <!-- AI-719 / task-2026-05-16-d2e562 — aria-label collapsed to
         bare verb "Undo". The "(Ctrl+Z)" shortcut hint is preserved
         in title= for desktop hover, but removed from aria-label so
         (a) the task-8149b5 mobile ::after pseudo (`content: attr(
         aria-label)`) renders just "Undo" — saving ~50px of toolbar
         width contributing to the AI-717 SAVE-offscreen problem; and
         (b) screen readers everywhere announce the bare verb
         (keyboard shortcuts are meaningless to AT users the same way
         they're meaningless to touch users). The shortcut binding
         itself stays in the script block — Ctrl+Z still works on
         desktop. -->
    <button v-on:click="undo()" aria-label="Undo" title="Undo (Ctrl+Z)" class="btn btn-icon me-2 live-edit-toolbar-buttons live-edit-toolbar-buttons-undo-redo" id="vue-toolbar-undo" :disabled='undoIsDisabled'>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20px">
            <path
                d="M12.5,8C9.85,8 7.45,9 5.6,10.6L2,7V16H11L7.38,12.38C8.77,11.22 10.54,10.5 12.5,10.5C16.04,10.5 19.05,12.81 20.1,16L22.47,15.22C21.08,11.03 17.15,8 12.5,8Z"/>
        </svg>
    </button>
    <!-- AI-719 — same rationale as Undo above: aria-label "Redo",
         title preserves "(Ctrl+Y)" for desktop hover. -->
    <button v-on:click="redo()" aria-label="Redo" title="Redo (Ctrl+Y)" class="btn btn-icon live-edit-toolbar-buttons live-edit-toolbar-buttons-undo-redo" id="vue-toolbar-redo" :disabled='redoIsDisabled'>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20px">
            <path
                d="M18.4,10.6C16.55,9 14.15,8 11.5,8C6.85,8 2.92,11.03 1.54,15.22L3.9,16C4.95,12.81 7.95,10.5 11.5,10.5C13.45,10.5 15.23,11.22 16.62,12.38L13,16H22V7L18.4,10.6Z"/>
        </svg>
    </button>
</template>

<script>
export default {
    methods: {
        undo() {
            if (this.undoIsDisabled) {
                return;
            }
            mw.app.state.undo()
            this.setButtons();
        },
        redo() {
            if (this.redoIsDisabled) {
                return;
            }
            mw.app.state.redo()
            this.setButtons();
        },
        setButtons() {
            this.undoIsDisabled = !mw.app.state.hasNext;
            this.redoIsDisabled = !mw.app.state.hasPrev;
        }
    },
    data() {
        return {
            undoIsDisabled: true,
            redoIsDisabled: true,
        };
    },
    mounted() {
        var self = this;
        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            mw.app.state.on('record', () => this.setButtons())
            mw.app.state.on('undo', () => {
                var undoStateTarget = null;
                var state = mw.app.state.state();
                if (state && state[0] && state[0].target) {
                    undoStateTarget = state[0].target;
                }
                if (undoStateTarget) {
                    mw.app.registerChange(undoStateTarget);

                    if(mw.top().app.liveEditWidgets && mw.top().app.liveEditWidgets.quickEditComponentBox) {
                        // mw.app.liveEditWidgets.openQuickEditComponent()
                        if(mw.top().app.liveEditWidgets.quickEditComponentBox.visible() && mw.top().app.liveEditWidgets.quickEditComponentBox.box.parentElement) {
                            mw.top().app.liveEditWidgets.setQuickEditorForNode(undoStateTarget.closest('.module-layouts'));
                        }

                    }

                }

                this.setButtons()
            })
            mw.app.state.on('redo', () => {
                var redoStateTarget = null;
                var state = mw.app.state.state();
                if (state && state[0] && state[0].target) {
                    redoStateTarget = state[0].target;
                }
                if (redoStateTarget) {
                    mw.app.registerChange(redoStateTarget);
                    if(mw.top().app.liveEditWidgets && mw.top().app.liveEditWidgets.quickEditComponentBox) {
                        // mw.app.liveEditWidgets.openQuickEditComponent()
                        if(mw.top().app.liveEditWidgets.quickEditComponentBox.visible() && mw.top().app.liveEditWidgets.quickEditComponentBox.box.parentElement) {
                            mw.top().app.liveEditWidgets.setQuickEditorForNode(redoStateTarget.closest('.module-layouts'));
                        }

                    }
                }

                this.setButtons()
            })

            // AI-274 / AI-268 (task-2026-05-13-b07dd6): keyboard shortcuts.
            // SaveButton.vue binds Ctrl+S the same way; we mirror that
            // here so Ctrl+Z (undo), Ctrl+Y (redo) and the conventional
            // Ctrl+Shift+Z (also redo, Mac-style alias) work everywhere
            // mw.app.editor reaches — same handler pipeline, same
            // disabled-state guard as the click path so a held key never
            // dispatches past the end of the history buffer.
            if (mw.app.editor && typeof mw.app.editor.on === 'function') {
                mw.app.editor.on('Ctrl+Z', function (event) {
                    if (event && typeof event.preventDefault === 'function') {
                        event.preventDefault();
                    }
                    self.undo();
                });
                mw.app.editor.on('Ctrl+Y', function (event) {
                    if (event && typeof event.preventDefault === 'function') {
                        event.preventDefault();
                    }
                    self.redo();
                });
                mw.app.editor.on('Ctrl+Shift+Z', function (event) {
                    if (event && typeof event.preventDefault === 'function') {
                        event.preventDefault();
                    }
                    self.redo();
                });
            }
        })


    },

}
</script>
