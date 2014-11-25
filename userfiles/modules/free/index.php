<?php

    $html =  get_option('html', $params['id']);
    $rand = uniqid();
?>

<?php if(is_admin() && is_live_edit()){ ?>


<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script>
mw.require("options.js");


validateFreeHTML = window.validateFreeHTML || function(html){
  var doc = mw.tools.parseHtml(html);
  mw.$('.ui-resizable-handle', doc).remove();
  mw.$('.ui-draggable', doc).removeClass('ui-draggable ui-resizable ui-draggable-dragging');
  return doc.body.innerHTML;
}

$(document).ready(function(){

    var freeholder = mwd.getElementById('free-element-<?php print $rand; ?>');

    $(freeholder).bind('mousedown mouseup click', function(e){
        //e.stopPropagation();
    });


    freeholder.time = null;
    mw.$("#free-element-<?php print $rand; ?> .free")
    .resizable({
        stop:function(){
            mw.options.saveOption({
              group:'<?php print $params['id']; ?>',
              key:'html',
              value:validateFreeHTML(mw.$(freeholder).html())
            });
        }
    })
    .draggable({
        stop:function(){
            mw.options.saveOption({
              group:'<?php print $params['id']; ?>',
              key:'html',
              value:validateFreeHTML(mw.$(freeholder).html())
            });
        }
    });
    mw.$(freeholder).dblclick(function(e){
      if(e.target.nodeName === 'img'){

      }
    });
    mw.on.DOMChange(freeholder, function(){
        clearTimeout(freeholder.time);
        freeholder.time = setTimeout(function(){
              mw.$("#free-element-<?php print $rand; ?> .free").not('.ui-draggable').draggable({
                  stop:function(){
                     mw.options.saveOption({
                      group:'<?php print $params['id']; ?>',
                      key:'html',
                      value:validateFreeHTML(mw.$(freeholder).html())
                    });
                  }
              });
        }, 333);
    });
});

</script>

<?php } ?>
<div class="free-element" contenteditable="false" id="free-element-<?php print $rand; ?>">
    <?php print $html; ?>
</div>

