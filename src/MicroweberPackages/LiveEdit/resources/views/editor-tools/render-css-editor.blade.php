<div>



    <?php $custom_css = get_option("custom_css", "template"); ?>

    <?php

    $template = template_name();
    $file = mw()->layouts_manager->template_check_for_custom_css($template);
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
                    css_code_area_editor = CodeMirror.fromTextArea(document.getElementById("custom_css_code_mirror"), {
                        lineNumbers: true,
                        indentWithTabs: true,
                        matchBrackets: true,
                        gutter: false,
                        extraKeys: {"Ctrl-Space": "autocomplete"},
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
                    live_edit_css_code_area_editor = CodeMirror.fromTextArea(document.getElementById("live_edit_custom_css_code_mirror"), {
                        lineNumbers: true,
                        indentWithTabs: true,
                        matchBrackets: true,
                        gutter: false,
                        extraKeys: {"Ctrl-Space": "autocomplete"},
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

                // Window opener events
                if (typeof window.opener !== 'undefined' && window.opener && window !== window.opener && window.opener.mw) {
                    window.opener.mw.top().on('mw.liveeditCSSEditor.save', function() {
                        setTimeout(function() {
                            window.location.reload();
                        }, 200);
                    });
                }

                if (mw.top && mw.top().app && mw.top().app.canvas) {
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

                    // reload in the window opener
                    if (typeof window.opener !== 'undefined' && window.opener && window !== window.opener) {
                        const el = window.opener.mw.top().$("#mw-custom-user-css")[0];
                        window.opener.mw.tools.refresh(el);
                        window.opener.mw.notification.success('Custom CSS is saved');
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
                    const css = mw.parent().$("#mw-template-settings")[0];

                    if (css !== undefined && css !== null) {
                        mw.tools.refresh(top.document.querySelector('link[href*="live_edit.css"]'));
                        mw.notification.success('CSS Saved');
                    }

                    // reload in the window opener
                    if (typeof window.opener !== 'undefined' && window.opener && window !== window.opener && window.opener.mw) {
                        const css = window.opener.mw.top().$("#mw-template-settings")[0];
                        window.opener.mw.tools.refresh(css);
                        window.opener.mw.notification.success('CSS Saved');
                        mw.notification.success('CSS Saved');
                    }

                    if (mw.top()) {
                        mw.top().app.canvas.dispatch('reloadCustomCss');
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

        $(document).ready(function() {
            cssEditor.init();
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


<textarea class="form-select  w100 mw_option_field" dir="ltr" name="custom_css" id="custom_css_code_mirror" rows="30"
          option-group="template"
          placeholder="<?php _e('Type your CSS code here'); ?>"><?php print $custom_css ?></textarea>
                <div class="mw-css-editor-c2a-nav" id="csssave">

                    <span onclick="savecss();" class="btn btn-dark" type="button"><?php _e('Save'); ?></span>


                </div>


            </div>
            <div class="tab-pane tab-pane-slide-right" id="style-edit-global-template-css-editor-holder-live-edit-css"
                 role="tabpanel">



        <textarea class="form-select  w100" dir="ltr" name="live_edit_custom_css"
                  id="live_edit_custom_css_code_mirror" rows="30"
                  placeholder="<?php _e('Type your CSS code here'); ?>"><?php print $live_edit_css_content ?></textarea>


                <div class="mw-css-editor-c2a-nav">


                    <span onclick="live_edit_savecss();" class="btn btn-dark" type="button"><?php _e('Save'); ?></span>


                </div>

            </div>
        </div>
    </div>

</div>
