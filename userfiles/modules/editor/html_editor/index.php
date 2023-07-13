<?php must_have_access(); ?>


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

</style>

<script>
    mw.require('<?php print modules_url()?>editor/html_editor/html_editor.js');
    mw.require('options.js');
    mw.lib.require('codemirror');
    mw.require('<?php print modules_url()?>editor/selector.css');
    var $time_out_handle = 0, html_code_area_editor;
    $(document).ready(function () {
        mw.tools.loading(document.body, true);


        html_code_area_editor = CodeMirror.fromTextArea(document.getElementById("custom_html_code_mirror"), {
            lineNumbers: true,
            lineWrapping: true,
            matchTags: {bothTags: true},
            indentWithTabs: true,
            matchBrackets: true,
            extraKeys: {
                "Ctrl-Space": "autocomplete",
                "Ctrl-Q": function (cm) {
                    cm.foldCode(cm.getCursor());
                },
                "Ctrl-J": "toMatchingTag"
            },
            mode: {
                name: "htmlmixed",
                scriptTypes: [{
                    matches: /\/x-handlebars-template|\/x-mustache/i,
                    mode: null
                },
                    {
                        matches: /(text|application)\/(x-)?vb(a|script)/i,
                        mode: "vbscript"
                    }]
            },
            foldGutter: true,
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
        });
        html_code_area_editor.setOption("theme", 'material');
        html_code_area_editor.setSize("100%", "100%");
        html_code_area_editor.on("change", function (cm, change) {
            var custom_html_code_mirror = document.getElementById("custom_html_code_mirror")
            custom_html_code_mirror.value = cm.getValue();
            window.clearTimeout($time_out_handle);
            $time_out_handle = window.setTimeout(function () {
                $(custom_html_code_mirror).change();
            }, 2000);
        });
        mw.tools.loading(false);


    })


    function format_code() {
        html_code_area_editor.setSelection({
                'line': html_code_area_editor.firstLine(),
                'ch': 0,
                'sticky': null
            }, {
                'line': html_code_area_editor.lastLine(),
                'ch': 0,
                'sticky': null
            },
            {scroll: false});
        //auto indent the selection
        html_code_area_editor.indentSelection("smart");

        html_code_area_editor.setSelection({
                'line': html_code_area_editor.firstLine(),
                'ch': 0,
                'sticky': null
            }, {
                'line': html_code_area_editor.firstLine(),
                'ch': 0,
                'sticky': null
            },
            {scroll: false});

    }


    $(document).ready(function () {
        mw.html_editor.init();
    })


</script>


<nav class="navbar navbar-expand-md ">
    <div class="container-fluid">

        <div id="select_edit_field_container">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <?php _e('Select field to edit'); ?>
                </button>
                <div id="select_edit_field_wrap"></div>
            </div>
        </div>






        <div   id="custom_html_code_mirror_save">
            <div class="btn-group btn-block" role="group">
                <button onclick="format_code();"  class="btn btn-outline-primary" type="submit"><?php _e('Format code'); ?></button>
                <button onclick="mw.html_editor.apply();" class="btn btn-primary" type="submit"><?php _e('Update'); ?></button>
            </div>

          <?php

          /*  <span onclick="mw.html_editor.apply_and_save();"
                  class="mw-ui-btn mw-ui-btn-invert"><?php _e('Update'); ?><?php _e('and'); ?><?php _e('save'); ?></span>*/
          ?>
        </div>
    </div>
</nav>









<div id="custom_html_code_mirror_container">
                <textarea class="form-select  w100" name="custom_html" id="custom_html_code_mirror"
                          option-group="template" placeholder="<?php _e('Type your HTML code here'); ?>"></textarea>
</div>





