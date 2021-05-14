<?php $custom_css = get_option("custom_css", "template");


?>
<?php must_have_access(); ?>

<style>
    html,body{
        direction: initial;
    }

    .CodeMirror-code{
        min-height: 80vh;
    }

</style>

<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.js"></script>
<script type="text/javascript">

    live_edit_css_code_area_editor_value = '';
        mw.require('options.js');

        $(document).ready(function(){

                mw.require('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.css');

                mw.getScripts([
                    '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.js',
                    '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.min.js',
                    '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/htmlmixed/htmlmixed.min.js',
                    '//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/php/php.min.js',
                ], function(){
                    css_code_area_editor = CodeMirror.fromTextArea(document.getElementById("custom_css_code_mirror"), {
                            lineNumbers: true,
                            indentWithTabs: true,
                            matchBrackets: true,
                            extraKeys: {"Ctrl-Space": "autocomplete"},
                            mode: {
                                    name: "css", globalVars: true

                            }
                    });

                    css_code_area_editor.setSize("100%", "auto");

                    css_code_area_editor.on("change", function (cm, change) {


                    });


                    if(document.getElementById("live_edit_custom_css_code_mirror")){

                        live_edit_css_code_area_editor = CodeMirror.fromTextArea(document.getElementById("live_edit_custom_css_code_mirror"), {
                            lineNumbers: true,
                            indentWithTabs: true,
                            matchBrackets: true,
                            gutter: true,

                            extraKeys: {"Ctrl-Space": "autocomplete"},
                            mode: {
                                name: "css", globalVars: true

                            }
                        });

                        live_edit_css_code_area_editor.setSize("90%", "90%");

                        live_edit_css_code_area_editor.on("change", function (cm, change) {

//var val  = document.getElementById("live_edit_custom_css_code_mirror").value;

                         //   alert(val);
                        });



                    }

                })


        });

    $(window).on('load', function () {
        mw.options.form('#<?php print $params['id'] ?>', function () {
            if (mw.notification != undefined) {
                mw.notification.success('CSS Updated');
            }
            if (typeof(window.parent.mw.wysiwyg) != 'undefined') {
                var custom_fonts_stylesheet = window.parent.document.getElementById("mw-custom-user-css");
                if (custom_fonts_stylesheet != null) {
                    var custom_fonts_stylesheet_restyled = '<?php print api_nosession_url('template/print_custom_css') ?>?v=' + Math.random(0, 10000);
                    custom_fonts_stylesheet.href = custom_fonts_stylesheet_restyled;

                }
            }

        });

    });
        $time_out_handle = 0;


        savecss = function(){
            mw.options.saveOption({
                group:'template',
                key:'custom_css',
                value:css_code_area_editor.getValue()
            },
            function(){
                var el = (window.opener || top).$('#mw-custom-user-css')[0];

                if(el){
                var custom_fonts_stylesheet_restyled = '<?php print api_nosession_url('template/print_custom_css') ?>?v=' + Math.random(0, 10000);
                el.href = custom_fonts_stylesheet_restyled;
                mw.tools.refresh(el)
                }
            });
        }

</script>





<?php

$template = template_name();
$file = mw()->layouts_manager->template_check_for_custom_css($template);
$live_edit_css_content = '';
if ($file and is_file($file)) {
    $live_edit_css_content = file_get_contents($file);
}
?>




<script>
    $(document).ready(function () {
        mw.tabs({
            nav: '.mw-ui-btn-nav-tabs a',
            tabs: '.mw-ui-box-content',
            onclick: function(){

               if(typeof live_edit_css_code_area_editor != 'undefined'){
                   setTimeout(function(){
                       live_edit_css_code_area_editor.refresh();

                   }, 200);




               }
            }
        });

    });


    live_edit_savecss = function () {

        var css = {
            css_file_content: live_edit_css_code_area_editor.getValue(),
            active_site_template: '<?php print $template ?>'
        };
        $.post(mw.settings.api_url + "current_template_save_custom_css", css, function (res) {

            var css = parent.mw.$("#mw-template-settings")[0];

            if(css !== undefined && css !== null){
                mw.tools.refresh(top.document.querySelector('link[href*="live_edit.css"]'))

                mw.notification.success('CSS Saved')

            }



        });


    }
</script>



<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
    <a href="javascript:;" class="mw-ui-btn active"><?php _e("Custom "); ?>CSS</a>
    <a href="javascript:;" class="mw-ui-btn"><?php _e("Visual Editor"); ?> CSS</a>
</div>
<div class="mw-ui-box">
    <div class="mw-ui-box-content">

<textarea class="mw-ui-field w100 mw_option_field" dir="ltr" name="custom_css" id="custom_css_code_mirror" rows="30"
          option-group="template" placeholder="<?php _e('Type your CSS code here'); ?>"><?php print $custom_css ?></textarea>
        <div class="mw-ui-btn-nav pull-right" id="csssave">
            <span onclick="savecss();" class="mw-ui-btn mw-ui-btn-invert"><?php _e('Save'); ?></span>
        </div>


    </div>
    <div class="mw-ui-box-content" style="display: none">



        <textarea class="mw-ui-field w100" dir="ltr" name="live_edit_custom_css"
                  id="live_edit_custom_css_code_mirror" rows="30"
                  placeholder="<?php _e('Type your CSS code here'); ?>"><?php print $live_edit_css_content ?></textarea>


        <div>
            <div class="mw-ui-btn-nav pull-right">
                <span onclick="live_edit_savecss();" class="mw-ui-btn mw-ui-btn-invert"><?php _e('Save'); ?></span>
            </div>


            <div class="mw-ui-btn-nav pull-left">

                <module type="content/views/layout_selector_custom_css" template="<?php print $template; ?>"/>

            </div>
        </div>



    </div>
</div>













