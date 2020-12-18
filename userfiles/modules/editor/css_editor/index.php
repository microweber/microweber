<?php $custom_css = get_option("custom_css", "template");
?>
<?php only_admin_access(); ?>

<style>
    html,body{
        direction: initial;
    }

    .CodeMirror-code{
        min-height: 80vh;
    }

</style>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.js"></script>
<script type="text/javascript">
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
                mw.tools.refresh(el)
            });
        }

</script>
<div class="holder"><textarea class="mw-ui-field w100 mw_option_field" dir="ltr" name="custom_css" id="custom_css_code_mirror" rows="30"
                    option-group="template" placeholder="Type your CSS code here"><?php print $custom_css ?></textarea></div>
<div class="mw-ui-btn-nav pull-right" id="csssave">
    <span onclick="savecss();" class="mw-ui-btn mw-ui-btn-invert"><?php _e('Save'); ?></span>
</div>
