

<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/ui.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/admin.css"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/wysiwyg.css"/>
<script src="<?php print mw_includes_url(); ?>api/jquery-ui.js"></script>
<script>
    mwAdmin = true; 

</script>
<script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-core.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-cssclassapplier.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-selectionsaverestore.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/libs/rangy/rangy-serializer.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/tools.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/url.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/events.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/wysiwyg.js"></script>
<script src="<?php print(mw_includes_url()); ?>api/external_callbacks.js"></script>
 
<script>
 
GalleriesRemote = function(){
        if(parent.document.querySelector('#manage-galleries-holder') !== null){
          parent.mw.$('#manage-galleries-holder').empty()
            var galleries = document.querySelectorAll('.module[data-type="pictures"]'),
                  l = galleries.length,
                  i = 0;
              for( ; i<l; i++){
                  var gspan = parent.document.createElement('span');
                  gspan.className = 'image-manage-item';
                  gspan.forGallery = galleries[i];
                  var html = '';
                  var gallimgs = galleries[i].querySelectorAll('img'), gi = 0;
                  for( ; gi < 3; gi++ ){
                    if(!!gallimgs[gi]){
                       html+='<em class="bgimg" style="background-image:url('+gallimgs[gi].src+');"></em>';
                    }
                    else{
                        html+='<em class="mw-icon-image"></em>';
                    }
                  }
                  gspan.onclick = function(){
                    parent.mw.tools.scrollTo(this.forGallery, function(){
                      mw.tools.module_settings(this,undefined);
                      if(!!parent.QTABS){
                          parent.QTABS.unset(0);
                          parent.mw.$(".tip-box .mw-tooltip-arrow").css('left', -9999);
                      }
                    });
                  }
                  gspan.innerHTML = '<span class="manage-gallery-btn-holder">' +html + '</span><span><?php _e("Manage this gallery"); ?></span>';
                  parent.mw.$('#manage-galleries-holder').append(gspan);
              }
        }
    }
 

scaleHeight = function(){
  var pt = parseFloat(mw.$("#mw-iframe-editor-area").css("paddingTop"));
  var pb = parseFloat(mw.$("#mw-iframe-editor-area").css("paddingBottom"));
  var h = $(window).height() - mw.$("#mw-admin-text-editor").outerHeight() - pt - pb - 4;
  mw.$("#mw-iframe-editor-area").height(h);

}

ScaleFrame = function(){

  scaleHeight = function(){};
  var par_frame = parent.mw.$('iframe[name="'+window.name+'"]')[0];
  if(par_frame != undefined){
    parent.mw.$('iframe[name="'+window.name+'"]')[0].style.height =  $(document.body)[0].scrollHeight  + 'px';
    //mw.$("#mw-admin-text-editor").hide();
  }
  /*
  var sel = window.getSelection();
  var ed = mw.$('#mw-admin-text-editor');


  if(sel!= null && !sel.isCollapsed){
    var off = sel.getRangeAt(0).getBoundingClientRect();
    ed.width(335).show().css({position:'absolute', top:off.top - ed.height() - 7, left:off.left,border:'1px solid #ccc','boxShadow':'none'});
  }
  else{
     //ed.show().width('100%').css({position:'fixed', top:0, left:0,border:'','boxShadow':''});
     ed.hide();
  }  */
}

PrepareEditor = function(){
  if(window.name.contains("mweditor")){
     HOLD = false;
     edmwdoc = mw.tools.parseHtml('');
     var WYSIWYGArea = mwd.getElementById('mw-iframe-editor-area');

     mw.tools.foreachChildren(WYSIWYGArea, function(){
        if(this.nodeName === 'P'){
            mw.tools.addClass(this, 'element');
        }
     });

     mw.on.DOMChange(WYSIWYGArea, function(){
         ScaleFrame()
          el = $(this);
          typeof HOLD === 'number' ? clearTimeout(HOLD) : '';
          HOLD = setTimeout(function(){
                var html = el.html();
                edmwdoc.body.innerHTML = html;
                $('[contenteditable]', edmwdoc.body).removeAttr('contenteditable');
                var html = edmwdoc.body.innerHTML;
                parent.mw.$("iframe#"+window.name).trigger("change", html);
          }, 600);

          top.mw.askusertostay = true;
          parent.mw.askusertostay = true;

       setTimeout(function(){
         ScaleFrame();
       }, 333)

     });


     $(window).bind('moduleLoaded', function(){
         setTimeout(function(){

         ScaleFrame();
         var imgs = mwd.querySelectorAll('.edit img'), l = imgs.length, i = 0;
         for( ; i < l; i++){
            $(imgs[i]).bind('load', function(){
                ScaleFrame();
            });
         }

         }, 333)
     });

  }
}

$(window).load(function(){


  mw.linkTip.init(mwd.getElementById('mw-iframe-editor-area'));


scaleHeight();
ScaleFrame();
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




$(".edit").attr("contenteditable", true);
$(".module").attr("contenteditable", false);
$(mwd.body).bind('keydown keyup keypress mouseup mousedown click paste selectstart', function(e){
  ScaleFrame()
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
      $(mwd.body).bind('mousedown', function(e){
        parent.mw.$(".mw-ui-category-selector").hide();
        parent.$(parent.mwd.body).trigger('mousedown');
      });
      $(mwd.body).bind('mouseup', function(e){
        parent.$(parent.mwd.body).trigger('mouseup');
      });
      $(mwd.body).bind('click', function(e){
        parent.$(parent.mwd.body).trigger('click');
      });


      mw.$("#mw-iframe-editor-area").bind("keyup", function(e){
        parent.mw.$('#'+window.name).trigger("editorKeyup");
        $(mwd.body).addClass('editorKeyup');
        if(e.ctrlKey){
            if(e.keyCode === 65){
                mw.wysiwyg.select_all(this);
                return false;
            }
        }
      });
      mw.$("#mw-iframe-editor-area").bind("mousedown", function(e){
        if(mw.tools.hasParentsWithClass(e.target, 'mw-admin-editor-area')){
            mw.$('.mw-tooltip-insert-module').remove();
            mw.drag.plus.locked = false;
        }
      });



    GalleriesRemote();

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
  min-height: 200px;
}

.mw-admin-editor #mw-iframe-editor-area:empty{
  background-color: #efecec;
}

.mw-plain-module-name {
	display: block;
	padding-top: 5px;
}

.mw-admin-editor{
    background: none;
}
#mw-admin-text-editor{
  opacity:0;
  -webkit-transition: opacity 0.3s;
  -moz-transition: opacity 0.3s;
  -ms-transition: opacity 0.3s;
  -o-transition: opacity 0.3s;
  transition: opacity 0.3s;
}

#mw-admin-text-editor.show-editor{
  opacity:1;
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


.editor_wrapper_fixed{
  box-shadow:0 0 6px -2px #000000;
}

.mw_edit_btn.mw_edit_delete{
  display: none;
}


</style>

<?php $mainclass = ''; ?>

<?php if(isset($params['live_edit'])){ ?>

<?php $mainclass = 'admin-live-edit-editor'; ?>

<link href="<?php print(mw_includes_url()); ?>css/liveedit.css" rel="stylesheet" type="text/css"/>
<script src="<?php print mw_includes_url(); ?>api/liveedit.js"></script>
<script src="<?php print mw_includes_url(); ?>api/columns.js"></script>
<script src="<?php print mw_includes_url(); ?>api/plus.js"></script>



<script>
  //mw.require("liveedit.js");
//  mw.require("columns.js");
//  mw.require("plus.js");
  $(window).bind('load', function(){
      mw.drag.plus.init('#mw-iframe-editor-area');
      mw.drag.columns.init();
      mw.drag.saveDisabled = true;
      mw.drag.draftDisabled = true;
  });
</script>

<?php } ?>

<script>
window.onblur = function(){
    var ed = document.getElementById('mw-admin-text-editor');
    if(ed !== null){
        mw.tools.removeClass(ed, 'show-editor');
    }
}
window.onfocus = function(){
    var ed = document.getElementById('mw-admin-text-editor');
    if(ed !== null){
        mw.tools.addClass(ed, 'show-editor');
    }
}

    $(document).ready(function(){
		
		
		/*autofocus="autofocus" contenteditable="true"*/
		$('#the_admin_editor').find('.edit').attr('contenteditable',true);;

		
        $(mwd).bind('mousedown', function(e){
            if(!e.target.isContentEditable){
                var target = null;
                if(e.target.nodeName === 'A'){
                  var target = e.target;
                }
                else if(mw.tools.hasParentsWithTag(e.target, 'a')){
                  var target = mw.tools.firstParentWithTag(e.target, 'a');
                }
                if(target !== null){
                    if(target.target == '' || target.target == '_self'){
                         target.target = '_top';
                    }
                }
            }
        });
        });

</script>

<?php mw_var('plain_modules', true);
  if(is_admin() == false){
    //exit('Must be admin');
  }
 ?>
 

<div class="mw-admin-editor <?php print $mainclass; ?>" id="the_admin_editor">
 <?php //include mw_includes_path() . DS . 'toolbar' . DS ."wysiwyg_admin.php"; ?>
 <?php include mw_includes_path() . DS . 'toolbar' . DS ."wysiwyg_tiny.php"; ?>
  <div class="mw-admin-editor-area" id="mw-iframe-editor-area" tabindex="0" >{content}</div>
</div>

<?php mw_var('plain_modules', false); ?>

<span class="mw-plus-top">+</span>
<span class="mw-plus-bottom">+</span>
<div style="display: none" id="plus-modules-list">
<input type="text" class="mw-ui-searchfield" />
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs pull-left">
        <span class="mw-ui-btn active"><span class="mw-icon-module"></span><?php _e("Modules"); ?></span>
        <span class="mw-ui-btn"><span class="mw-icon-template"></span><?php _e("Layouts"); ?></span>
    </div>

    <div class="mw-ui-box">
        <div class="module-bubble-tab" style="display: block"><module type="admin/modules/list" class="modules-list-init module-as-element"></div>
        <div class="module-bubble-tab"><module type="admin/modules/list_layouts" class="modules-list-init module-as-element"></div>
        <div class="module-bubble-tab-not-found-message"></div>
    </div>
</div>

