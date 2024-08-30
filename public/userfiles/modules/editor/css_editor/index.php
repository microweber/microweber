<?php must_have_access(); ?>
<?php $custom_css = get_option("custom_css", "template"); ?>
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
    //mw.require('options.js');
    mw.lib.require('codemirror')
</script>
<script>

    live_edit_css_code_area_editor_value = '';


    $(document).ready(function () {


        css_code_area_editor = CodeMirror.fromTextArea(document.getElementById("custom_css_code_mirror"), {
            lineNumbers: true,
            indentWithTabs: true,
            matchBrackets: true,
            gutter: false,
            extraKeys: {"Ctrl-Space": "autocomplete"},
            mode: {
                name: "css", globalVars: true
            }
        });

        css_code_area_editor.setSize("100%", "auto");
        css_code_area_editor.setOption("theme", 'material');
        css_code_area_editor.on("change", function (cm, change) {


        });


        if (document.getElementById("live_edit_custom_css_code_mirror")) {

            live_edit_css_code_area_editor = CodeMirror.fromTextArea(document.getElementById("live_edit_custom_css_code_mirror"), {
                lineNumbers: true,
                indentWithTabs: true,
                matchBrackets: true,
                gutter: false,

                extraKeys: {"Ctrl-Space": "autocomplete"},
                mode: {
                    name: "css", globalVars: true

                }
            });

            live_edit_css_code_area_editor.setSize("100%", "90%");
            live_edit_css_code_area_editor.setOption("theme", 'material');
            live_edit_css_code_area_editor.on("change", function (cm, change) {


            });


        }


    });

    $(window).on('load', function () {
        mw.options.form('#<?php print $params['id'] ?>', function () {
            if (mw.notification != undefined) {
                mw.notification.success('CSS Updated');
            }
            if (typeof (window.mw.parent().wysiwyg) != 'undefined') {
                var custom_fonts_stylesheet = window.parent.document.getElementById("mw-custom-user-css");
                if (custom_fonts_stylesheet != null) {
                    var custom_fonts_stylesheet_restyled = '<?php print api_nosession_url('template/print_custom_css') ?>?v=' + Math.random(0, 10000);
                    custom_fonts_stylesheet.href = custom_fonts_stylesheet_restyled;

                }
            }

        });

    });
    $time_out_handle = 0;


    savecss = function () {

        var cssval = css_code_area_editor.getValue();
        var err_text = '';
        var errors = CodeMirror.lint.css(cssval, []);
        if (errors.length) {
            for (var i = 0; i < errors.length; i++) {
                message = errors[i];
                err_text += "\n" + message.message;
            }
        }

        // if (err_text != '') {
        //     err_text = 'You have syntax error on code: ' + err_text;
        //     mw.notification.warning(err_text,9000)
        //     return;
        // }

        mw.options.saveOption({
                group: 'template',
                key: 'custom_css',
                value: cssval
            },
            function () {
                //var el = (window.opener || top).$('#mw-custom-user-css')[0];
                el = mw.top().app.canvas.getWindow().$('#mw-custom-user-css')[0];

                if (el) {

                    var custom_fonts_stylesheet_restyled = '<?php print api_nosession_url('template/print_custom_css') ?>?v=' + Math.random(0, 10000);
                    el.href = custom_fonts_stylesheet_restyled;
                    mw.tools.refresh(el)
                    mw.notification.success('Custom CSS is saved')
                }

                // reload in the window opener
                if (typeof window.opener != 'undefined' && window.opener && window !== window.opener) {
                    var el = window.opener.mw.top().$("#mw-custom-user-css")[0];
                    window.opener.mw.tools.refresh(el)
                    window.opener.mw.notification.success('Custom CSS is saved')
                }


            });
    }

</script>


<script>

    $(document).ready(function () {
        mw.tabs({
            nav: '#codeEditorTabStyleEditorCssEditorNav .mw-admin-action-links',
            tabs: '#codeEditorTabStyleEditorCssEditorNavTabs .tab-pane'
        });
    });

</script>

<?php

$template = template_name();
$file = mw()->layouts_manager->template_check_for_custom_css($template);
$live_edit_css_content = '';
if ($file and is_file($file)) {
    $live_edit_css_content = file_get_contents($file);
}

?>

<div class="d-flex" id="codeEditorTabStyleEditorCssEditorNavTabs">
    <div class="navbar navbar-expand-md navbar-transparent ">
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


                <module type="content/views/layout_selector_custom_css" template="<?php print $template; ?>"/>


                <span onclick="live_edit_savecss();" class="btn btn-dark" type="button"><?php _e('Save'); ?></span>


            </div>

        </div>
    </div>
</div>


<script>
    $(document).ready(function () {


        const tabEl = document.querySelector('#codeEditorTabStyleEditorCssEditorNav');
        tabEl.addEventListener('shown.bs.tab', event => {
            if (typeof live_edit_css_code_area_editor != 'undefined') {
                setTimeout(function () {
                    live_edit_css_code_area_editor.refresh();
                    live_edit_css_code_area_editor.setSize("100%", "90%");
                }, 500);
            }
        })


        mw.tabs({
            nav: '#css-type-tabs-nav a',
            tabs: '#css-type-tabs .mw-ui-box-content',
            onclick: function () {
                if (typeof live_edit_css_code_area_editor != 'undefined') {
                    setTimeout(function () {
                        live_edit_css_code_area_editor.refresh();
                        live_edit_css_code_area_editor.setSize("100%", "90%");
                    }, 500);
                }
            }
        });

    });


    live_edit_savecss = function () {


        var cssval = live_edit_css_code_area_editor.getValue();
        var err_text = '';
        var errors = CodeMirror.lint.css(cssval, []);
        if (errors.length) {
            for (var i = 0; i < errors.length; i++) {
                message = errors[i];
                err_text += "\n" + message.message;
            }
        }
        // skip for now
        // if (err_text != '') {
        //     mw.notification.warning(err_text)
        //     return;
        // }


        var css = {
            css_file_content: cssval,
            active_site_template: '<?php print $template ?>'
        };
        $.post(mw.settings.api_url + "current_template_save_custom_css", css, function (res) {

            var css = mw.parent().$("#mw-template-settings")[0];

            if (css !== undefined && css !== null) {
                mw.tools.refresh(top.document.querySelector('link[href*="live_edit.css"]'))
                mw.notification.success('CSS Saved')
            }

            // reload in the window opener
            if (typeof window.opener != 'undefined' && window.opener && window !== window.opener && window.opener.mw) {
                var css = window.opener.mw.top().$("#mw-template-settings")[0];
                window.opener.mw.tools.refresh(css)
                window.opener.mw.notification.success('CSS Saved')
                mw.notification.success('CSS Saved')
            }
            if (mw.top()) {
                mw.top().app.canvas.dispatch('reloadCustomCss');
            }


        });


    }


    $(document).ready(function () {
        if (typeof window.opener != 'undefined' && window.opener && window !== window.opener && window.opener.mw) {

            window.opener.mw.top().on('mw.liveeditCSSEditor.save', function () {
                // when the live edit is saved from the window opener, we need to reload the css in this module
                setTimeout(function () {
                    window.location.reload();
                }, 200);

            });
        }

        if(mw.top && mw.top().app && mw.top().app.canvas){

            mw.top().on('mw.liveeditCSSEditor.save', function () {
                // when the live edit is saved from the window opener, we need to reload the css in this module
                setTimeout(function () {
                     mw.reload_module('#<?php print $params['id'] ?>')
                }, 200);

            });

            mw.top().app.on('setPropertyForSelector', (propertyChangeEvent) => {
                if (typeof live_edit_css_code_area_editor != 'undefined') {
                    live_edit_css_code_area_editor.getWrapperElement().parentNode.removeChild(live_edit_css_code_area_editor.getWrapperElement());
                    live_edit_css_code_area_editor = undefined;
                    $('#style-edit-global-template-css-editor-holder-live-edit-css').html('' +
                        '<div class="alert alert-warning">' +
                        'Editor content has been changed, please save the page to see the changes' +
                        '</div>');

                }
            });


        }



    });

</script>













