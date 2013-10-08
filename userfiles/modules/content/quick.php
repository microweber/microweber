<?php


$rand = uniqid(); 
$data = false;
//$data = $params;
$is_new_content = false;
if(!isset($is_quick)){
$is_quick=false;	
}
if(isset($params['page-id'])){
  $data = mw('content')->get_by_id(intval($params["page-id"]));
} 
if(isset($params['content-id'])){
  $data = mw('content')->get_by_id(intval($params["content-id"]));
}




$title_placeholder = false;
/* FILLING UP EMPTY CONTENT WITH DATA */
if($data == false or empty($data )){
   $is_new_content = true;
   include('_empty_content_data.php');
}
/* END OF FILLING UP EMPTY CONTENT  */



d($data['subtype']);

 

?>
 
<form method="post" action="<?php print site_url(); ?>api/save_content" id="quickform-<?php print $rand; ?>">
	<input type="hidden" name="id"  value="<?php print $data['id']; ?>" />
	<input type="hidden" name="is_active"  value="y" />
	<input type="hidden" name="subtype"  value="<?php print $data['subtype']; ?>" />
	<input type="hidden" name="content_type"  value="<?php print $data['content_type']; ?>" />
	<div class="mw-ui-field-holder">
		<input
      type="text"
      name="title"
      class="mw-ui-field mw-title-field mw-ui-field-full"
      style="border-left: 1px solid #E6E6E6"
      placeholder="<?php print $title_placeholder; ?>"
	  value="<?php print $data['title']; ?>" 
      autofocus required />
	</div>
	<div class="mw-ui-field-holder">
		<div>
			<div class="mw-ui-field mw-tag-selector mw-ui-field-dropdown mw-ui-field-full" id="mw-post-added-<?php print $rand; ?>">
				<input type="text" class="mw-ui-invisible-field" placeholder="<?php _e("Click here to add to categories and pages"); ?>." style="width: 280px;" id="quick-tag-field" />
			</div>
			<div class="mw-ui-category-selector mw-ui-category-selector-abs mw-tree mw-tree-selector" id="mw-category-selector-<?php print $rand; ?>" >
				<module
                    type="categories/selector"
                    for="content" />
			</div>
		</div>
	</div>
	<?php if($data['content_type'] == 'post' or $data['subtype'] == 'post' or $data['subtype'] == 'product'){ ?>
	<div class="mw-ui-field-holder">
		<textarea class="semi_hidden" name="content" id="quick_content"></textarea>
	</div>
	<?php } ?>
	<?php if($data['content_type'] == 'page'){ ?>
	<module type="content/layout_selector" id="mw-quick-add-choose-layout" autoload="yes" content-id="<?php print $data['id']; ?>" />
	<?php } ?>
	<?php if($data['subtype'] == 'product'){ ?>
	<div class="mw-ui-field-holder">
		<module
          type="custom_fields/admin"
          for="content"
          default-fields="price"
          content-id="0"
          content-subtype="<?php print $data['subtype'] ?>" />
	</div>
	<?php } ?>
	<div class="mw-ui-field-holder">
		<?php if($data['subtype'] != 'category'){ ?>
		<span class="mw-ui-link relative" id="quick-add-gallery" onclick="mw.$('#quick_init_gallery').show();$(this).hide();">Create Gallery</span>
		<div id="quick_init_gallery" class="mw-o-box" style="display: none;margin-bottom:20px;">
			<div  class="mw-o-box-content">
				<module type="pictures/admin" content_id="0" rel="content" rel_id="0" />
			</div>
		</div>
		<?php } ?>
		<button type="submit" class="mw-ui-btn mw-ui-btn-green right">Publish</button>
	</div>
</form>
<div class="quick_done_alert" style="display: none">
	<h2><span style="text-transform: capitalize"><?php print $data['subtype'] ?></span> has been created.</h2>
	<a href="javascript:;" class="mw-ui-link">Go to <?php print $data['subtype'] ?></a> <span class="mw-ui-btn" onclick="$(mw.tools.firstParentWithClass(this, 'mw-inline-modal')).remove();">Create New</span> </div>
<script>
    mw.require("content.js");
    mw.require("files.js");
</script> 
<script>

load_iframe_editor = function(element_id){

	 var element_id =  element_id || 'quick_content';

	 var area = mwd.getElementById(element_id);



	 if(area !== null){
		 var  ifr_ed_url = '<?php print mw('content')->link($data['id']) ?>?content_id=<?php print $data['id'] ?>';
		 var  ifr_ed_url_more = '&isolate_content_field=1&edit_post_mode=true&content_type=<?php print  $data['content_type'] ?>&subtype=<?php print  $data['subtype'] ?>';




		//var editor =  mw.wysiwyg.iframe_editor(area, ifr_ed_url+ifr_ed_url_more);
		var params = {};
		params.content_id='<?php print $data['id'] ?>'
		params.content_type='<?php print $data['content_type'] ?>'
		params.subtype='<?php print $data['subtype'] ?>'
		var editor =  mw.tools.wysiwyg(area,params ,true);
 


        editor.style.width = "100%";
        editor.style.height = "470px";
	 }

}

</script>
<script>



    add_cats = function(){
      var names = [];
      var inputs = mwd.getElementById('mw-category-selector-<?php print $rand; ?>').querySelectorAll('input[type="checkbox"]'), i=0, l = inputs.length;
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
    }



    $(document).ready(function(){
		 load_iframe_editor();
       /*var area = mwd.getElementById('quick_content');
       if(area !== null){
           editor = mw.tools.wysiwyg(area);
           editor.style.width = "100%";
           editor.style.height = "270px";
       }
       else{
         editor = undefined;
       } */

       mw.treeRenderer.appendUI('#mw-category-selector-<?php print $rand; ?>');
       mw.tools.tag({
          tagholder:'#mw-post-added-<?php print $rand; ?>',
          items: ".mw-ui-check",
          itemsWrapper: mwd.querySelector('#mw-category-selector-<?php print $rand; ?>'),
          method:'parse',
          onTag:function(){
             add_cats()
          },
          onUntag:function(){
             add_cats()
          }
      });
      mw.$("#quickform-<?php print $rand; ?>").submit(function(){
        var el = this;
        var module =  $(mw.tools.firstParentWithClass(el, 'module'));
        var data = mw.serializeFields(el);
        module.addClass('loading');
        mw.content.save(data, {
          onSuccess:function(){
              el.reset();
             // $(editor).contents().find("#mw-iframe-editor-area").empty();
              mw.$(".quick_done_alert a").attr("href", mw.settings.site_url + "?content_id=" + this);
              mw.reload_module("pictures/admin", function(){
                  module.removeClass('loading');
                 
              });
			   mw.tools.inlineModal({
                    element: mw.$(".quick-add-module"),
                    content: $(".quick_done_alert")
                  });
          },
          onError:function(){
              module.removeClass('loading');
              if(typeof this.title !== 'undefined'){
                mw.notification.error('Please enter title');
              }
              if(typeof this.content !== 'undefined'){
                mw.notification.error('Please enter content');
              }
              if(typeof this.error !== 'undefined'){
                mw.session.checkPause = false;
                mw.session.checkPauseExplicitly = false;
                mw.session.logRequest();
              }
          }
        })
        return false;
      });
    });
</script>















