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
    </style>


    <script>
        mw.lib.require('codemirror');
    </script>
    <script>
        function mwCodeEditor() {
            // Private variables
            let html_code_area_editor2;
            let targetWindow;
            let targetDocument;
            let setHtmlToNode = false;

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
                });
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
            const setEditorContent = function () {

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
            };

            // Apply HTML edit to the selected node
            const applyHtmlEdit = function () {
                var custom_html_code_mirror = document.getElementById("html_code_area_editor2");
                var val = $(custom_html_code_mirror).val();

                if (setHtmlToNode) {
                    setHtmlToNode.innerHTML = val;

                    var modules_ids = {};
                    var modules_list = $('.module', setHtmlToNode);

                    $(modules_list).each(function () {
                        var id = $(this).attr('id');
                        if (id) {
                            id = '#' + id;
                        } else {
                            id = $(this).attr('data-type');
                        }
                        if (!id) {
                            id = $(this).attr('type');
                        }
                        modules_ids[id] = true;
                    });

                    $.each(modules_ids, function (index, value) {
                        targetWindow.mw.reload_module(index);
                    });

                    mw.top().app.registerChangedState(setHtmlToNode);
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
                formatCode: formatCode
            };
        }

        // Initialize the editor
        const codeEditor = mwCodeEditor();

        // Define global functions that use the editor instance
        function applyHtmlEdit2() {
            codeEditor.applyHtmlEdit();
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
            <?php /*        <button onclick="format_code2();"  class="btn btn-outline-primary" type="button"><?php _e('Format code'); ?></button>
*/ ?>
            <button id="mw-html-editor-update-btn" class="btn btn-dark" type="button"><?php _e('Update'); ?></button>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var updateBtn = document.getElementById('mw-html-editor-update-btn');
            if (updateBtn) {
                updateBtn.addEventListener('click', function () { applyHtmlEdit2(); });
            }
        });
    </script>
</div>
