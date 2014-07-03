<?php
only_admin_access();
 
$edit_page_info = $data;;
include __DIR__ . DS . 'admin_toolbar.php'; ?>
<style>
#admin-user-nav {
	display: none;
}
</style>

<div id="post-states-tip" style="display: none">
  <div class="mw-ui-btn-vertical-nav post-states-tip-nav"> <span onclick="mw.admin.postStates.set('unpublish')" data-val="n" class="mw-ui-btn mw-ui-btn-medium btn-publish-unpublish btn-unpublish <?php if($data['is_active'] == 'n'): ?> active<?php endif; ?>"><span class="mw-icon-unpublish"></span>
    <?php _e("Unpublish"); ?>
    </span> <span onclick="mw.admin.postStates.set('publish')" data-val="y" class="mw-ui-btn mw-ui-btn-medium btn-publish-unpublish btn-publish <?php if($data['is_active'] != 'n'): ?> active<?php endif; ?>"><span class="mw-icon-check"></span>
    <?php _e("Publish"); ?>
    </span>
    <hr>
    <span class="mw-ui-btn mw-ui-btn-medium post-move-to-trash" onclick="mw.del_current_page('<?php print ($data['id'])?>');"><span class="mw-icon-bin"></span>Move to trash</span> </div>
</div>
<form method="post" <?php if($just_saved!=false) : ?> style="display:none;" <?php endif; ?> class="mw_admin_edit_content_form" action="<?php print site_url(); ?>api/save_content_admin" id="quickform-<?php print $rand; ?>">
  <input type="hidden" name="id" id="mw-content-id-value-<?php print $rand; ?>"  value="<?php print $data['id']; ?>" />
  <input type="hidden" name="subtype" id="mw-content-subtype-<?php print $rand; ?>"   value="<?php print $data['subtype']; ?>" />
  <input type="hidden" name="subtype_value" id="mw-content-subtype-value-<?php print $rand; ?>"   value="<?php print $data['subtype_value']; ?>" />
  <input type="hidden" name="content_type" id="mw-content-type-value-<?php print $rand; ?>"   value="<?php print $data['content_type']; ?>" />
  <input type="hidden" name="parent"  id="mw-parent-page-value-<?php print $rand; ?>" value="<?php print $data['parent']; ?>" class="" />
  <input type="hidden" name="layout_file"  id="mw-layout-file-value-<?php print $rand; ?>" value="<?php print $data['layout_file']; ?>"   />
  <input type="hidden" name="active_site_template"  id="mw-active-template-value-<?php print $rand; ?>" value="<?php print $data['active_site_template']; ?>"   />
  <div class="mw-ui-field-holder" id="slug-field-holder">
    <input
            type="hidden"
            id="content-title-field-master"
            name="title"
            onkeyup="slugFromTitle();"
            placeholder="<?php print $title_placeholder; ?>"
            value="<?php print $data['title']; ?>" />
    <input type="hidden" name="is_active" id="is_post_active" value="<?php print $data['is_active']; ?>" />
    <div class="edit-post-url">
      <div class="mw-ui-row">
        <div class="mw-ui-col" id="slug-base-url-column"><span class="view-post-site-url" id="slug-base-url"><?php print site_url(); ?></span></div>
        <div class="mw-ui-col"><span class="view-post-slug active" onclick="mw.slug.toggleEdit()"><?php print $data['url']; ?></span>
          <input name="content_url" id="edit-content-url" class="mw-ui-invisible-field mw-ui-field-small w100 edit-post-slug"  onblur="mw.slug.toggleEdit();mw.slug.setVal(this);slugEdited=true;" type="text" value="<?php print ($data['url'])?>" />
        </div>
      </div>
    </div>
    <script>
         slugEdited = false;
         slugFromTitle = function(){
            var slugField = mwd.getElementById('edit-content-url');
            var titlefield = mwd.getElementById('content-title-field');
            if(slugEdited === false){
                var slug = mw.slug.create(titlefield.value);
                mw.$('.view-post-slug').html(slug);
                mw.$('#edit-content-url').val(slug);
            }
         }
      </script> 
  </div>

    <?php if($data['content_type'] == 'page'){ ?>
    <div class="mw-admin-edit-page-primary-settings parent-selector ">
    <div class="mw-ui-field-holder">
      <div class="quick-parent-selector">
        <module
              type="content/selector"
              no-parent-title="No parent page"
              field-name="parent_id_selector"
              change-field="parent"
              selected-id="<?php print $data['parent']; ?>"
              remove_ids="<?php print $data['id']; ?>"
              recommended-id="<?php print $recommended_parent; ?>"   />
      </div>
    </div>
    </div>
    <?php } ?>
    <?php if($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
    <div class="mw-admin-edit-page-primary-settings content-category-selector">
    <div class="mw-ui-field-holder" style="padding-top: 0">
      <div class="mw-ui-field mw-tag-selector mw-ui-field-dropdown mw-ui-field-full" id="mw-post-added-<?php print $rand; ?>">
        <input type="text" class="mw-ui-invisible-field" placeholder="<?php _e("Click here to add to categories and pages"); ?>." style="width: 280px;" id="quick-tag-field" />
      </div>
      <div class="mw-ui-category-selector mw-ui-category-selector-abs mw-tree mw-tree-selector" id="mw-category-selector-<?php print $rand; ?>" >
        <?php if($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
        <module
                type="categories/selector"
                for="content"
      			active_ids="<?php print $data['parent']; ?>"
      			subtype="<?php print $data['subtype']; ?>"
      			categories_active_ids="<?php print $categories_active_ids; ?>"
      			for-id="<?php print $data['id']; ?>" />
        <?php endif; ?>
      </div>
    </div>
    </div>
    <?php endif; ?>

  <div class="mw-admin-edit-content-holder">
    <?php 
	 $data['recommended_parent'] = $recommended_parent;
	 $data['active_categories'] = $categories_active_ids; 
	 print load_module('content/edit_tabs',$data); ?>
  </div>
  <?php  if(isset($data['subtype']) and isset($data['content_type']) and ($data['content_type'] == 'page') and $data['subtype'] == 'dynamic'): ?>
  <module type="content/views/layout_selector" id="mw-quick-add-choose-layout-middle-pos" autoload="yes" template-selector-position="bottom" content-id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>" />
  <?php 
	 $data['recommended_parent'] = $recommended_parent;
	 $data['active_categories'] = $categories_active_ids; 
	 //print load_module('content/edit_default',$data); ?>
  <?php  else: ?>
  <div id="mw-admin-edit-content-main-area"> </div>
  <?php  endif; ?>
  <?php  if(isset($data['subtype']) and $data['subtype'] == 'dynamic'
or ($data['id'] == 0 and isset($data['content_type']) and $data['content_type'] == 'page')

): ?>
  <script>
     mw.$("#quick-add-post-options-item-template").show();
	 mw.$("#mw-edit-page-editor-holder").hide();
</script>
  <?php   endif; ?>
  <hr class="hr2">
  <?php event_trigger('mw_admin_edit_page_footer', $data); ?>
</form>
<div class="quick_done_alert" style="display: none" id="post-added-alert-<?php print $rand; ?>">
  <div class="quick-post-done">
    <h2>Well done, you have saved your changes. </h2>
    <label class="mw-ui-label"><small>Go to see them at this link</small></label>
    <a target="_top" class="quick-post-done-link" href="<?php print content_link($data['id']); ?>?editmode=y"><?php print content_link($data['id']); ?></a>
    <label class="mw-ui-label"><small>Or choose an action below</small></label>
    <a href="javascript:;" class="mw-ui-btn" onclick="mw.edit_content.close_alert();">Continue editing</a> <a href="javascript:;" class="mw-ui-btn mw-ui-btn-green" onclick="mw.edit_content.create_new();">Create New</a> </div>
</div>
<script>
    mw.require("content.js");
    mw.require("files.js");
</script> 
<script>
/* FUNCTIONS */





mw.edit_content = {};

mw.edit_content.saving = false;





mw.edit_content.create_new = function(){
   mw.$('#<?php print $module_id ?>').attr("content-id", "0");
   mw.$('#<?php print $module_id ?>').removeAttr("just-saved");
   mw.reload_module('#<?php print $module_id ?>');
};

mw.edit_content.close_alert = function(){
   	 mw.$('#quickform-<?php print $rand; ?>').show();
	 mw.$('#post-added-alert-<?php print $rand; ?>').hide();

};

 

mw.edit_content.load_editor  =  function(element_id){
      var element_id =  element_id || 'mw-admin-content-iframe-editor';
      var area = mwd.getElementById(element_id);
      var parent_page =  mw.$('#mw-parent-page-value-<?php print $rand; ?>').val();
      var content_id =  mw.$('#mw-content-id-value-<?php print $rand; ?>').val();
      var content_type =  mw.$('#mw-content-type-value-<?php print $rand; ?>').val()
      var subtype =  mw.$('#mw-content-subtype-<?php print $rand; ?>').val();
      var subtype_value =  mw.$('#mw-content-subtype-value-<?php print $rand; ?>').val();
      var active_site_template =  $('#mw-active-template-value-<?php print $rand; ?>').val();
      var active_site_layout = $('#mw-layout-file-value-<?php print $rand; ?>').val();
      var name = 'content/edit_default_inner';
      var selector = '#mw-admin-edit-content-main-area';
      var callback = false;
      var attributes = {}
      attributes.parent_page = parent_page;
      attributes.content_id = content_id;
      attributes.content_type = content_type;
      attributes.subtype = subtype;
      attributes.subtype_value = subtype_value;
      attributes.active_site_template = active_site_template;
      attributes.active_site_layout = active_site_layout;
      mw.load_module(name, selector, callback, attributes);
}
mw.edit_content.before_save = function(){
	mw.askusertostay=false;
	if(window.parent != undefined && window.parent.mw != undefined){
		window.parent.mw.askusertostay=false;
	}
}
mw.edit_content.after_save = function(saved_id){
	mw.askusertostay=false;
	var content_id =  mw.$('#mw-content-id-value-<?php print $rand; ?>').val();
	var quick_add_holder = mwd.getElementById('mw-quick-content');
 	if(quick_add_holder != null){
 	    mw.tools.removeClass(quick_add_holder, 'loading');
	}
	if(content_id == 0){
			if(saved_id !== undefined){
 		        mw.$('#mw-content-id-value-<?php print $rand; ?>').val(saved_id);
 			}
			<?php if($is_quick!=false) : ?>
			 mw.$('#quickform-<?php print $rand; ?>').hide();
			 mw.$('#post-added-alert-<?php print $rand; ?>').show();
			<?php endif; ?>
  	}

	if(parent !== self && !!parent.mw){
		    mw.reload_module_parent('posts');
			mw.reload_module_parent('shop/products');
			mw.reload_module_parent('shop/cart_add');
			mw.reload_module_parent('pages');
			mw.reload_module_parent('content');
			mw.reload_module_parent('custom_fields');
		    mw.tools.removeClass(mwd.getElementById('mw-quick-content'), 'loading');
			mw.reload_module('pages');
    	    parent.mw.askusertostay = false;
    	<?php if($is_current!=false) :  ?>
    	if(window.parent.mw.history != undefined){
 			setTimeout(function(){
 				window.parent.mw.history.load('latest_content_edit');
			},200);
    	}
    	<?php endif; ?>
	} else {
		mw.reload_module('[data-type="pages"]', function(){
			if( mw.$("#pages_tree_toolbar .mw_del_tree_content").length === 0 ){
				mw.$("#pages_tree_toolbar").removeClass("activated");
				mw.treeRenderer.appendUI('#pages_tree_toolbar');
				mw.tools.tree.recall(mwd.querySelector('.mw_pages_posts_tree'));
			}
			mw.tools.removeClass(mwd.getElementById('mw-quick-content'), 'loading');
		 });
	}
}

mw.edit_content.set_category = function(id){
      /* FILLING UP THE HIDDEN FIELDS as you change category or parent page */

	  var names = [];
      var inputs = mwd.getElementById(id).querySelectorAll('input[type="checkbox"]'), i = 0, l = inputs.length;
      for( ; i<l; i++){
        if(inputs[i].checked === true){
           names.push(inputs[i].value);
        }
      }
      if(names.length > 0){
        mw.$('#mw_cat_selected_for_post').val(names.join(',')).trigger("change");
      } else {
        mw.$('#mw_cat_selected_for_post').val('__EMPTY_CATEGORIES__').trigger("change");
      }
	  var names = [];
      var inputs = mwd.getElementById(id).querySelectorAll('input[type="radio"]'), i=0, l = inputs.length;
      for( ; i<l; i++){
        if(inputs[i].checked === true){
           names.push(inputs[i].value);
        }
      }
      if(names.length > 0){
        mw.$('#mw-parent-page-value-<?php print $rand; ?>').val(names[0]).trigger("change");
      } else {
        mw.$('#mw-parent-page-value-<?php print $rand; ?>').val(0).trigger("change");
      }
}

mw.edit_content.render_category_tree = function(id){
    if(mw.treeRenderer != undefined){
    	   mw.treeRenderer.appendUI('#mw-category-selector-'+id);
    	   mw.admin.tag({
    		  tagholder:'#mw-post-added-'+id,
    		  items: ".mw-ui-check",
    		  itemsWrapper: mwd.querySelector('#mw-category-selector-'+id),
    		  method:'parse',
    		  onTag:function(){
    			 mw.edit_content.set_category('mw-category-selector-'+id);
    		  },
    		  onUntag:function(a){
    			 mw.edit_content.set_category('mw-category-selector-'+id);
    		  },
              onFound:function(){
                mw.$("#category-tree-not-found-message").hide();
              },
              onNotFound:function(){
                mw.$("#category-tree-not-found-message").show();
              }
    	  });
          mw.$(".mw-ui-category-selector-abs .module:first").after('<div style="text-align:center;padding:30px;display:none;" id="category-tree-not-found-message"><h3>Category not found</h3><br><span class="mw-ui-btn"><em class="mw-icon-plus"></em>Create it</span></div>');
          $(mwd.querySelectorAll('#mw-category-selector-'+id+" .pages_tree_item")).bind("mouseup", function(e){
              if(!mw.tools.hasClass(e.target, 'mw_toggle_tree')){
                $(this).addClass("active");
              }
          });
    }
}

mw.edit_content.handle_form_submit = function(go_live){

        if(mw.edit_content.saving){ return false; }
        mw.edit_content.saving = true;
		var go_live_edit = go_live || false;
		var el = mwd.getElementById('quickform-<?php print $rand; ?>');
		if(el === null){
		    return;
		}
        $(window).trigger('adminSaveStart');
		mw.edit_content.before_save();
        var module =  $(mw.tools.firstParentWithClass(el, 'module'));
        var data = mw.serializeFields(el);
        module.addClass('loading');
        mw.content.save(data, {
          onSuccess:function(a){
              mw.$('.mw-admin-go-live-now-btn').attr('content-id',this);
              if(mw.notification != undefined){
                mw.notification.success('Content saved!');
              }

              if(parent !== self && !!window.parent.mw){
                 window.parent.mw.askusertostay=false;
				 if(typeof(data.is_active) !== 'undefined' && typeof(data.id) !== 'undefined'){
					   if((data.id) != 0){ 
						  if((data.is_active) == 'n'){
							 window.parent.mw.$('.mw-set-content-unpublish').hide();
							 window.parent.mw.$('.mw-set-content-publish').show();
						  }
						  else if((data.is_active) == 'y'){
							  window.parent.mw.$('.mw-set-content-publish').hide();
							  window.parent.mw.$('.mw-set-content-unpublish').show();
						  }
					   }
					  
				 }
              }

			  if(typeof(this) != "undefined"){
    			var inner_edits = mw.collect_inner_edit_fields();

    			if(inner_edits !== false){
    				var save_inner_edit_data = inner_edits;
    				save_inner_edit_data.id = this;

    				var xhr = mw.save_inner_editable_fields(save_inner_edit_data);
                    xhr.success(function(){
                      $(window).trigger('adminSaveEnd');
                    });
                    xhr.fail(function(){
                       $(window).trigger('adminSaveFailed');
                    });

    			}
		}
              if(go_live_edit != false){
    		    if(parent !== self && !!window.parent.mw){
    				 if(window.parent.mw.drag != undefined && window.parent.mw.drag.save != undefined){
    					 window.parent.mw.drag.save();
    				 }
                     window.parent.mw.askusertostay=false;
                }
                $.get('<?php print site_url('api_html/content_link/?id=') ?>'+this, function(data) {
                  window.top.location.href = data+'/editmode:y';
                });
              }
              else {
				  $.get('<?php print site_url('api_html/content_link/?id=') ?>'+this, function(data) {
					  if(data == null){
						return false;
					  }
					  var slug = data.replace("<?php print site_url() ?>", "").replace("/", "");
					  mw.$("#edit-content-url").val(slug);
					  mw.$(".view-post-slug").html(slug);
                   	  mw.$("a.quick-post-done-link").attr("href",data+'/editmode:y');
			 		  mw.$("a.quick-post-done-link").html(data);
                 });
                  mw.$("#<?php print $module_id ?>").attr("content-id",this);
                  <?php if($is_quick !=false) : ?>
                //  mw.$("#<?php print $module_id ?>").attr("just-saved",this);
                  <?php else: ?>
                  if(self === parent){
                    //var type =  el['subtype'];
                    mw.url.windowHashParam("action", "editpage:" + this);
                  }
                  <?php endif; ?>
                  mw.edit_content.after_save(this);
              }
              mw.edit_content.saving = false;
          },
          onError:function(){
            $(window).trigger('adminSaveFailed');
              module.removeClass('loading');
              if(typeof this.title !== 'undefined'){
                mw.notification.error('Please enter title');
				$('.mw-title-field').animate({
				    paddingLeft: "+=5px",
 				    backgroundColor: "#efecec"
				})
                .animate({
    				paddingLeft: "-=5px",
     				backgroundColor: "white"
				});
              }
              if(typeof this.content !== 'undefined'){
                mw.notification.error('Please enter content');
              }
              if(typeof this.error !== 'undefined'){
                mw.session.checkPause = false;
                mw.session.checkPauseExplicitly = false;
                mw.session.logRequest();
              }
              mw.edit_content.saving = false;
          }
        });
}

mw.collect_inner_edit_fields = function(data) {
     var frame =  mwd.querySelector('#mw-admin-content-iframe-editor iframe');
     if( frame === null ) return false;
     var frameWindow = frame.contentWindow;
     var root = frameWindow.mwd.getElementById('mw-iframe-editor-area');
     var data = frameWindow.mw.drag.getData(root);
     return data;
}

mw.save_inner_editable_fields = function(data){
	var xhr = $.ajax({
    	type: 'POST',
    	url: mw.settings.site_url + 'api/save_edit',
    	data: data,
    	datatype: "json"
	});
    return xhr;
}


/* END OF FUNCTIONS */

</script> 
<script>
    $(mwd).ready(function(){
        mw.edit_content.load_editor();
       <?php if($just_saved!=false) : ?>
       mw.$("#<?php print $module_id ?>").removeAttr("just-saved");
       <?php endif; ?>
        mw.edit_content.render_category_tree("<?php print $rand; ?>");
        mw.$("#quickform-<?php print $rand; ?>").submit(function(){
          mw.edit_content.handle_form_submit();
          return false;
        });
		<?php if($data['id']!=0) : ?>
		    mw.$(".mw-admin-go-live-now-btn").attr('content-id',<?php print $data['id']; ?>);
		<?php endif; ?>
       mw.$('#mw-parent-page-value-<?php print $rand; ?>').bind('change', function(e){
    		 var iframe_ed = $('.mw-iframe-editor');
    	     var changed =  iframe_ed.contents().find('.changed').size();
    		 if(changed == 0){
    		    mw.edit_content.load_editor();
    		 }
       });
	    $(window).bind('templateChanged', function(e){
		 var iframe_ed = $('.mw-iframe-editor')
	     var changed =  iframe_ed.contents().find('.changed').size();
		 if(changed == 0){
		    mw.edit_content.load_editor();
		 }
       });
	    if(mwd.querySelector('.mw-iframe-editor') !== null){
          mwd.querySelector('.mw-iframe-editor').onload = function(){
              $(window).bind('scroll', function(){
             var scrolltop = $(window).scrollTop();
             if(mwd.getElementById('mw-edit-page-editor-holder') !== null){
                 var otop = mwd.getElementById('mw-edit-page-editor-holder').offsetTop;
                 if( (scrolltop + 100) > otop){
                      var ewr = mwd.querySelector('.mw-iframe-editor').contentWindow.document.querySelector('.editor_wrapper');
                      if(ewr === null){return false;}
                      ewr.style.position = 'absolute';
                      ewr.style.top = scrolltop + otop + 'px';
                      ewr.style.top = scrolltop - otop /*+ mwd.querySelector('.admin-manage-toolbar').offsetTop*/ + mwd.querySelector('.admin-manage-toolbar').offsetHeight - 98  + 'px';
                      mw.$('.admin-manage-toolbar-scrolled').addClass('admin-manage-toolbar-scrolled-wysiwyg');
                      mw.tools.addClass(ewr, 'editor_wrapper_fixed');
                 }
                 else{
                      var ewr = mwd.querySelector('.mw-iframe-editor').contentWindow.document.querySelector('.editor_wrapper');
                      if(ewr === null){return false;}
                      ewr.style.position = 'static';
                      mw.$('.admin-manage-toolbar-scrolled').removeClass('admin-manage-toolbar-scrolled-wysiwyg');
                      mw.tools.removeClass(ewr, 'editor_wrapper_fixed');
                 }
              }
           });
          }
        }

	   var title_field_shanger = $('#content-title-field');

	   if(title_field_shanger.length > 0){
		    $( title_field_shanger ).unbind( "change");
			$( title_field_shanger ).bind( "change", function() {
				var newtitle =  $( this ).val();
			   $('#content-title-field-master').val(newtitle);
			});
	   }

       mww.QTABS = mw.tools.tabGroup({
          nav: mw.$("#quick-add-post-options .mw-ui-btn"),
          tabs: mw.$("#quick-add-post-options-items-holder .quick-add-post-options-item"),
          toggle:true,
          onclick:function(){
             var tabs = $(mwd.getElementById('quick-add-post-options-items-holder'));
            if(mw.$("#quick-add-post-options .mw-ui-btn.active").length > 0){
              var tabsnav = $(mwd.getElementById('quick-add-post-options'));
              var off = tabsnav.offset();
              $(tabs).show();
              QTABSArrow(this);
              QTABMaxHeight();
            }
            else{
               $(tabs).hide();
            }
          }
       });

       QTABMaxHeight = function(){
            var qt = mw.$('#quick-add-post-options-items-holder-container'),
                wh = $(window).height(),
                st = $(window).scrollTop();
            qt.css('maxHeight', (wh - (qt.offset().top - st + 20)));
       }

       $(mww).bind('mousedown', function(e){
		   var el = mwd.getElementById('content-edit-settings-tabs-holder');
          if(el != null && !el.contains(e.target)){
             mww.QTABS.unset()
             mw.$(".quick-add-post-options-item, #quick-add-post-options-items-holder").hide();
             mw.$("#quick-add-post-options .active").removeClass('active');
          }
       });

      mw.$(".mw-iframe-editor").bind("editorKeyup", function(){
        mw.tools.addClass(mwd.body, 'editorediting');
      });
      $(mwd.body).bind("mousedown", function(){
         mw.tools.removeClass(mwd.body, 'editorediting');
      });
      mw.$(".admin-manage-toolbar").bind("mousemove", function(){
         mw.tools.removeClass(mwd.body, 'editorediting');
      });

      $(window).bind("resize scroll", function(){

      QTABMaxHeight();
         /*

           $(window).scrollTop(0);
           mw.tools.toggleFullscreen(mwd.getElementById('pages_edit_container'));

         */
      });












    });
</script>