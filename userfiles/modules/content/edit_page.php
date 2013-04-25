<?
if(!isset($edit_post_mode)){
	$edit_post_mode = false;
}   //  $params['content_type'] = 'post';


$rand = uniqid();


if(isset($params["data-content-id"]) and intval($params['data-content-id']) > 0){
	$params["data-page-id"] = $params["data-content-id"];
}

if(isset($params["content-id"])){
	$params["data-page-id"] = $params["content-id"];
}
if(isset($params["data-content"])and intval($params['content-id']) > 0){
	//$params["data-page-id"] = $params["data-content"];
}

if(!isset($params["data-page-id"])){
	//$params["data-page-id"] = PAGE_ID;
}

$pid = false;
$data = false;
if(isset($params["data-page-id"]) and intval($params["data-page-id"]) != 0){

  $data = get_content_by_id(intval($params["data-page-id"]));
  if(isarr($data)){
	  if(isset($data['subtype']) and trim($data['subtype']) == 'product'){
		  $params["is_shop"] = 'y';
	  }
  }
//d($data);
}


$active_site_template = '';
$layout_file = '';
$layout_from_parent = '';
if(!isset($edit_post_mode) and $data == false or empty($data ) ){
$layout_from_parent = ' layout_file="layouts/clean.php" ';
}
if($data == false or empty($data )){
  include('_empty_content_data.php');
  if(isset($edit_post_mode) and $edit_post_mode == true and !isset($params["parent-page-id"]) or (isset($params["parent-page-id"]) and intval($params["parent-page-id"]) == 0)){
   if(isset($params["is_shop"]) and $params["is_shop"] == 'y'){
     $parent_cont = get_content('subtype=dynamic&is_active=y&content_type=page&limit=1&order_by=updated_on desc&is_shop=y');
   }

   if(!isset($params["is_shop"]) or $params["is_shop"] == 'n'){
     $parent_cont = get_content('subtype=dynamic&is_active=y&content_type=page&limit=1&order_by=updated_on desc&is_shop=n');
   }
   if(isarr($parent_cont) and isset($parent_cont[0])){
    $params["parent-page-id"] = $parent_cont[0]['id'];
    $params["parent-category-id"] = $parent_cont[0]['subtype_value'];
  }


} else {

  if(intval($data['id']) == 0){
	 if(isset($params["parent-page-id"]) and intval($params["parent-page-id"]) != 0){
	   $data['parent'] = $params["parent-page-id"];

	 }
}



 if(isset($params["parent-page-id"]) and intval($params["parent-page-id"]) != 0){
  $parent_cont = get_content_by_id($params["parent-page-id"]);
      if(isarr($parent_cont) and isset($parent_cont['active_site_template'])){



//    $layout_from_parent = " inherit_from='{$params["parent-page-id"]}' ";








      }
   }
}
}
if(isset($edit_post_mode) and $edit_post_mode == true){
 $data['content_type'] = 'post';
}

if(isset($data['content_type']) and  $data['content_type'] == 'post'){
	$edit_post_mode = true;
}


if(isset($params["data-is-shop"])){
	$data["is_shop"] = $params["data-is-shop"];


}

if(isset($params['is_shop']) and  $params['is_shop'] == 'y'){

  $is_shop_exist = get_content('is_shop=y&count=1');
  if($is_shop_exist == 0){
   // $is_shop_exist = get_content('is_shop=y&count=1');
    mw_create_default_content('shop');
    $is_shop_exist = get_content('is_shop=y&limit=1');
    if(isarr($is_shop_exist)){
      $params['parent-page-id'] = $data['parent_id'] = $is_shop_exist[0]['id'];
    }
  } else {

    if(isset($data['parent']) and  intval($data['parent']) == 0){
      $is_shop_exist = get_content('is_shop=y&limit=1');
      if(isarr($is_shop_exist)){
        $params['parent-page-id'] = $data['parent_id'] = $data['parent'] = $is_shop_exist[0]['id'];
      }
    }


  }
} else {

  if(isset($edit_post_mode) and $edit_post_mode == true){
    $is_blog_exist = false;
    if(isset($params['parent-page-id'])){
      $is_blog_exist = get_content("id=".$params['parent-page-id']);
      if(isarr($is_blog_exist)){
        $data['parent'] = $is_blog_exist[0]['id'];

      }

    }

    if(isset($data['parent']) and  intval($data['parent']) == 0 or !isset($data['parent'])){






     if(isarr($is_blog_exist)){


     } else {



       $is_blog_exist = get_content('content_type=page&subtype=dynamic&is_shop=n&limit=1');

       if(isarr($is_blog_exist)){
        $data['parent'] = $is_blog_exist[0]['id'];
      } else {


       mw_create_default_content('blog');
       $is_blog_exist = get_content('content_type=page&subtype=dynamic&is_shop=n&limit=1&no_cache=1');
     }

   }




   if(isarr($is_blog_exist)){
       // $params['parent-page-id'] = $data['parent_id'] = $data['parent'] = $is_blog_exist[0]['id'];
     $data['parent'] = $is_blog_exist[0]['id'];
   }
 }
}
}

//

//$form_rand_id = //$rand = md5(serialize($data).serialize($params));
?>
<script  type="text/javascript">
mw.require('forms.js',true);
mw.require('url.js',true);
</script>
<script type="text/javascript">


_MemoryToggleContentID = "<? print $data['id']; ?>";




$(document).ready(function(){



  mw.$('#admin_edit_page_form_<? print $rand; ?>').submit(function() {





   mw_before_content_save<? print $rand; ?>();
   mw.form.post(mw.$('#admin_edit_page_form_<? print $rand; ?>') , '<? print site_url('api/save_content') ?>', function(){
     <? if(intval($data['id']) == 0): ?>


     mw.url.windowHashParam("action", "edit<? print $data['content_type'] ?>:" + this);

     mw.url.windowHashParam("new_content", 'true');
     mw.reload_module('[data-type="pages"]', function(){

        if( mw.$("#pages_tree_toolbar .ico").length === 0 ){
            mw.$("#pages_tree_toolbar").removeClass("activated");
            mw.treeRenderer.appendUI('#pages_tree_toolbar');
            mw.tools.tree.recall(mwd.querySelector('.mw_pages_posts_tree'));
        }

     });
     <? else: ?>

     mw.reload_module('[data-type="pages"]', function(){
        if( mw.$("#pages_tree_toolbar .ico").length === 0 ){
          mw.$("#pages_tree_toolbar").removeClass("activated");
          mw.treeRenderer.appendUI('#pages_tree_toolbar');
          mw.tools.tree.recall(mwd.querySelector('.mw_pages_posts_tree'));
        }
     });
 // mw_after_content_save<? print $rand; ?>();
 <? endif; ?>


 if(window.parent != undefined && window.parent.mw != undefined){
  window.parent.mw.reload_module('posts');
}

mw_on_save_complete<? print $rand; ?>()


});


//  var $pmod = $(this).parent('[data-type="<? print $config['the_module'] ?>"]');

		  // mw.reload_module($pmod);

     return false;


   });


  mw.$('#admin_edit_page_form_<? print $rand; ?>  .go-live').click(function() {


    mw_before_content_save<? print $rand; ?>()


    mw.form.post(mw.$('#admin_edit_page_form_<? print $rand; ?>') , '<? print site_url('api/save_content') ?>', function(){

     mw_after_content_save<? print $rand; ?>(this);




   });




    return false;


  });




  var el_par_page =$('#parent_page_select_<? print $rand; ?>');
  if(el_par_page.length >0){
   __set_content_parent_info<? print $rand; ?>()
   el_par_page.bind('change', function() {

     __set_content_parent_info<? print $rand; ?>()

   })

 }



 mw.$("#mw-scaleeditor, #mw-main-postpage-editor .mw-close").click(function(){

  var span = $('#mw-scaleeditor span');
  if(!span.hasClass('no-fullscreen')){
    mw.$("#mw-main-postpage-editor").removeAttr('style')
    mw.$("#mw-main-postpage-editor").draggable("enable");
  }
  else{mw.$("#mw-main-postpage-editor").draggable("disable");}

  //mw.tools.scaleTo('#mw-main-postpage-editor', 950, 600);
  mw.tools.scaleTo('#mw-main-postpage-editor', "80%", "80%");

  span.toggleClass('no-fullscreen');


});


 $(mwd.body).mousedown(function(e){
  if(!mw.tools.hasParentsWithClass(e.target, 'mw-scaleto')
    && !$(e.target).hasClass('mw-scaleto')
    && !mw.tools.hasParentsWithClass(e.target, 'zoom')
    && !$(e.target).hasClass('zoom')){
   mw.tools.scaleTo('#mw-main-postpage-editor', 'close');
 $('#mw-scaleeditor span').removeClass('no-fullscreen');
 if(mw.$('.preview_frame_wrapper iframe').length>0){
  mw.templatePreview.zoom('out');
}

}
});

 mw.$("#mw-main-postpage-editor").draggable({
  handle:'#mw-main-postpage-editor-drag-handle',
  containment:"window",
  start:function(){
    $(this).find(".iframe_fix").show();
  },
  stop:function(){
   $(this).find(".iframe_fix").hide();
 }
});

 mw.$("#mw-main-postpage-editor").draggable("disable")



 mw.tools.memoryToggleRecall();




});


mw.on.hashParam("new_content", function(){

     //alert(' mw_on_save_complete<? print $rand; ?>')
     mw_on_save_complete<? print $rand; ?>();



//mw.url.windowDeleteHashParam("new_content")
});


function __set_content_parent_info<? print $rand; ?>(){

  mw.$('#admin_edit_page_form_content_parent_info<? print $rand; ?>').empty();
  var el =$('#parent_page_select_<? print $rand; ?> option:selected');
  if(el.length >0){
    var val = el.val();
    var title = el.attr('title');
    if(title != undefined){
      mw.$('#admin_edit_page_form_content_parent_info<? print $rand; ?>')

      .html('<a class="page_parent" href="javascript:;" onclick="edit_page_open_page_and_menus<? print $rand; ?>();"><span class="ico ipage_arr_up"></span><span>Parent</span><span class="ico ipage2"></span><span>'+ title+'</span></a>');

      mw.$("#page_title_and_url .mw-title-field-label-page").removeClass("mw-title-field-label-subpage");
    }

    if(val != undefined && val != 0){
      $('#mw-layout-selector-module').attr('inherit_from',val);

      $('#mw-layout-selector-module').removeAttr('active-site-template');
      $('#mw-layout-selector-module').removeAttr('data-active-site-template');
      mw.reload_module('#mw-layout-selector-module');

      mw.$("#page_title_and_url .mw-title-field-label-page")
      .addClass("mw-title-field-label-subpage")
      .removeClass("mw-title-field-label-page");

    }

  }
}

function edit_page_open_page_and_menus<? print $rand; ?>(){

//mw.tools.memoryToggle(this);


mw.$("#advanced-settings-toggle").addClass("toggler-active");


mw.$('.ed_page_and_menus_opener_link').addClass('active');
mw.$('.page_and_menus_holder').show();

var sel_hl = mw.$('.mw_parent_page_sel_holder')[0];

mw.tools.scrollTo(sel_hl)
mw.tools.highlight(sel_hl, '#d8ffc3', 300,5000)



}

function mw_on_save_complete<? print $rand; ?>(){
	//alert(1);
  mw.notification.success("<?php _e('All changes are saved'); ?>.");

  mw.askusertostay = false;


}



function mw_before_content_save<? print $rand; ?>(){

  var ed_area = $('#iframe_editor_mw-editor<? print $rand; ?>');
  var ed_area_src = mw.$('#mw-editor<? print $rand; ?>_src');

  if(ed_area != undefined && ed_area.length > 0){

   var ed_area_ch =  ed_area.contents().find('.changed[field="content"]').first();

   if(ed_area_ch != undefined && ed_area_src != undefined && ed_area_src.length > 0){
    var changed_html = ed_area_ch.html();
    if(changed_html == undefined){
      var ed_area_ch1 =  ed_area.contents().find('#mw-iframe-editor-area').first().html();
      if(ed_area_ch1 != undefined){
	// changed_html = ed_area_ch1;
}
}
			 //
       ed_area_src.val(changed_html);
     }
   }



   mw.$('#admin_edit_page_form_<? print $rand; ?> .module[data-type="custom_fields"]').empty();
 }




 function mw_after_content_save<? print $rand; ?>($id){

  mw.reload_module('[data-type="pages"]',  function(){




  });
  <? if($edit_post_mode != false): ?>
  mw.reload_module('[data-type="posts"]',  function(){





  });
  <? endif; ?>

  $id = $id.toString();


  mw.cookie.set('back_to_admin','<? print admin_url("view:content#action=editpage:") ?>'+$id);

  mw.reload_module('#admin_edit_page_form_<? print $rand; ?> .module[data-type="custom_fields"]');
  if( typeof $id =='string'){
   $id = $id.replace(/"/g, "");
   $.get('<? print site_url('api_html/content_link/') ?>'+$id, function(data) {
     window.top.location.href = data+'/editmode:y';

   });


 }



 mw_on_save_complete<? print $rand; ?>()



}




<? if(isset($data['subtype']) and trim($data['subtype']) == 'a_product'): ?>



<? endif; ?>






</script>

<form autocomplete="off" name="mw_edit_page_form" id="admin_edit_page_form_<? print $rand; ?>" class="mw_admin_edit_content_form mw-ui-form add-edit-page-post content-type-<? print $data['content_type'] ?>">
  <input name="id" type="hidden" value="<? print ($data['id'])?>" />
  <div id="page_title_and_url">
    <div class="mw-ui-field-holder">
      <? if(intval($data['id']) > 0): ?>
      <? $act = _e("Edit ", true); ;?>
      <? else : ?>
      <? $act = _e('Add new ', true); ;?>
      <? endif; ?>
      <?php /*         */
  $t =  "Page";
  if(intval($data['id']) == 0 and isset($params['subtype']) and trim($params['subtype']) != '') {
    $data['subtype'] = $params['subtype'];
    $t =      $data['subtype'];
  } elseif(isset($data['content_type']) and $data['content_type'] == 'post'  and isset($data['subtype']) and $data['subtype'] != ''){

    $t =      $data['subtype'];
  } elseif($data['content_type'] == 'page' and $data['parent'] >0){
    $t = "Sub-page";
     //   $data['subtype'] = 'post';
     // $data['title'] =     "Sub ". $data['content_type'];
  } else {

  }


  ?>
      <? if(intval($data['id']) > 0): ?>
      <span class="mw-title-field-label mw-title-field-label-<?php print strtolower(( $t)); ?>"></span>
      <input name="title" onkeyup="mw.askusertostay = true;" onpaste="mw.askusertostay = true;" class="mw-ui-field mw-title-field"  type="text" value="<? print ($data['title'])?>" />
      <? else : ?>
      <?


?>
      <span class="mw-title-field-label mw-title-field-label-<?php print strtolower(ucfirst( $t)); ?>"></span>
      <input name="title" class="mw-ui-field mw-title-field"   type="text" value="<?php print ucfirst($t); ?> <? if($data['content_type'] == 'post' and $data['subtype'] == 'post'):?><?php _e("Title"); ?><? else : ?><?php _e("Name"); ?><? endif ?>" />
      <? endif; ?>
    </div>
    <div class="edit-post-url"><span class="view-post-site-url"><?php print site_url(); ?></span><span class="view-post-slug active" onclick="mw.slug.toggleEdit()"><? print ($data['url'])?></span>
      <input name="content_url" class="edit-post-slug" onkeyup="mw.slug.fieldAutoWidthGrow(this);" onblur="mw.slug.toggleEdit();mw.slug.setVal(this);" type="text" value="<? print ($data['url'])?>" />
      <span class="edit-url-ico" onclick="mw.slug.toggleEdit()"></span> </div>
    <div class="edit_page_content_parent" id="admin_edit_page_form_content_parent_info<? print $rand; ?>"></div>
    <div class="mw_clear"></div>
  </div>
  <?
if(!isset($data['content'])){
  $data['content'] = '';
}?>
  <script>

load_iframe_editor = function(content){
 var area = mwd.getElementById('mw-editor<? print $rand; ?>');


 var  ifr_ed_url = '<? print content_link($data['id']) ?>?content_id=<? print $data['id'] ?>';
 var  ifr_ed_url_more = '';
 <? if($edit_post_mode != false): ?>

 var selpage = $('#categorories_selector_for_post_<? print $rand; ?>');



 if(selpage.length > 0){
  selpage_find = 	selpage.find('input[type="radio"]:checked').first().val();
		//mw.log('pecata   '+selpage_find);
		if(selpage_find != undefined){
			ifr_ed_url_more = '&parent_id='+selpage_find;
		}
	//

}




<? else:  ?>


if(!!mw.templatePreview){
  var ifr_ed_url =  mw.templatePreview.generate(true);

}


<? endif; ?>


if(!!content){
 mw.wysiwyg.iframe_editor(area, ifr_ed_url+'&isolate_content_field=1&edit_post_mode=<? print  $edit_post_mode ?>&content_type=<? print  $data['content_type'] ?>'+ifr_ed_url_more, content);

}
else{
  mw.wysiwyg.iframe_editor(area, ifr_ed_url+'&isolate_content_field=1&edit_post_mode=<? print  $edit_post_mode ?>&content_type=<? print  $data['content_type'] ?>'+ifr_ed_url_more);

}







}







</script>
  <div class="mw-scaleto-holder">
    <div id="mw-main-postpage-editor">
      <div id="mw-main-postpage-editor-drag-handle"><span class="mw-close"></span></div>
      <div id="mw-editor<? print $rand; ?>" style="height: 310px;width:623px;"></div>
      <textarea name="content" autocomplete="off"  style="display:none" id="mw-editor<? print $rand; ?>_src"></textarea>
      <div class="mw-postaction-bar">
        <div class="left"> <a href="javascript:;" id="mw-scaleeditor" class="mw-ui-btn mw-btn-single-ico"><span class="ico ifullscreen"></span></a> </div>
        <div class="right">
          <?php /*     <span class="mw-ui-btn">Preview</span>
          <span class="mw-ui-btn mw-ui-btn-green">Publish Page</span> */ ?>
          <span class="mw-ui-btn go-live">Go Live Edit</span> <span class="mw-ui-btn mw-ui-btn-green" style="min-width: 66px;" onclick="$(this).parents('form').submit();mw.$('#mw_edit_page_left .mw-tree.activated').removeClass('activated');">Save</span> </div>
      </div>
      <div class="iframe_fix"></div>
    </div>
  </div>
  <? /* PAGES ONLY  */ ?>
  <? if($edit_post_mode == false): ?>
  <script>

    load_preview = function(){

      if(!!mw.templatePreview){
        if(!mw.templatePreview._once){
          mw.templatePreview._once = true;
          mw.templatePreview.generate();
        }
      }

    }

     <? if(intval($data['id']) == 0): ?>
     $(document).ready(function(){
//$("#layout-selector-toggle").show();
//$(".mw-layout-selector-holder").show();

mw.tools.toggle(".mw-layout-selector-holder", "#layout-selector-toggle");

//mw.tools.toggle($("#layout-selector-toggle")[0]);
load_preview();
    })
<? endif; ?>


    </script>
  <a class="toggle_advanced_settings mw-ui-more" data-for='.mw-layout-selector-holder' id="layout-selector-toggle" data-callback="load_preview" onclick="mw.tools.memoryToggle(this);load_preview();" href="javascript:;">Template</a>
  <div class="mw-layout-selector-holder" style="display: none;">
    <module id="mw-layout-selector-module" data-type="content/layout_selector" <? print
      $layout_from_parent ?> data-page-id="<? print ($data['id'])?>"  autoload=1 />
    <div class="mw-save-content-bar"> <span class="mw-ui-btn go-live">Go Live Edit</span> <span onclick="$(this).parents('form').submit();" style="min-width: 66px;" class="mw-ui-btn mw-ui-btn-green">Save</span> </div>
  </div>
  <div class="vSpace"></div>
  <? if($edit_post_mode == false): ?>
  <?   //  d($data);

  $pt_opts = array();
  if(intval($data['id']) > 0){
    $pt_opts['active_ids'] = $data['parent'];

  } else {

    if(isset($params['parent-page-id']) and intval($data['parent']) == 0 and intval($params['parent-page-id']) > 0){
      $pt_opts['active_ids'] = $data['parent']= $params['parent-page-id'];
    }


  }


  ?>
  <a href="javascript:;" data-for='.page_and_menus_holder' id="advanced-settings-toggle" onclick="mw.tools.memoryToggle(this);"  class="toggle_advanced_settings mw-ui-more ed_page_and_menus_opener_link">
  <?php _e('Page &amp; Menus'); ?>
  </a>
  <div class="page_and_menus_holder" style="display: none;">
    <div class="vSpace"></div>
    <div class="mw-ui-field-holder mw_parent_page_sel_holder" style="padding: 10px;margin: 0 -10px;">
      <label class="mw-ui-label">
        <?php _e("Parent page"); ?>
      </label>
      <div class="mw-ui-select" style="width: 100%;">
        <select name="parent" id="parent_page_select_<? print $rand; ?>">
          <option value="0"   <? if((0 == intval($data['parent']))): ?>   selected="selected"  <? endif; ?> title="None">None</option>
          <?

          $pt_opts['link'] = "{empty}{title}";
          $pt_opts['list_tag'] = " ";
          $pt_opts['list_item_tag'] = "option";
          $pt_opts['remove_ids'] = $data['id'];
          if(isset($params['is_shop'])){
      //$pt_opts['is_shop'] = $params['is_shop'];
          }
  			if(!isset($pt_opts['active_ids']) and isset($params['parent-page-id']) and  intval($params['parent-page-id']) > 0){
  			 $pt_opts['active_ids'] = $data['parent']= $params['parent-page-id'];
  			 }


          $pt_opts['active_code_tag'] = '   selected="selected"  ';



          pages_tree($pt_opts);


          ?>
        </select>
      </div>
    </div>
    <? exec_action('mw_edit_page_admin_menus', $data); ?>
  </div>
  <div class="vSpace"></div>
  <? endif; ?>
  <? endif; ?>
  <? /* PAGES ONLY  */ ?>
  <? /* ONLY FOR POSTS  */ ?>
  <? if($edit_post_mode != false): ?>
  <a href="javascript:;" data-for='#edit_post_select_category' id="category-post-toggle" onclick="mw.tools.memoryToggle(this);" class="mw-ui-more toggler-active">
  <?php _e("Add to Page &amp; Category"); ?>
  </a> 
  <small class="right mw-help" data-help="Please choose parent page and categories for this <? print $data['content_type'] ?>.">(?)</small>
  <div class="vSpace"></div>
  <div id="edit_post_select_category" style="display: block">
    <div class="mw-ui-field mw-tag-selector " id="mw-post-added-<? print $rand; ?>">
      <input type="text" class="mw-ui-invisible-field" value="Click here to add to categories and pages." data-default="Click here to add to categories and pages." />
    </div>
    <script>

  $(document).ready(function(){


    mw.tools.tag({
      tagholder:'#mw-post-added-<? print $rand; ?>',
      items: ".mw-ui-check",
      itemsWrapper: mwd.querySelector('#mw-category-selector-<? print $rand; ?>'),
      method:'parse',
      onTag:function(){

        mw_set_categories_from_tree()
mw_load_post_cutom_fields_from_categories<? print $rand; ?>()



        var curr_content = mwd.getElementById('mw-editor<? print $rand; ?>').value;
        if(curr_content != undefined){
         load_iframe_editor(curr_content);
       }
       else{
         load_iframe_editor();
       }




     },
     onUntag:function(){

    mw_set_categories_from_tree();

mw_load_post_cutom_fields_from_categories<? print $rand; ?>()
      var curr_content = mwd.getElementById('mw-editor<? print $rand; ?>').value;
      if(curr_content != undefined){
       load_iframe_editor(curr_content);
     }
     else{
       load_iframe_editor();
     }







   }
 });





  });



  function mw_set_categories_from_tree(){
    var names = [];


    /*
    mw.$('#mw-category-selector-<? print $rand; ?> .category_element .mw-ui-check-input-sel:checked').each(function() {
      names.push($(this).val());
    });       */

    var inputs = mwd.getElementById('mw-category-selector-<? print $rand; ?>').querySelectorAll('input[type="checkbox"]'), i=0, l = inputs.length;

    for( ; i<l; i++){
      if(inputs[i].checked === true){
         names.push(inputs[i].value)
      }
    }



    if(names.length > 0){
      mw.$('#mw_cat_selected_for_post').val(names.join(',')).trigger("change");
    } else {
      mw.$('#mw_cat_selected_for_post').val('__EMPTY_CATEGORIES__').trigger("change");
    }
}

  </script>
    <?

  $shopstr = '&is_shop=n';


  if(isset($params["subtype"]) and $params["subtype"] == 'product'){
    $shopstr = '&is_shop=y';
  }
  if(isset($params["data-subtype"]) and $params["data-subtype"] == 'product'){
    $shopstr = '&is_shop=y';
  }

  if(isset($data["subtype"]) and $data["subtype"] == 'product'){
    $shopstr = '&is_shop=y';
  }

  $strz = '';


  $selected_parent_ategory_id = '';
  if(isset($params["parent-category-id"])){
    $selected_parent_ategory_id = " data-parent-category-id={$params["parent-category-id"]} ";
  }




  if(isset($include_categories_in_cat_selector)): ?>
    <?
  $x = implode(',',$include_categories_in_cat_selector);
  $strz = ' add_ids="'.$x.'" ';   ?>
    <? endif; ?>
    <? $categories_active_ids = ''; ?>
    <div class="mw-ui mw-ui-category-selector mw-tree mw-tree-selector" id="mw-category-selector-<? print $rand; ?>">
      <div class="cat_selector_view_ctrl"><a href="javascript:;" class="active" onclick="mw.$('#categorories_selector_for_post_<? print $rand; ?> label.mw-ui-check').show();$(this).addClass('active').next().removeClass('active');">All</a> <a href="javascript:;" onclick="mw.tools.tree.viewChecked(mwd.getElementById('categorories_selector_for_post_<? print $rand; ?>'));$(this).addClass('active').prev().removeClass('active');">Selected</a> </div>
      <? if(intval($data['id']) > 0): ?>
      <? $in_cats = get('from=categories_items&fields=parent_id&rel=content&rel_id='.$data['id']);
  if(isarr($in_cats)){
   foreach($in_cats as $in_cat){
    $categories_active_ids = $categories_active_ids.','.$in_cat['parent_id'];
  }
}



?>
      <microweber module="categories/selector"  categories_active_ids="<? print $categories_active_ids; ?>" for="content" id="categorories_selector_for_post_<? print $rand; ?>" rel_id="<? print $data['id'] ?>"  active_ids="<? print intval($data['parent']) ?>" <? print $strz ?> <? print $shopstr ?> />
      <? else: ?>
      <? if(isset($params["parent-page-id"]) and intval($params["parent-page-id"]) > 0){

 $selected_parent_ategory_id = 'active_ids="'.$params["parent-page-id"].'"';
} ?>
      <? if(intval($data['parent']) == 0  and isset( $data['parent_id'] ) and intval( $data['parent_id'] ) > 0) {


  $data['parent'] =  $data['parent_id'] ;

} else if(intval($data['parent']) == 0  and isset( $params['parent-page-id'] ) and intval( $params['parent-page-id'] ) > 0){
  $data['parent'] =  $params['parent-page-id'] ;
}

if(isset($is_blog_exist) and isarr($is_blog_exist)){

}
if((!isset($categories_active_ids) or $categories_active_ids == '') and isset( $params["selected-category-id"])){
  $categories_active_ids =  $params["selected-category-id"];

   if(isset($params['parent-page-id']) and intval($data['parent']) == 0 and intval($params['parent-page-id']) > 0){



   }
}


?>
      <microweber module="categories/selector"   categories_active_ids="<? print $categories_active_ids; ?>"  id="categorories_selector_for_post_<? print $rand; ?>" rel_id="<? print $data['id'] ?>"  active_ids="<? print intval($data['parent']) ?>" for="content" <? print $strz ?> <? print $selected_parent_ategory_id ?> <? print $shopstr ?> />
      <? endif; ?>
    </div>
    <div class="vSpace"></div>
    <script type="text/javascript">
$(mwd).ready(function(){
  if(!!mw.treeRenderer && mw.$("#categorories_selector_for_post_<? print $rand; ?> .ico").length === 0){
   mw.treeRenderer.appendUI('#categorories_selector_for_post_<? print $rand; ?>');
  }
});
</script>
  </div>
  <? endif; ?>
  <? /* ONLY FOR POSTS  */ ?>
  <? if($edit_post_mode != false): ?>
  <?




if(!isset($params["subtype"])){
 if(intval($data['id']) != 0){
  if(isset($data["subtype"]) and trim($data["subtype"]) != ''){
   $params['subtype'] = $data["subtype"];
 } else {
   $params['subtype'] = 'post';
 }



} else {
  $params['subtype'] = 'post';



}

}

?>
  <? if(isset($params['subtype']) and $params['subtype'] == 'product'): ?>
  <? $pages = get_content('content_type=page&subtype=dynamic&is_shop=y&limit=1000');   ?>
  <? else: ?>
  <? $pages = get_content('content_type=page&subtype=dynamic&is_shop=n&limit=1000');   ?>
  <? endif; ?>
  <?
if(intval($data['id']) == 0){
 if(isset($params["parent-page-id"]) and intval($params["parent-page-id"]) != 0){
   $data['parent'] = $params["parent-page-id"];

 }
}
// d(  $data['parent']);
?>
  <? if(!isset($params['subtype'])): ?>
  <?   $params['subtype'] = 'post'; ?>
  <? endif; ?>
  <input name="subtype"  type="hidden"  value="<? print $data['subtype'] ?>" >
  <? endif; ?>
  <?



?>
  <? if($edit_post_mode != false): ?>
  <? $data['content_type'] = 'post'; ?>
  <a class="toggle_advanced_settings mw-ui-more" onclick="mw.tools.memoryToggle(this);" data-for='.pictures-editor-holder' id="pictures-editor-toggle" href="javascript:;">Pictures Gallery</a>
  <div class="pictures-editor-holder" style="display: none;">
    <module type="pictures/admin" for="content" for-id=<? print $data['id'] ?>  />
  </div>
  <? endif; ?>
  <? exec_action('mw_edit_content_admin', $data); ?>
  <? if($edit_post_mode != false): ?>
  <? exec_action('mw_edit_post_admin', $data); ?>
  <? else: ?>
  <? exec_action('mw_edit_page_admin', $data); ?>
  <? endif; ?>
  <input name="content_type"  type="hidden"  value="<? print $data['content_type'] ?>" >
  <? if($edit_post_mode != false): ?>
  <div class="mw_clear"></div>
  <div class="vSpace"></div>
  <? endif; ?>
  <? /* ONLY FOR POSTS  */ ?>
  <? // if($edit_post_mode != false): ?>
  <? if(isset($data['subtype']) and trim($data['subtype']) == 'product'): ?>
  <a href="javascript:;" class="mw-ui-more toggler-active" onclick="mw.tools.toggle('#custom_fields_for_post_<? print $rand; ?>', this);" id="custom-fields-toggler" data-for='#custom_fields_for_post_<? print $rand; ?>'>
  <?php _e("Custom Fields"); ?>
  </a>
  <? else: ?>
  <a href="javascript:;" class="mw-ui-more" onclick="mw.tools.memoryToggle(this);" id="custom-fields-toggler" data-for='#custom_fields_for_post_<? print $rand; ?>'>
  <?php _e("Custom Fields"); ?>
  </a>
  <? endif; ?>



  <div class="vSpace"></div>
  <?php /* <a href="javascript:;" class="mw-ui-btn" onclick="mw.tools.toggle('#the_custom_fields', this);"><span class="ico iSingleText"></span><?php _e("Custom Fields"); ?></a>  */ ?>
  <div id="custom_fields_for_post_<? print $rand; ?>"  style="<? if(isset($data['subtype']) and trim($data['subtype']) == 'product'): ?>display:block;<? else: ?>display:none;<? endif; ?>">
    <small class="right mw-help" data-help="You can set custom properties for this <? print $data['content_type'] ?>. ">(?)</small>
    <div class="vSpace"></div>
    <module type="custom_fields/admin"    for="content" rel_id="<? print $data['id'] ?>" id="fields_for_post_<? print $rand; ?>" content-subtype="<? print $data['subtype'] ?>" />





 <div class="vSpace"></div>
 <small>Custom fields you may like to use <span class="" data-help="Those custom fields are from the page or categories you chose for this <? print $data['subtype'] ?>.">(?)</span></small>
    <div class="custom_fields_from_parent"  id="custom_fields_from_pages_selector_for_post_1<? print $rand; ?>" ></div>
    <div class="custom_fields_from_parent_cat"  id="custom_fields_from_cats_selector_for_post_1<? print $rand; ?>" ></div>





    <script  type="text/javascript">


    $(document).ready(function(){

      mw_load_post_cutom_fields_from_categories<? print $rand; ?>();
      mw.$('#categorories_selector_for_post_<? print $rand; ?> input[type="radio"]').bindMultiple('change', function(e){
        mw_load_post_cutom_fields_from_categories<? print $rand; ?>();
      });


      <? if($edit_post_mode != false): ?>

      <? endif; ?>



      <? if(intval($data['id']) == 0 and isset($data['subtype']) and trim($data['subtype']) == 'product'): ?>


      var is_price = $("#custom_fields_for_post_<? print $rand; ?>").find('a.mw-field-type-price');
      if(is_price.length == 0){
       createFieldPill(mwd.querySelector("#field-type-price a"));
     }

     <? endif; ?>



     $(window).bind("templateChanged", function(e, el){
      load_iframe_editor();
    });


	//

		//}

  });

    load_iframe_editor();
	/*if(window.parent == undefined && window.parent.mw == undefined){


	} else {

	   $(".is_admin").bind("mouseover", function(){

	 load_iframe_editor();

		$(this).unbind("mouseover");

      });


}*/

function mw_load_post_cutom_fields_from_categories<? print $rand; ?>(){
     var vals = mw.$('#categorories_selector_for_post_<? print $rand; ?> input[name="parent"]:checked').val();
     var holder1 = mw.$('#custom_fields_from_pages_selector_for_post_1<? print $rand; ?>');
     if(vals != undefined){
      i = 1;

      holder1.attr('for','content');
      holder1.attr('save_to_content_id','<? print $data['id'] ?>');
      holder1.attr('rel_id',vals);
      mw.load_module('custom_fields/list','#custom_fields_from_pages_selector_for_post_1<? print $rand; ?>', function(){
          });
    }


        var vals = mw.$('#categorories_selector_for_post_<? print $rand; ?> input[type="checkbox"]:checked').val();
     var holder1 = mw.$('#custom_fields_from_cats_selector_for_post_1<? print $rand; ?>');
     if(vals != undefined){
      i = 1;

      holder1.attr('rel','categories');
      holder1.attr('save_to_content_id','<? print $data['id'] ?>');
      holder1.attr('rel_id',vals);
      mw.load_module('custom_fields/list','#custom_fields_from_cats_selector_for_post_1<? print $rand; ?>', function(){
          });
    }





// mw.log(vals);

}
</script>
    <div class="vSpace"></div>
  </div>
  <? if($edit_post_mode == false): ?>
  <a class="toggle_advanced_settings mw-ui-more" data-for=".pictures-editor-holder" id="pictures-toggle" onclick="mw.tools.memoryToggle(this);" href="javascript:;">Pictures Gallery</a>
  <div class="pictures-editor-holder" style="display: none;">
    <microweber module="pictures/admin" for="content" for-id=<? print $data['id']; ?> />
  </div>
  <div class="vSpace"></div>
  <? endif; ?>
  <? //endif; ?>
  <div class="mw_clear">&nbsp;</div>
  <? /* ONLY FOR POSTS  */ ?>
  <div class="advanced_settings"> <a href="javascript:;" data-for='.advanced_settings_holder' id="advanced-settings-toggler" onclick="mw.tools.memoryToggle(this);"   class="toggle_advanced_settings mw-ui-more">
    <?php _e('Advanced Settings'); ?>
    </a>
    <?php /* <a href="javascript:;" onclick="mw.tools.toggle('.advanced_settings_holder', this);"  class="toggle_advanced_settings mw-ui-btn">
       <span class="ico ioptions"></span> <?php _e('Advanced Settings'); ?>
     </a> */ ?>
    <div class="advanced_settings_holder">
      <div class="vSpace"></div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Description <small class="mw-help" data-help="Short description for yor content.">(?)</small></label>
        <textarea
        class="mw-ui-field" name="description"   placeholder="Describe your page in short"><?php if($data['description']!='') print ($data['description'])?>
</textarea>
      </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Meta Title <small class="mw-help" data-help="Title for this <? print $data['content_type'] ?> that will appear on the search engines on social networks.">(?)</small></label>
        <textarea class="mw-ui-field" name="content_meta_title"  placeholder="Title to appear on the search engines results page."><?php if(isset($data['content_meta_title']) and $data['content_meta_title']!='') print ($data['content_meta_title'])?>
</textarea>
      </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Meta Keywords <small class="mw-help" data-help="Keywords for this <? print $data['content_type'] ?> that will help the search engines to find it. Ex: ipad, book, tutorial">(?)</small></label>
        <textarea class="mw-ui-field" name="content_meta_keywords"  placeholder="Type keywords that describe your content - Example: Blog, Online News, Phones for Sale etc."><?php if(isset($data['content_meta_keywords']) and $data['content_meta_keywords']!='') print ($data['content_meta_keywords'])?>
</textarea>
      </div>
      <div class="vSpace"></div>
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Active <small class="mw-help" data-help="If yes your content will be visible on the site">(?)</small></div>
        <label class="mw-ui-check">
          <input name="is_active" type="radio"  value="n" <? if( '' == trim($data['is_active']) or 'n' == trim($data['is_active'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_active" type="radio"  value="y" <? if( 'y' == trim($data['is_active'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      <? /* PAGES ONLY  */ ?>
      <? if($edit_post_mode == false): ?>
      <div class="vSpace"></div>
      <div class="mw_clear vSpace"></div>
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Home?</div>
        <label class="mw-ui-check">
          <input name="is_home" type="radio"  value="n" <? if( '' == trim($data['is_home']) or 'n' == trim($data['is_home'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_home" type="radio"  value="y" <? if( 'y' == trim($data['is_home'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      <div class="mw_clear vSpace"></div>
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Shop <small class="mw-help" data-help="If yes this page will accept products to be added to it">(?)</small></div>
        <label class="mw-ui-check">
          <input name="is_shop" type="radio"  value="n" <? if( '' == trim($data['is_shop']) or 'n' == trim($data['is_shop'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_shop" type="radio"  value="y" <? if( 'y' == trim($data['is_shop'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      <div class="mw_clear vSpace"></div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Page type</label>
        <div class="mw-ui-select" style="width: 220px;">
          <select name="subtype">
            <option value="<? print $data['subtype'] ?>"   <? if(isset($data['subtype']) and trim($data['subtype']) != '' and trim($data['subtype']) != 'dynamic' and trim($data['subtype']) != 'static'  ): ?>   selected="selected"  <? endif; ?>><? print $data['subtype'] ?></option>
            <option value="static"   <? if( '' == trim($data['subtype']) or 'static' == trim($data['subtype'])): ?>   selected="selected"  <? endif; ?>>static</option>
            <option value="dynamic"   <? if( 'dynamic' == trim($data['subtype'])  ): ?>   selected="selected"  <? endif; ?>>dynamic</option>
          </select>
        </div>
      </div>
      <input name="subtype_value"  type="hidden" value="<? print ($data['subtype_value'])?>" />
      <? endif; ?>
      <?

              if(isset($data['position'])): ?>
      <input name="position"  type="hidden" value="<? print ($data['position'])?>" />
      <? endif; ?>
      <? /* PAGES ONLY  */ ?>
      <div class="mw-ui-field-holder" id="post_pass_field">
        <label class="mw-ui-label">Password <small>(Only the users with the password can have a access)</small></label>
        <input name="password" class="mw-ui-field" type="password" value="" />
      </div>
      <? if(isset($data['id']) and $data['id'] > 0): ?>
      <br />
      <small>Id: <? print ($data['id'])?></small>
      <? endif; ?>
      <? if(isset($data['created_on'])): ?>
      <br />
      <small>Created on: <? print format_date($data['created_on'])?></small>
      <? endif; ?>
      <? if(isset($data['created_on'])): ?>
      <br />
      <small>Updated on: <? print format_date($data['updated_on'])?></small>
      <? endif; ?>
      <? /* PRODUCTS ONLY  */ ?>
      <? if(isset($data['subtype']) and trim($data['subtype']) == 'a_product'): ?>
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 160px">Downloadable product?</div>
        <label class="mw-ui-check">
          <input name="subtype_value" type="radio"  value="normal" <? if( '' == trim($data['subtype_value']) or 'normal' == trim($data['subtype_value'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="subtype_value" type="radio"  value="downloadable" <? if( 'downloadable' == trim($data['subtype_value'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      <? endif; ?>
      <? /*  end of PRODUCTS ONLY  */ ?>
    </div>
    <div class="mw_clear vSpace"></div>
  </div>
</form>
