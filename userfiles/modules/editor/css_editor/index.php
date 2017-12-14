<?php $custom_css = get_option("custom_css", "template");
?>
<?php only_admin_access(); ?>

<!--<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.js"></script>-->
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.min.js"></script>-->
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/htmlmixed/htmlmixed.min.js"></script>-->
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/php/php.min.js"></script>-->
<!--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.css">-->

<script type="text/javascript">
    mw.require('options.js');

    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/htmlmixed/htmlmixed.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/php/php.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.css');
</script>

<?php

/*
 *




<script type="text/javascript">
    mw.css_ed = {};

    $(document).ready(function () {

        mw.css_ed.init_element_picker();


    });

    mw.css_ed.init_element_picker = function(){

        $(window.parent.document.body).on('mousemove.css_element_picker', function(event) {
            var target = event.target || event.srcElement;
            var id = target.id
            $('#element_picker_preview').html( id);
        })

        $(window.parent.document.body).on('click.css_element_picker', function(event) {
            var target = event.target || event.srcElement;
            var id = target.id

            chosen_target = id;
            $('#element_picker_picked').html(chosen_target);
            $(window.parent.document.body).off('click.css_element_picker');
            $(window.parent.document.body).off('mousemove.css_element_picker');
        })

//        $(mwd.body).mousemove(function(event) {
//
//
//
//
//
//
//        });
    }
</script>
<button id="pick_element_btn" onclick="mw.css_ed.init_element_picker()">pick element</button>
<div id="element_picker_preview"></div>
<div id="element_picker_picked"></div>


*/

?>










<script type="text/javascript">
    $(document).ready(function () {
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
</script>
<script type="text/javascript">
    $time_out_handle = 0;
    $(document).ready(function () {
        css_code_area_editor = CodeMirror.fromTextArea(document.getElementById("custom_css_code_mirror"), {
            lineNumbers: true,
            indentWithTabs: true,
			matchBrackets: true,  
            extraKeys: {"Ctrl-Space": "autocomplete"},
            mode: {
                name: "css", globalVars: true

            }
        });

        css_code_area_editor.setSize("100%", "100%");

        css_code_area_editor.on("change", function (cm, change) {
            var custom_css_code_mirror = document.getElementById("custom_css_code_mirror")
            custom_css_code_mirror.value = cm.getValue();

            window.clearTimeout($time_out_handle);
            $time_out_handle = window.setTimeout(function () {
                $(custom_css_code_mirror).change();
            }, 2000);

        });


    })

</script>
<textarea class="mw-ui-field w100 mw_option_field" name="custom_css" id="custom_css_code_mirror" rows="30"
          option-group="template" placeholder="Type your CSS code here"><?php print $custom_css ?></textarea>
