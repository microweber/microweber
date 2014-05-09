<!DOCTYPE HTML>
<html>
<head>

{head}

<link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/mw_framework.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/liveadmin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print MW_INCLUDES_URL; ?>css/wysiwyg.css"/>
<script>
    mwAdmin = true;
</script>
<script>mw.require("jquery-ui.js");</script>
<script>mw.require("tools.js");</script>
<script>mw.require("url.js");</script>
<script>mw.require("events.js");</script>
<script>mw.require("wysiwyg.js");</script>
<script>

scaleHeight = function(){
  var pt = parseFloat(mw.$("#mw-iframe-editor-area").css("paddingTop"));
  var pb = parseFloat(mw.$("#mw-iframe-editor-area").css("paddingBottom"));
  var h = $(window).height() - mw.$("#mw-admin-text-editor").outerHeight() - pt - pb - 4;
  mw.$("#mw-iframe-editor-area").height(h);

}

_test = function(){
  scaleHeight = function(){};
  mw.$("#mw-iframe-editor-area").height('auto');
  parent.mw.$('iframe[name="'+window.name+'"]')[0].style.height =  $(document.body)[0].scrollHeight  + 'px';
  mw.$("#mw-admin-text-editor").hide();

  var sel = window.getSelection();
  var ed = mw.$('#mw-admin-text-editor');
  
  
  if(sel!= null && !sel.isCollapsed){
    var off = sel.getRangeAt(0).getBoundingClientRect();
    ed.width(335).show().css({position:'absolute', top:off.top - ed.height() - 7, left:off.left,border:'1px solid #ccc','boxShadow':'none'});
  }
  else{
     //ed.show().width('100%').css({position:'fixed', top:0, left:0,border:'','boxShadow':''});
     ed.hide();
  }
}

PrepareEditor = function(){
  if(window.name.contains("mweditor")){
     HOLD = false;
     edmwdoc = mw.tools.parseHtml('');
     mw.on.DOMChange(mwd.getElementById('mw-iframe-editor-area'), function(){
         _test()

          el = $(this);
          typeof HOLD === 'number' ? clearTimeout(HOLD) : '';
          HOLD = setTimeout(function(){
                var html = el.html();
                edmwdoc.body.innerHTML = html;
                $('[contenteditable]', edmwdoc.body).removeAttr('contenteditable');

                var html = edmwdoc.body.innerHTML;
                parent.mw.$("iframe#"+window.name).trigger("change", html);
          }, 600);
     });
  }
}

$(window).load(function(){


scaleHeight();
_test();
    __area = mwd.getElementById('mw-iframe-editor-area');
   $(window).resize(function(){
   scaleHeight()
});

  mw.$("#mw-iframe-editor-area").bind("mouseup", function(e){
    if(!e.target.isContentEditable){
        var el = this.querySelector('.edit');
        if(el !== null){
            // Put Cursor at the end
            mw.wysiwyg.select_all(el);
            var range = window.getSelection().getRangeAt(0);
            range.collapse(false);
            el.focus();
            window.getSelection().collapseToEnd(); // Chrome
        }
    }
  });

});


$(document).ready(function(){


$(".module").attr("contentEditable", false);

$(".edit").attr("contentEditable", true);

$(mwd.body).bind('keydown keyup keypress mouseup mousedown click paste selectstart', function(e){
  _test()
  var el= $(e.target);
  if(mw.tools.hasClass(e.target.className, 'module') || mw.tools.hasParentsWithClass(e.target, 'module')){
    e.preventDefault();
    //var curr =  mw.tools.hasClass(e.target.className, 'module') ? e.target : mw.tools.firstParentWithClass(e.target, 'module');
  }
  if(el.hasClass('edit')){
    el.addClass('changed');
  }
  else{
     $(mw.tools.firstParentWithClass(e.target, 'edit')).addClass('changed');
  }
});



});


$(window).load(function(){
  var master = mwd.getElementById('the_admin_editor');
  master.addEventListener("DOMAttrModified", function(e){
      var attr = e.attrName;
      if(attr == 'style'){
        parent.mw.$("#" + window.name).css({
          height:$(master).height() + 4
        });
      }
  }, false);




      $(mwd.body).bind('mousedown', function(e){
        parent.mw.$(".mw-ui-category-selector").hide();
      });



      mw.$("#mw-iframe-editor-area .module").each(function(){

          if($(this).dataset("type") == 'pictures'){

            $(this).css("cursor", "pointer").click(function(e){
              if(!mw.tools.hasClass(e.target, 'mw-close')) {
                parent['QTABS'].set(0);
                parent.mw.tools.scrollTo('#quick-add-post-options', false, 20);
              }

            });


          }

      });

      mw.$("#mw-iframe-editor-area").bind("keyup", function(){
        parent.mw.$('#'+window.name).trigger("editorKeyup");
        $(mwd.body).addClass('editorKeyup');
      });
      mw.$("#mw-iframe-editor-area").bind("mousedown", function(e){
        if(mw.tools.hasParentsWithClass(e.target, 'mw-admin-editor-area')){
            mw.$('.mw-tooltip-insert-module').remove();
        }
      });

      mw.drag.plus.init();

});


delete_module = function(inner_node){
    mw.tools.confirm(mw.msg.del, function(){
      $(mw.tools.firstParentWithClass(inner_node, 'module')).remove();
    });
}
</script>



<style type="text/css">

*{
  margin: 0;
  padding: 0;
}

.mw-admin-editor #mw-iframe-editor-area{
  line-height: 1.85;
  padding: 15px 0;
}





.mw-plain-module-name {
	display: block;
	padding-top: 5px;
}

.mw-admin-editor{
    background: none;
}

.mw-wysiwyg-module-helper{
  min-height: 23px;
}
img{
  max-width: 100%;
}


::-webkit-scrollbar {
    width: 10px;
    background:#E9E6E6;
}

::-webkit-scrollbar-thumb {
    background: #787878;
}





</style>
<script>

mw.require("plus.js");

</script>
</head>
<body style="padding: 0;margin: 0;">
<?php mw_var('plain_modules', true);
  if(is_admin() == false){
    //exit('Must be admin');
  }
 ?>
 

<div class="mw-admin-editor" id="the_admin_editor">
 <?php include MW_INCLUDES_DIR . DS . 'toolbar' . DS ."wysiwyg_admin.php"; ?>
  <div class="mw-admin-editor-area" id="mw-iframe-editor-area" tabindex="0" autofocus="autofocus">{content}</div>
</div>

<?php mw_var('plain_modules', false); ?>

<span class="mw-plus-top">+</span>
<span class="mw-plus-bottom">+</span>
<div style="display: none" id="modules-list">
    <module type="admin/modules/list"/ class="modules-list-init">
</div>

</body>
</html>