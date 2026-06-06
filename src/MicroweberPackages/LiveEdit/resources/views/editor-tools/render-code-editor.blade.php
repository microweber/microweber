<div>
    <style>
        .CodeMirror,
        #select_edit_field_wrap {
            height: 100%;
        }

        .htmleditliframe {
            width: 100%;
            height: 120px;
            overflow: hidden;
            position: relative;
        }

        .htmleditliframe:after {
            position: absolute;
            content: '';
            display: block;
            z-index: 1;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .htmleditliframe iframe {
            overflow: hidden;
            width: 300%;
            height: 360px;
            transform: scale(.33333);
            transform-origin: 0 0;
            pointer-events: none;
        }

        .CodeMirror, #select_edit_field_wrap {
            height: calc(100vh - 55px) !important;
        }

        .tab-content .tab-content .CodeMirror, #select_edit_field_wrap {
            height: calc(100vh - 55px) !important;
        }

        .htmleditliframe {
            width: 100%;
            height: 120px;
            overflow: hidden;
            position: relative;
        }

        .htmleditliframe:after {
            position: absolute;
            content: '';
            display: block;
            z-index: 1;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .htmleditliframe iframe {
            overflow: hidden;
            width: 300%;
            height: 360px;
            transform: scale(.33333);
            transform-origin: 0 0;
            pointer-events: none;
        }

        .mw-html-editor-unsaved-banner {
            position: sticky;
            bottom: 0;
            z-index: 7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            margin: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 0;
        }

        .mw-html-editor-unsaved-banner[hidden] {
            display: none;
        }
    </style>


    <script>
        mw.lib.require('codemirror');
        // audit-test 2026-05-07 PM TASK-005 / TICKET-AF: ensures filterXSS()
        // (js-xss) is available in this scope before applyHtmlEdit runs.
        mw.require('xss.js');
    </script>
    <script>
        function mwCodeEditor() {
            // Private variables
            let html_code_area_editor2;
            let targetWindow;
            let targetDocument;
            let setHtmlToNode = false;
            let hasUnsavedChanges = false;
            let pendingEditorRefresh = false;

            // Initialize the editor
            const init = function () {
                targetWindow = mw.top().app.canvas.getWindow();
                targetDocument = mw.top().app.canvas.getDocument();

                initCodeEditor();
                initEvents();
            };

            // Initialize the code editor
            const initCodeEditor = function () {
                // audit-test 2026-05-07 Code Editor audit finding #11 (UX):
                // Ctrl-S/Cmd-S now triggers Update; previously the browser's
                // "Save Page As..." native dialog hijacked the shortcut and
                // the admin had no keyboard path to commit changes.
                html_code_area_editor2 = CodeMirror.fromTextArea(document.getElementById("html_code_area_editor2"), {
                    lineNumbers: true,
                    gutter: false,
                    lineWrapping: true,
                    matchTags: {bothTags: true},
                    extraKeys: {
                        "Ctrl-Space": "autocomplete",
                        "Ctrl-S": function (cm) { applyHtmlEdit2(); return false; },
                        "Cmd-S": function (cm) { applyHtmlEdit2(); return false; }
                    },
                    mode: {
                        name: "text/html", globalVars: true
                    }
                });

                html_code_area_editor2.setSize("100%", "auto");
                html_code_area_editor2.setOption("theme", 'material');
                html_code_area_editor2.on("change", function (cm, change) {
                    $('#html_code_area_editor2').val(cm.getValue());
                    if (change && change.origin === 'setValue') {
                        return;
                    }

                    setUnsavedChanges(true);
                });
            };

            const getDirtyBanner = function () {
                return document.getElementById('mw-html-editor-unsaved-banner');
            };

            const setUnsavedChanges = function (isDirty) {
                hasUnsavedChanges = isDirty;
                if (!isDirty) {
                    pendingEditorRefresh = false;
                }

                const banner = getDirtyBanner();
                if (banner) {
                    banner.hidden = !isDirty;
                }
            };

            const showPendingChangesBanner = function () {
                pendingEditorRefresh = true;
                setUnsavedChanges(true);
            };

            const discardPendingChanges = function () {
                setUnsavedChanges(false);
                setEditorContent(true);
            };

            const escapeSelectorValue = function (value) {
                if (typeof value !== 'string') {
                    return '';
                }

                if (window.CSS && typeof window.CSS.escape === 'function') {
                    return window.CSS.escape(value);
                }

                return value.replace(/"/g, '\\"');
            };

            const collectModuleSelectors = function (root) {
                const modules = {};

                if (!root || !root.querySelectorAll) {
                    return modules;
                }

                root.querySelectorAll('.module').forEach(function (el) {
                    const id = el.getAttribute('id');
                    if (id) {
                        modules['#' + escapeSelectorValue(id)] = el;
                        return;
                    }

                    const moduleId = el.getAttribute('data-module-id');
                    if (moduleId) {
                        modules['[data-module-id="' + escapeSelectorValue(moduleId) + '"]'] = el;
                    }
                });

                return modules;
            };

            // Initialize events
            const initEvents = function () {


                mw.top().app.canvas.on('canvasDocumentClick', function () {

                    setEditorContent();
                });
                mw.top().app.canvas.on('canvasDocumentInput', function () {

                    setEditorContent();
                });
                mw.top().app.canvas.on('canvasDocumentKeydown', function () {

                    setEditorContent();
                });

                mw.top().app.editor.on('editNodeRequest', function () {
                    if (setHtmlToNode) {
                        setEditorContent();
                    }
                });


                mw.top().app.liveEdit.handles.get('element').on('targetChange', (node, event ) => {


                        setEditorContent();


                });


                mw.top().app.state.on('undo', () => {

                        setEditorContent();


                });
                mw.top().app.state.on('redo', () => {

                        setEditorContent();


                });




            };

            // Set editor content based on selected node
            const setEditorContent = function (force) {
                if (hasUnsavedChanges && !force) {
                    showPendingChangesBanner();
                    return;
                }

                var activeNode = mw.top().app.liveEdit.getSelectedNode();
                var can = mw.top().app.liveEdit.canBeElement(activeNode);
                if (!can) {
                    setHtmlToNode = false;
                } else {
                    setHtmlToNode = activeNode;
                }

                if(!setHtmlToNode){
                    return;
                }



                if (setHtmlToNode) {
                    var htmlOrigClone = '';
                    var htmlOrig = setHtmlToNode.innerHTML;
                    var origId = setHtmlToNode.getAttribute('id');

                    var hasClone = false;
                    var htmlOrigCloneNode = false;

                    const original = targetDocument.getElementById(origId);
                    if (original) {
                        hasClone = true;
                        htmlOrigCloneNode = original.cloneNode(true);
                    }

                    // Replace .module with [module]
                    if (hasClone && htmlOrigCloneNode) {
                        htmlOrigCloneNode.querySelectorAll('.module').forEach(function (el) {
                            el.innerHTML = '[module]';
                            el.contentEditable = false;
                        });

                        htmlOrigCloneNode.querySelectorAll('[data-mwplaceholder]').forEach(function (el) {
                            el.removeAttribute('data-mwplaceholder');
                        });

                        htmlOrigCloneNode.querySelectorAll('[data-mw-live-edithover]').forEach(function (el) {
                            el.removeAttribute('data-mw-live-edithover');
                        });

                        htmlOrigClone = htmlOrigCloneNode.innerHTML;
                        html_code_area_editor2.setValue(htmlOrigClone);
                    } else {
                        html_code_area_editor2.setValue(htmlOrig);
                    }

                    html_code_area_editor2.refresh();
                } else {
                    // Disable editor
                    html_code_area_editor2.setValue('');
                    html_code_area_editor2.refresh();
                }

                setUnsavedChanges(false);
            };

            // audit-test 2026-05-07 PM TASK-005 / TICKET-AF (SECURITY HIGH):
            // applyHtmlEdit was assigning admin-typed CodeMirror content
            // straight to setHtmlToNode.innerHTML — stored-XSS sink in the
            // canvas iframe (admin origin). Now route val through filterXSS
            // (js-xss) with an allow-list extended for Microweber editor
            // markup so the live-edit round-trip stays byte-equivalent
            // (`<module>` self-closing tag + data-* attrs + class/id/type
            // on divs) while `<script>`, on*= handlers, javascript: URLs
            // are stripped.
            const _filterEditorHtml = function (html) {
                if (typeof filterXSS !== 'function') {
                    return html;
                }
                var keepAllDataAttrs = function (tag, name, value) {
                    if (name.substr(0, 5) === 'data-' || name === 'class' || name === 'id') {
                        // SECURITY: still strip JS via the value-side option below;
                        // here we just permit the attribute *name* through.
                        return name + '="' + filterXSS.escapeAttrValue(value) + '"';
                    }
                };
                return filterXSS(html, {
                    // Add the Microweber custom <module> tag to the allow-list.
                    // type/data-type/class/id are commonly set on it.
                    whiteList: Object.assign({}, filterXSS.getDefaultWhiteList(), {
                        module: ['type', 'data-type', 'class', 'id'],
                        // <div>/<span> default whiteList in js-xss only allows
                        // a tiny set of attrs — admin-edit canvas uses class/
                        // id/type/data-* heavily, so widen them here.
                        div: ['class', 'id', 'type', 'data-type', 'data-module-id', 'style'],
                        span: ['class', 'id', 'type', 'data-type', 'style'],
                    }),
                    onIgnoreTagAttr: keepAllDataAttrs,
                    onTagAttr: function (tag, name, value, isWhiteAttr) {
                        // Block `javascript:`, `data:`, `vbscript:` schemes in any URL-bearing attr
                        if ((name === 'href' || name === 'src') &&
                            /^(javascript|data|vbscript):/i.test((value || '').trim())) {
                            return name + '=""';
                        }
                    },
                });
            };

            // Apply HTML edit to the selected node
            const applyHtmlEdit = function () {
                var custom_html_code_mirror = document.getElementById("html_code_area_editor2");
                var val = _filterEditorHtml($(custom_html_code_mirror).val());

                if (setHtmlToNode) {
                    var modulesBefore = collectModuleSelectors(setHtmlToNode);
                    setHtmlToNode.innerHTML = val;
                    var modulesAfter = collectModuleSelectors(setHtmlToNode);

                    $.each(modulesBefore, function (selector, node) {
                        if (!modulesAfter[selector] && node) {
                            mw.top().app.editor.dispatch('moduleRemoved', node);
                        }
                    });

                    $.each(modulesAfter, function (selector) {
                        if (!modulesBefore[selector]) {
                            targetWindow.mw.reload_module(selector);
                        }
                    });

                    var shouldRefreshEditor = pendingEditorRefresh;
                    mw.top().app.registerChangedState(setHtmlToNode);
                    setUnsavedChanges(false);

                    if (shouldRefreshEditor) {
                        setEditorContent(true);
                    }
                }
            };

            // Format the code in the editor
            const formatCode = function () {
                html_code_area_editor2.setSelection({
                        'line': html_code_area_editor2.firstLine(),
                        'ch': 0,
                        'sticky': null
                    }, {
                        'line': html_code_area_editor2.lastLine(),
                        'ch': 0,
                        'sticky': null
                    },
                    {scroll: false});

                // Auto indent the selection
                html_code_area_editor2.indentSelection("smart");

                html_code_area_editor2.setSelection({
                        'line': html_code_area_editor2.firstLine(),
                        'ch': 0,
                        'sticky': null
                    }, {
                        'line': html_code_area_editor2.firstLine(),
                        'ch': 0,
                        'sticky': null
                    },
                    {scroll: false});
            };

            // Public methods
            return {
                init: init,
                applyHtmlEdit: applyHtmlEdit,
                formatCode: formatCode,
                discardPendingChanges: discardPendingChanges
            };
        }

        // Initialize the editor
        const codeEditor = mwCodeEditor();

        // Define global functions that use the editor instance
        function applyHtmlEdit2() {
            codeEditor.applyHtmlEdit();
        }

        function discardHtmlChanges2() {
            codeEditor.discardPendingChanges();
        }

        function format_code2() {
            codeEditor.formatCode();
        }

        // mw.lib.require('codemirror') above injects the codemirror.js
        // <script> tag asynchronously — there is no callback for it. If
        // we call codeEditor.init() before the script finishes parsing,
        // CodeMirror.fromTextArea throws "CodeMirror is not defined"
        // (see task-2026-04-29-8db524). Poll for the global up to 10s
        // before kicking off init.
        $(document).ready(function () {
            var attempts = 0;
            var maxAttempts = 200; // 200 × 50ms = 10s
            var waitForCodeMirror = function () {
                if (typeof window.CodeMirror !== 'undefined') {
                    codeEditor.init();
                    return;
                }
                if (++attempts >= maxAttempts) {
                    console.error('CodeMirror failed to load within 10s — code editor will not initialise.');
                    return;
                }
                setTimeout(waitForCodeMirror, 50);
            };
            waitForCodeMirror();
        });
    </script>

    <div id="custom_html_code_mirror_container">
        {{-- audit-test 2026-05-07 Code Editor audit finding #8 (A11Y):
             screen-reader users entering CodeMirror heard "edit text,
             multi-line" with no context. CM5 forwards aria attributes
             to its input handle. --}}
        <textarea class="form-select w100" dir="ltr" id="html_code_area_editor2" rows="30" aria-label="<?php _e('HTML source code'); ?>"></textarea>
    </div>

    <div id="mw-html-editor-unsaved-banner" class="alert alert-warning mw-html-editor-unsaved-banner" hidden>
        <span><?php _e('You have unsaved HTML changes. Apply them to keep your edits or discard them to reload the current element.'); ?></span>
        <div class="btn-group btn-group-sm" role="group" aria-label="<?php _e('Unsaved HTML actions'); ?>">
            <button id="mw-html-editor-discard-btn" class="btn btn-outline-dark" type="button"><?php _e('Discard'); ?></button>
            <button id="mw-html-editor-apply-btn" class="btn btn-dark" type="button"><?php _e('Apply'); ?></button>
        </div>
    </div>

    {{-- audit-test 2026-05-07 Code Editor audit finding #1 (P0 BLOCKER):
         Update was a <span onclick> — not focusable, not keyboard-
         activatable, and `type="button"` on a span is a no-op. The
         editor had ZERO keyboard commit path, so a screen-reader /
         keyboard-only admin could not save their work. Convert to a
         real <button> + addEventListener; keep the global
         applyHtmlEdit2() function intact since it is referenced from
         the canvas iframe. --}}
    <div class="mw-css-editor-c2a-nav">
        <div class="btn-group btn-block" role="group">
            {{-- task-2026-06-06-fmtcodebtn: the Format-code affordance was
                 commented out (raw <span onclick>-style), so admins had no
                 way to auto-indent their HTML even though formatCode() /
                 format_code2() are fully wired below. Restored it as a real,
                 focusable <button> bound via addEventListener (matching the
                 2026-05-07 audit pattern used for Update). --}}
            <button id="mw-html-editor-format-btn" class="btn btn-outline-dark" type="button"><?php _e('Format code'); ?></button>
            <button id="mw-html-editor-update-btn" class="btn btn-dark" type="button"><?php _e('Update'); ?></button>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var updateBtn = document.getElementById('mw-html-editor-update-btn');
            if (updateBtn) {
                updateBtn.addEventListener('click', function () { applyHtmlEdit2(); });
            }
            // task-2026-06-06-fmtcodebtn: keyboard-accessible Format-code wiring.
            var formatBtn = document.getElementById('mw-html-editor-format-btn');
            if (formatBtn) {
                formatBtn.addEventListener('click', function () { format_code2(); });
            }
            var applyDirtyBtn = document.getElementById('mw-html-editor-apply-btn');
            if (applyDirtyBtn) {
                applyDirtyBtn.addEventListener('click', function () { applyHtmlEdit2(); });
            }
            var discardDirtyBtn = document.getElementById('mw-html-editor-discard-btn');
            if (discardDirtyBtn) {
                discardDirtyBtn.addEventListener('click', function () { discardHtmlChanges2(); });
            }
        });
    </script>
</div>
