<?php must_have_access(); ?>


<script type="text/javascript">
    mw.require('<?php print modules_url()?>editor/html_editor/html_editor.js');
    mw.require('options.js');
 </script>



 <style>



    .CodeMirror,
    #select_edit_field_wrap { height: 100%; }





    .htmleditliframe{
        width:100%;
        height: 120px;
        overflow: hidden;
        position: relative;
    }

    .htmleditliframe:after{
        position: absolute;
        content: '';
        display: block;
        z-index: 1;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .htmleditliframe iframe{
        overflow: hidden;
        width:300%;
        height: 360px;
        transform: scale(.33333);
        transform-origin: 0 0;
        pointer-events: none;
    }

</style>

<script>
    mw.lib.require('codemirror')
    mw.require('<?php print modules_url()?>editor/selector.css');
     // mw.require('<?php print modules_url()?>microweber/api/libs/codemirror');



</script>
<script >
    var $time_out_handle = 0;
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
                    "Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); },
                    "Ctrl-J": "toMatchingTag"
                },
                mode: {
                    name: "htmlmixed",
                    scriptTypes: [{matches: /\/x-handlebars-template|\/x-mustache/i,
                        mode: null},
                        {matches: /(text|application)\/(x-)?vb(a|script)/i,
                            mode: "vbscript"}]
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


</script>
<script>

   function format_code() {
       html_code_area_editor.setSelection({
               'line':html_code_area_editor.firstLine(),
               'ch':0,
               'sticky':null
           },{
               'line':html_code_area_editor.lastLine(),
               'ch':0,
               'sticky':null
           },
           {scroll: false});
       //auto indent the selection
       html_code_area_editor.indentSelection("smart");

       html_code_area_editor.setSelection({
               'line':html_code_area_editor.firstLine(),
               'ch':0,
               'sticky':null
           },{
               'line':html_code_area_editor.firstLine(),
               'ch':0,
               'sticky':null
           },
           {scroll: false});



       //I tried to fire a mousdown event on the code to unselect everything but it does not work.
       //$('.CodeMirror-code', $codemirror).trigger('mousedown');
   }

</script>
<script>





    $(document).ready(function () {

        mw.html_editor.init();

    })


</script>

<div class="mw-ui-row">
  <div class="mw-ui-col" style="width: 400px;">
    <div class="mw-ui-col-container">
      <div class="mw-ui-box">
        <div class="mw-ui-box-header">
          <span class="mw-icon-gear"></span><span><?php _e('Sections'); ?></span>
        </div>
        <div class="mw-ui-box-content selector-box" id="select_edit_field_container"><div id="select_edit_field_wrap"></div></div>
      </div>
    </div>
  </div>
    <div class="mw-ui-col">
      <div class="mw-ui-col-container">
          <div id="custom_html_code_mirror_container">
                <textarea class="mw-ui-field w100" name="custom_html" id="custom_html_code_mirror" rows="30"
                  option-group="template" placeholder="<?php _e('Type your HTML code here'); ?>"></textarea>

          </div>
          <div class="mw-ui-btn-nav pull-right" id="save">

              <span onclick="format_code();" class="mw-ui-btn" ><?php _e('Format code'); ?></span>
              <span onclick="mw.html_editor.apply();" class="mw-ui-btn" ><?php _e('Update'); ?></span>
              <span onclick="mw.html_editor.apply_and_save();" class="mw-ui-btn mw-ui-btn-invert"><?php _e('Update'); ?> <?php _e('and'); ?> <?php _e('save'); ?></span>
          </div>
      </div>
  </div>
</div>






