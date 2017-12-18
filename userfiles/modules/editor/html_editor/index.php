<?php only_admin_access(); ?>

<script src="<?php print modules_url()?>editor/html_editor/html_editor.js"></script>

<script type="text/javascript">
    mw.require('options.js');

    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/xml/xml.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/javascript/javascript.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/css/css.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/vbscript/vbscript.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/htmlmixed/htmlmixed.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/mode/php/php.min.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.min.css');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/display/autorefresh.js');
    mw.require('//cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/addon/selection/selection-pointer.js');
</script>






<!--
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify-css.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify-html.js"></script>

 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify-css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.7.4/beautify-html.min.js"></script>

<script src="https://cdn.rawgit.com/beautify-web/js-beautify/v1.7.4/js/lib/beautify.js"></script>
<script src="https://cdn.rawgit.com/beautify-web/js-beautify/v1.7.4/js/lib/beautify-css.js"></script>
<script src="https://cdn.rawgit.com/beautify-web/js-beautify/v1.7.4/js/lib/beautify-html.js"></script>
<!--<link rel="stylesheet" href="https://codemirror.net/lib/codemirror.css">
<link rel="stylesheet" href="https://codemirror.net/theme/3024-day.css">
<link rel="stylesheet" href="https://codemirror.net/theme/3024-night.css">
<link rel="stylesheet" href="https://codemirror.net/theme/abcdef.css">
<link rel="stylesheet" href="https://codemirror.net/theme/ambiance.css">
<link rel="stylesheet" href="https://codemirror.net/theme/base16-dark.css">
<link rel="stylesheet" href="https://codemirror.net/theme/bespin.css">
<link rel="stylesheet" href="https://codemirror.net/theme/base16-light.css">
<link rel="stylesheet" href="https://codemirror.net/theme/blackboard.css">
<link rel="stylesheet" href="https://codemirror.net/theme/cobalt.css">
<link rel="stylesheet" href="https://codemirror.net/theme/colorforth.css">
<link rel="stylesheet" href="https://codemirror.net/theme/dracula.css">
<link rel="stylesheet" href="https://codemirror.net/theme/duotone-dark.css">
<link rel="stylesheet" href="https://codemirror.net/theme/duotone-light.css">
<link rel="stylesheet" href="https://codemirror.net/theme/eclipse.css">
<link rel="stylesheet" href="https://codemirror.net/theme/elegant.css">
<link rel="stylesheet" href="https://codemirror.net/theme/erlang-dark.css">
<link rel="stylesheet" href="https://codemirror.net/theme/hopscotch.css">
<link rel="stylesheet" href="https://codemirror.net/theme/icecoder.css">
<link rel="stylesheet" href="https://codemirror.net/theme/isotope.css">
<link rel="stylesheet" href="https://codemirror.net/theme/lesser-dark.css">
<link rel="stylesheet" href="https://codemirror.net/theme/liquibyte.css">
<link rel="stylesheet" href="https://codemirror.net/theme/material.css">
<link rel="stylesheet" href="https://codemirror.net/theme/mbo.css">
<link rel="stylesheet" href="https://codemirror.net/theme/mdn-like.css">
<link rel="stylesheet" href="https://codemirror.net/theme/midnight.css">
<link rel="stylesheet" href="https://codemirror.net/theme/monokai.css">
<link rel="stylesheet" href="https://codemirror.net/theme/neat.css">
<link rel="stylesheet" href="https://codemirror.net/theme/neo.css">
<link rel="stylesheet" href="https://codemirror.net/theme/night.css">
<link rel="stylesheet" href="https://codemirror.net/theme/panda-syntax.css">
<link rel="stylesheet" href="https://codemirror.net/theme/paraiso-dark.css">
<link rel="stylesheet" href="https://codemirror.net/theme/paraiso-light.css">
<link rel="stylesheet" href="https://codemirror.net/theme/pastel-on-dark.css">
<link rel="stylesheet" href="https://codemirror.net/theme/railscasts.css">
<link rel="stylesheet" href="https://codemirror.net/theme/rubyblue.css">
<link rel="stylesheet" href="https://codemirror.net/theme/seti.css">
<link rel="stylesheet" href="https://codemirror.net/theme/solarized.css">
<link rel="stylesheet" href="https://codemirror.net/theme/the-matrix.css">
<link rel="stylesheet" href="https://codemirror.net/theme/tomorrow-night-bright.css">
<link rel="stylesheet" href="https://codemirror.net/theme/tomorrow-night-eighties.css">
<link rel="stylesheet" href="https://codemirror.net/theme/ttcn.css">
<link rel="stylesheet" href="https://codemirror.net/theme/twilight.css">
<link rel="stylesheet" href="https://codemirror.net/theme/vibrant-ink.css">
<link rel="stylesheet" href="https://codemirror.net/theme/xq-dark.css">
<link rel="stylesheet" href="https://codemirror.net/theme/xq-light.css">-->
<link rel="stylesheet" href="https://codemirror.net/lib/codemirror.css">
<link rel="stylesheet" href="https://codemirror.net/theme/material.css">
<!--<link rel="stylesheet" href="https://codemirror.net/theme/zenburn.css">-->

<style>
    .CodeMirror, #select_edit_field_wrap { height: 100%; }

    #select_edit_field li{
      margin-left: 10px;
      padding: 5px 10px;
    }
    #select_edit_field > li{
      font-weight: bold;
    }
    #select_edit_field > li li{
      font-weight: normal;
    }

</style>

<script type="text/javascript">
    $time_out_handle = 0;
    $(document).ready(function () {
        html_code_area_editor = CodeMirror.fromTextArea(document.getElementById("custom_html_code_mirror"), {
            lineNumbers: true,
            lineWrapping: true,

            indentWithTabs: true,
            matchBrackets: true,
            extraKeys: {"Ctrl-Space": "autocomplete", "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
            mode: "htmlmixed",

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


    })


</script>
<script>





    $(document).ready(function () {

        mw.html_editor.init();




    })


</script>

<div class="mw-ui-row">
  <div class="mw-ui-col" style="width: 200px;">
    <div class="mw-ui-col-container">
      <div class="mw-ui-box   ">
          <div class="mw-ui-box-header">
<span class="mw-icon-gear"></span><span><?php _e('Sections'); ?></span>
</div>
          <div class="mw-ui-box-content"><div id="select_edit_field_wrap"></div></div>
        </div>

    </div>
  </div>
    <div class="mw-ui-col">
      <div class="mw-ui-col-container">
        <textarea class="mw-ui-field w100" name="custom_html" id="custom_html_code_mirror" rows="30"
            option-group="template" placeholder="<?php _e('Type your HTML code here'); ?>"></textarea>
      </div>
  </div>
</div>







<button onclick="mw.html_editor.apply();" class="mw-ui-btn mw-ui-btn-invert"><?php _e('Save'); ?></button>
