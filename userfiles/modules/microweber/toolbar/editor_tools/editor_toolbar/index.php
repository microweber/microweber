<script>
    mw.require("<?php print mw_includes_url(); ?>css/ui.css");
    mw.require("<?php print mw_includes_url(); ?>css/admin.css");
    mw.require("<?php print mw_includes_url(); ?>css/wysiwyg.css");
</script>


<script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-core.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-cssclassapplier.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-selectionsaverestore.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-serializer.js"></script>

<script>mw.require("tools.js");</script>
<script>mw.require("url.js");</script>
<script>mw.require("events.js");</script>
<script>mw.require("wysiwyg.js");</script>

<script>
SetValueTime = 600;
SetValueTimeout = null;
SetValue = function(){
  clearTimeout(SetValueTimeout);
  SetValueTimeout = setTimeout(function(){
      if(mw.is.ie){
        mw.$('[contenteditable] [contenteditable]').removeAttr('contenteditable');
        mw.wysiwyg.nceui();
      }

      var newval = Editable.innerHTML;
      if(newval != OLDVALUE) {

          OLDVALUE = newval;
          if(editorArea.nodeName === 'TEXTAREA'){
              editorArea.value = newval;
          }
          else{
              editorArea.innerHTML = newval;
          }
          var frame = parent.mw.$('#' + window.name);

          frame[0].value = newval;
          if(window.pauseChange === false){
            frame.trigger('change');
            parent.mw.$(frame[0].richtextEditorSettings.element).trigger('change');
          }

      }

  }, SetValueTime);

}

SetHeight = function(height){
   var height = height || window.richtextEditorSettings.height;
   if(height == 'auto'){
        setInterval(function(){
            parent.mw.$('#' + this.name).height($('#editor-master').height())
        }, 222);
   }
   else{
      var height = parseFloat(height);
      var _height =  height - mwd.getElementById('mw-admin-text-editor').offsetHeight;
      Editable.style.height =  _height + 'px';
   }
}

$(window).load(function(){
   mw.$("#mw-admin-text-editor").bind('mousedown', function(e){
      e.preventDefault();
      SetValue()
   });
   mw.wysiwyg.init();
   mw.wysiwyg.nceui();
   Editable = mwd.getElementById('editor-area');

   Editable.addEventListener("paste", function(e){
      // mw.wysiwyg.paste(e);
   });

  setTimeout(function(){
     mw.on.DOMChange(Editable, function(){
      SetValue();
     })
   }, SetValueTime);

   SetHeight();
   mw.linkTip.init(Editable);

});

</script>

<style>


img{
  max-width: 100%;
}

#editor-area{
  padding: 10px;
  overflow: auto;
  border: 1px solid #eee;
  height: auto;
  min-height: 200px;
  clear: both;
}
.editor_wrapper{
 border: 1px solid #eee;
 border-bottom: none;
 clear: both;
}

#mw-admin-text-editor{
  clear: both;
  position: relative;
  -moz-user-select: none;
  -webkit-user-select: none;
  -webkit-touch-callout: none;
  -o-user-select: none;
  -khtml-user-select: none;
   user-select: none;
}

#editor-area ul, #editor-area ol{
    padding-left: 16px;
}

#editor-area a{
  text-decoration: underline;
}

</style>
<div id="editor-master">
<div id="mw-admin-text-editor" class="mw_editor">
        <div class="editor_wrapper">

            <span class="mw_editor_btn mw_editor_image" data-command="custom-media" title="<?php _e("Insert Media"); ?>"><span class="ed-ico"></span></span>

            <span class="mw_dlm"></span>

            <span class="mw_editor_btn mw_editor_bold" data-command="bold" title="<?php _e("Bold"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_italic" data-command="italic" title="<?php _e("Italic"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_underline" data-command="underline" title="<?php _e("Underline"); ?>"><span class="ed-ico"></span></span>

            <span class="mw_dlm"></span>

            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyleft mw-align-left" data-command="justifyLeft" title="<?php _e("Align Left"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifycenter mw-align-center" data-command="justifyCenter" title="<?php _e("Align Center"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyright mw-align-right" data-command="justifyRight" title="<?php _e("Align Right"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_alignment mw_editor_justifyfull mw-align-justify" data-command="justifyFull" title="<?php _e("Align Both Sides"); ?>"><span class="ed-ico"></span></span>


            <span class="mw_dlm"></span>

            <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_format mw_editor_format" id="format_main" title="<?php _e("Format"); ?>" data-value="">
                <span class="mw-dropdown-value">
                    <span class="mw-dropdown-arrow"></span>
                    <span class="mw-dropdown-val"><?php _e("Format"); ?></span>
                </span>
              <div class="mw-dropdown-content">
                <ul>
                  <li value="h1"><a href="javascript:;"><h1><?php _e("Heading"); ?> 1</h1></a></li>
                  <li value="h2"><a href="javascript:;"><h2><?php _e("Heading"); ?> 2</h2></a></li>
                  <li value="h3"><a href="javascript:;"><h3><?php _e("Heading"); ?> 3</h3></a></li>
                  <li value="h4"><a href="javascript:;"><h4><?php _e("Heading"); ?> 4</h4></a></li>
                  <li value="h5"><a href="javascript:;"><h5><?php _e("Heading"); ?> 5</h5></a></li>
                  <li value="h6"><a href="javascript:;"><h6><?php _e("Heading"); ?> 6</h6></a></li>
                  <li value="p"><a href="javascript:;"><p><?php _e("Paragraph"); ?></p></a></li>
                </ul>
              </div>
            </div>



            <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_font_family mw_editor_fontfamily" id="font_family_selector_main" title="<?php _e("Font"); ?>" data-value="Arial">
              <span class="mw-dropdown-value">
                  <span class="mw-dropdown-arrow"></span>
                  <span class="mw-dropdown-val">Arial</span>
              </span>
              <div class="mw-dropdown-content">
                <ul>
                  <li value="Arial"><a href="javascript:;" style="font-family:Arial">Arial</a></li>
                  <li value="Tahoma"><a href="javascript:;" style="font-family:Tahoma">Tahoma</a></li>
                  <li value="Verdana"><a href="javascript:;" style="font-family:Verdana">Verdana</a></li>
                  <li value="Georgia"><a href="javascript:;" style="font-family:Georgia">Georgia</a></li>
                  <li value="Times New Roman"><a href="javascript:;" style="font-family: 'Times New Roman'">Times New Roman</a></li>
                </ul>
              </div>
            </div>
            <div class="mw-dropdown mw-dropdown-type-wysiwyg mw_dropdown_action_font_size mw_editor_fontsize" id="font_size_selector_main" title="<?php _e("Font Size"); ?>">
                <span class="mw-dropdown-value">
                  <span class="mw-dropdown-arrow"></span>
                  <span class="mw-dropdown-val" ><?php _e("Size"); ?></span>
                </span>
                <div class="mw-dropdown-content">
                    <ul>
                      <li value="10"><a href="javascript:;">10</a></li>
                      <li value="11"><a href="javascript:;">11</a></li>
                      <li value="12"><a href="javascript:;">12</a></li>
                      <li value="14"><a href="javascript:;">14</a></li>
                      <li value="16"><a href="javascript:;">16</a></li>
                      <li value="18"><a href="javascript:;">18</a></li>
                      <li value="20"><a href="javascript:;">20</a></li>
                      <li value="20"><a href="javascript:;">22</a></li>
                      <li value="24"><a href="javascript:;">24</a></li>
                      <li value="36"><a href="javascript:;">36</a></li>
                      <li value="72"><a href="javascript:;">72</a></li>
                    </ul>
                </div>
            </div>



            <span class="mw_dlm"></span>

            <span class="mw_editor_btn mw_editor_ol" data-command="insertorderedlist" title="<?php _e("Ordered List"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_ul" data-command="insertunorderedlist" title="<?php _e("Unordered List"); ?>"><span class="ed-ico"></span></span>

            <span class="mw_dlm"></span>

            <span class="mw_editor_btn mw_editor_link" data-command="custom-link" title="<?php _e("Add/Edit Link"); ?>"><span class="ed-ico"></span></span>
            <span class="mw_editor_btn mw_editor_unlink" data-command="custom-unlink" title="<?php _e("Remove Link"); ?>"><span class="ed-ico"></span></span>

            <span class="mw_editor_btn mw_editor_remove_formatting" data-command="removeformat" title="<?php _e("Remove Formatting"); ?>"><span class="ed-ico"></span></span>


        </div>
</div>
<div id="editor-area" contenteditable="true"></div>
</div>
<script>OLDVALUE = mwd.getElementById('editor-area').innerHTML</script>