<?php only_admin_access(); ?>

<script src="<?php print modules_url()?>editor/html_editor/html_editor.js"></script>

<script type="text/javascript">
    mw.require('options.js');

    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/htmlmixed/htmlmixed.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/php/php.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.css');
</script>


<script type="text/javascript">
    $time_out_handle = 0;
    $(document).ready(function () {
        html_code_area_editor = CodeMirror.fromTextArea(document.getElementById("custom_html_code_mirror"), {
            lineNumbers: true,
            indentWithTabs: true,
            matchBrackets: true,
            extraKeys: {"Ctrl-Space": "autocomplete"},
            mode: "htmlmixed"
        });

        html_code_area_editor.setOption("theme", 'material');

        html_code_area_editor.on("change", function (cm, change) {
            var custom_html_code_mirror = document.getElementById("custom_html_code_mirror")
            custom_html_code_mirror.value = cm.getValue();

            window.clearTimeout($time_out_handle);
            $time_out_handle = window.setTimeout(function () {
                $(custom_html_code_mirror).change();
            }, 2000);

        });


    })


</script>
<script>





    $(document).ready(function () {

        mw.html_editor.init();


    })


</script>
<table>
  <tr>
    <td>
      <div id="select_edit_field_wrap"></div>

    </td>
    <td>
      <button onclick="mw.html_editor.apply();" class="mw-ui-btn mw-ui-btn-invert"><?php _e('Apply'); ?></button>

    </td>
  </tr>
</table>


<textarea class="mw-ui-field w100" name="custom_html" id="custom_html_code_mirror" rows="30"
          option-group="template" placeholder="<?php _e('Type your HTML code here'); ?>"></textarea>
