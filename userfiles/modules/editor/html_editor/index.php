<?php only_admin_access(); ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/codemirror.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/mode/css/css.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/mode/php/php.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/mode/xml/xml.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/addon/selection/selection-pointer.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/addon/lint/lint.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/addon/hint/html-hint.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/addon/hint/xml-hint.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/addon/hint/javascript-hint.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/addon/edit/closetag.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/addon/edit/matchbrackets.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/addon/edit/matchtags.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/addon/fold/foldcode.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/codemirror.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.16.0/theme/material.css">
<script src="<?php print modules_url()?>editor/html_editor/html_editor.js"></script>




<script type="text/javascript">
    $time_out_handle = 0;
    $(document).ready(function () {
        editor = CodeMirror.fromTextArea(document.getElementById("custom_html_code_mirror"), {
            lineNumbers: true,
            indentWithTabs: true,
            matchBrackets: true,
            extraKeys: {"Ctrl-Space": "autocomplete"},
            mode: "htmlmixed"
        });

        editor.setOption("theme", 'material');
		 
        editor.on("change", function (cm, change) {
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
