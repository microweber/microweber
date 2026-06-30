<div>



    <?php $custom_css = get_option("custom_css", "template"); ?>

    <?php

    $template = template_name();
    $file = app()->layouts_manager->template_check_for_custom_css($template);
    $live_edit_css_content = '';
    if ($file and is_file($file)) {
        $live_edit_css_content = file_get_contents($file);
    }

    ?>

    <style>

        #code-editor-settings .CodeMirror {
            width: 100% !important;
        }

        #code-editor-settings .CodeMirror-code {
            height: calc(100vh - 100px);
        }

        #settings-container {
            padding: 0;
            min-height: 0;
        }

        .mw-css-editor-c2a-nav > * + * {
            margin-inline-start: 10px;
        }

        .mw-css-editor-c2a-nav,
        .mw-css-editor-c2a-nav .module-content-views-layout-selector-custom-css {
            white-space: nowrap;
        }

        .mw-css-editor-c2a-nav {
            position: fixed;
            right: 15px;
            bottom: 15px;
            background: none;
            z-index: 6;


        }

        .mw-css-editor-c2a-nav .module-content-views-layout-selector-custom-css {
            display: inline-block;
        }

        #custom_html_code_mirror_save {

        }


    </style>

    <script>
        mw.lib.require('codemirror');
    </script>
    <script>
        function mwCssEditor() {
            // Private variables
            let css_code_area_editor;
            let live_edit_css_code_area_editor;

            const getOpenerMw = function() {
                if (typeof window.opener !== 'undefined' && window.opener && window !== window.opener && window.opener.mw) {
                    return window.opener.mw;
                }

                return null;
            };

            const getPrimaryMwContext = function() {
                return getOpenerMw() || mw;
            };

            const refreshCssLink = function(targetMw, selector) {
                if (!targetMw) {
                    return;
                }

                const topWindow = typeof targetMw.top === 'function' ? targetMw.top() : null;
                const topDocument = topWindow && topWindow.document ? topWindow.document : null;
                const link = topDocument ? topDocument.querySelector(selector) : null;

                if (link && targetMw.tools && typeof targetMw.tools.refresh === 'function') {
                    targetMw.tools.refresh(link);
                }
            };

            const dispatchCanvasCssReload = function(targetMw) {
                const topWindow = targetMw && typeof targetMw.top === 'function' ? targetMw.top() : null;
                if (topWindow && topWindow.app && topWindow.app.canvas && typeof topWindow.app.canvas.dispatch === 'function') {
                    topWindow.app.canvas.dispatch('reloadCustomCss');
                }
            };

            const refreshSavedLiveEditCss = function(targetMw) {
                refreshCssLink(targetMw, '#mw-template-settings, link[href*="live_edit.css"]');
                dispatchCanvasCssReload(targetMw);
            };

            // Initialize the editors
            const init = function() {

                    initMainCssEditor();
                    initLiveEditCssEditor();
                    initTabs();
                    initEvents();

            };

            // Initialize the main CSS editor
            const initMainCssEditor = function() {
                if (document.getElementById("custom_css_code_mirror")) {
                    // audit-test 2026-05-07 Code Editor audit finding #11 (UX):
                    // Ctrl-S/Cmd-S now triggers Save; previously the browser's
                    // native "Save Page As..." dialog hijacked the shortcut.
                    css_code_area_editor = CodeMirror.fromTextArea(document.getElementById("custom_css_code_mirror"), {
                        lineNumbers: true,
                        indentWithTabs: true,
                        matchBrackets: true,
                        gutter: false,
                        extraKeys: {
                            "Ctrl-Space": "autocomplete",
                            "Ctrl-S": function (cm) { savecss(); return false; },
                            "Cmd-S": function (cm) { savecss(); return false; }
                        },
                        mode: {
                            name: "css",
                            globalVars: true
                        }
                    });

                    css_code_area_editor.setSize("100%", "auto");
                    css_code_area_editor.setOption("theme", 'material');
                }
            };

            // Initialize the live edit CSS editor
            const initLiveEditCssEditor = function() {
                if (document.getElementById("live_edit_custom_css_code_mirror")) {
                    // audit-test 2026-05-07 Code Editor audit finding #11 (UX):
                    // Ctrl-S/Cmd-S now triggers Save for the live-edit CSS pane.
                    live_edit_css_code_area_editor = CodeMirror.fromTextArea(document.getElementById("live_edit_custom_css_code_mirror"), {
                        lineNumbers: true,
                        indentWithTabs: true,
                        matchBrackets: true,
                        gutter: false,
                        extraKeys: {
                            "Ctrl-Space": "autocomplete",
                            "Ctrl-S": function (cm) { live_edit_savecss(); return false; },
                            "Cmd-S": function (cm) { live_edit_savecss(); return false; }
                        },
                        mode: {
                            name: "css",
                            globalVars: true
                        }
                    });

                    live_edit_css_code_area_editor.setSize("100%", "90%");
                    live_edit_css_code_area_editor.setOption("theme", 'material');
                }
            };

            // Initialize tabs
            const initTabs = function() {
                mw.tabs({
                    nav: '#codeEditorTabStyleEditorCssEditorNav .mw-admin-action-links',
                    tabs: '#codeEditorTabStyleEditorCssEditorNavTabs .tab-pane'
                });

                mw.tabs({
                    nav: '#css-type-tabs-nav a',
                    tabs: '#css-type-tabs .mw-ui-box-content',
                    onclick: function() {
                        refreshLiveEditEditor();
                    }
                });
            };

            const refreshLiveEditEditor = function() {
                if (typeof live_edit_css_code_area_editor !== 'undefined') {
                    setTimeout(function() {
                        live_edit_css_code_area_editor.refresh();
                        live_edit_css_code_area_editor.setSize("100%", "90%");
                    }, 500);
                }
            };

            // Initialize events
            const initEvents = function() {
                // Tab events
                const tabEl = document.querySelector('#codeEditorTabStyleEditorCssEditorNav');
                if (tabEl) {
                    tabEl.addEventListener('shown.bs.tab', event => {
                        refreshLiveEditEditor();
                    });
                }

                const openerMw = getOpenerMw();

                if (openerMw) {
                    openerMw.top().on('mw.liveeditCSSEditor.save', function() {
                        refreshSavedLiveEditCss(openerMw);
                        refreshLiveEditEditor();
                    });
                } else if (mw.top && mw.top().app && mw.top().app.canvas) {
                    mw.top().app.on('setPropertyForSelector', (propertyChangeEvent) => {
                        handlePropertyChange();
                    });
                }
            };

            const handlePropertyChange = function() {
                if (typeof live_edit_css_code_area_editor !== 'undefined') {
                    live_edit_css_code_area_editor.getWrapperElement().parentNode.removeChild(live_edit_css_code_area_editor.getWrapperElement());
                    live_edit_css_code_area_editor = undefined;
                    $('#style-edit-global-template-css-editor-holder-live-edit-css').html(
                        '<div class="alert alert-warning">' +
                        'Editor content has been changed, please save the page to see the changes' +
                        '</div>'
                    );
                }
            };

            // Save main CSS
            const saveMainCss = function() {
                const cssval = css_code_area_editor.getValue();

                mw.options.saveOption({
                    group: 'template',
                    key: 'custom_css',
                    value: cssval
                }, function() {
                    const el = mw.top().app.canvas.getWindow().$('#mw-custom-user-css')[0];

                    if (el) {
                        const custom_fonts_stylesheet_restyled = mw.settings.api_url + 'template/print_custom_css?v=' + Math.random(0, 10000);
                        el.href = custom_fonts_stylesheet_restyled;
                        mw.tools.refresh(el);
                        mw.notification.success('Custom CSS is saved');
                    }

                    const openerMw = getOpenerMw();
                    if (openerMw) {
                        refreshCssLink(openerMw, '#mw-custom-user-css');
                        openerMw.notification.success('Custom CSS is saved');
                    }
                });
            };

            // Save live edit CSS
            const saveLiveEditCss = function() {
                if (!live_edit_css_code_area_editor) return;

                const cssval = live_edit_css_code_area_editor.getValue();

                var liveEditIframeData = mw.top().app.canvas.getLiveEditData();

                const css = {
                    css_file_content: cssval,
                 };

                if (liveEditIframeData
                    && liveEditIframeData.template_name

                ) {
                    var template_name = liveEditIframeData.template_name;
                    css.active_site_template = template_name;
                }


                $.post(mw.settings.api_url + "current_template_save_custom_css", css, function(res) {
                    const primaryMw = getPrimaryMwContext();
                    refreshSavedLiveEditCss(primaryMw);
                    primaryMw.notification.success('CSS Saved');

                    if (primaryMw !== mw) {
                        mw.notification.success('CSS Saved');
                    }
                });
            };

            // Public methods
            return {
                init: init,
                saveMainCss: saveMainCss,
                saveLiveEditCss: saveLiveEditCss
            };
        }

        // Initialize the editor
        const cssEditor = mwCssEditor();

        // Define global functions that use the editor instance
        function savecss() {
            cssEditor.saveMainCss();
        }

        function live_edit_savecss() {
            cssEditor.saveLiveEditCss();
        }

        // mw.lib.require('codemirror') above injects codemirror.js
        // asynchronously without a callback. Calling cssEditor.init()
        // before the script finishes parsing throws "CodeMirror is not
        // defined" (task-2026-04-29-8db524). Poll for the global up to
        // 10s before initialising.
        $(document).ready(function() {
            var attempts = 0;
            var maxAttempts = 200; // 200 × 50ms = 10s
            var waitForCodeMirror = function () {
                if (typeof window.CodeMirror !== 'undefined') {
                    cssEditor.init();
                    return;
                }
                if (++attempts >= maxAttempts) {
                    console.error('CodeMirror failed to load within 10s — CSS editor will not initialise.');
                    return;
                }
                setTimeout(waitForCodeMirror, 50);
            };
            waitForCodeMirror();
        });
    </script>



    <div class="d-flex" id="codeEditorTabStyleEditorCssEditorNavTabs">
        <div class="navbar navbar-expand-md navbar-transparent px-5">
            <ul class="navbar-nav flex-column" id="codeEditorTabStyleEditorCssEditorNav" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab"
                       data-bs-target="#style-edit-global-template-css-editor-holder" type="button" role="tab">
                        <span class="nav-link-title"><?php _e("Custom CSS"); ?></span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="mw-admin-action-links mw-adm-liveedit-tabs  " data-bs-toggle="tab"
                       data-bs-target="#style-edit-global-template-css-editor-holder-live-edit-css" type="button"
                       role="tab">
                        <span class="nav-link-title"><?php _e("Live edit CSS"); ?></span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="tab-content" style="flex: 1">
            <div class="tab-pane active tab-pane-slide-right" id="style-edit-global-template-css-editor-holder"
                 role="tabpanel">


{{-- audit-test 2026-05-07 Code Editor audit finding #8 (A11Y):
     screen-reader users entering CodeMirror heard "edit text, multi-line"
     with no context. CM5 forwards aria attributes to its input handle. --}}
<textarea class="form-select  w100 mw_option_field" dir="ltr" name="custom_css" id="custom_css_code_mirror" rows="30"
          option-group="template" aria-label="<?php _e('Custom CSS source'); ?>"
          placeholder="<?php _e('Type your CSS code here'); ?>"><?php /* task-2026-06-06-csstextareaesc: HTML-escape so a `</textarea>` inside the CSS source can't break out of the textarea into the admin canvas iframe. Lossless — CodeMirror reads .value, which the browser auto-decodes. */ echo htmlspecialchars((string) $custom_css, ENT_QUOTES); ?></textarea>
                {{-- audit-test 2026-05-07 Code Editor audit finding #1 (P0 BLOCKER):
                     <span onclick> Save was not focusable / not keyboard-
                     activatable; converted to <button> + addEventListener.
                     Global savecss() preserved for any external callers. --}}
                <div class="mw-css-editor-c2a-nav" id="csssave">

                    <button id="mw-css-editor-save-btn" class="btn btn-dark" type="button"><?php _e('Save'); ?></button>


                </div>


            </div>
            <div class="tab-pane tab-pane-slide-right" id="style-edit-global-template-css-editor-holder-live-edit-css"
                 role="tabpanel">



        {{-- audit-test 2026-05-07 Code Editor audit finding #8 (A11Y):
             screen-reader label for the live-edit CSS textarea. --}}
        <textarea class="form-select  w100" dir="ltr" name="live_edit_custom_css"
                  id="live_edit_custom_css_code_mirror" rows="30"
                  aria-label="<?php _e('Live edit CSS source'); ?>"
                  placeholder="<?php _e('Type your CSS code here'); ?>"><?php /* task-2026-06-06-csstextareaesc: HTML-escape so a `</textarea>` inside the live-edit CSS file can't break out of the textarea. Lossless — CodeMirror reads the decoded .value. */ echo htmlspecialchars((string) $live_edit_css_content, ENT_QUOTES); ?></textarea>


                {{-- audit-test 2026-05-07 Code Editor audit finding #1 (P0 BLOCKER):
                     <span onclick> Save → <button> + addEventListener; global
                     live_edit_savecss() preserved. --}}
                <div class="mw-css-editor-c2a-nav">


                    <button id="mw-live-edit-css-save-btn" class="btn btn-dark" type="button"><?php _e('Save'); ?></button>


                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var customCssBtn = document.getElementById('mw-css-editor-save-btn');
            if (customCssBtn) {
                customCssBtn.addEventListener('click', function () { savecss(); });
            }
            var liveEditCssBtn = document.getElementById('mw-live-edit-css-save-btn');
            if (liveEditCssBtn) {
                liveEditCssBtn.addEventListener('click', function () { live_edit_savecss(); });
            }
        });
    </script>

</div>
