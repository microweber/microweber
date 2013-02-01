<?
if(!isset($edit_post_mode)){
	$edit_post_mode = false;
}   //  $params['content_type'] = 'post';

 
       $rand = uniqid();


if(isset($params["data-content-id"])){
	$params["data-page-id"] = $params["data-content-id"];
}

if(isset($params["content-id"])){
	$params["data-page-id"] = $params["content-id"];
}
if(isset($params["data-content"])){
	$params["data-page-id"] = $params["data-content"];
}

if(!isset($params["data-page-id"])){
	$params["data-page-id"] = PAGE_ID;
}

$pid = false;
$data = false;
if(isset($params["data-page-id"]) and intval($params["data-page-id"]) != 0){

$data = get_content_by_id(intval($params["data-page-id"]));

}


if($data == false or empty($data )){
include('_empty_content_data.php');
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




//$form_rand_id = //$rand = md5(serialize($data).serialize($params));
?>
<script  type="text/javascript">
  mw.require('forms.js');
  mw.require('url.js');
 </script>
<script type="text/javascript">




$(document).ready(function(){
	


mw.$('#admin_edit_page_form_<? print $rand; ?>').submit(function() {

	 

 

 mw_before_content_save<? print $rand; ?>();
 mw.form.post(mw.$('#admin_edit_page_form_<? print $rand; ?>') , '<? print site_url('api/save_content') ?>', function(){
                        	<? if(intval($data['id']) == 0): ?>


                            mw.url.windowHashParam("action", "edit<? print $data['content_type'] ?>:" + this);

							 mw.url.windowHashParam("new_content", 'true');
							 mw.reload_module('[data-type="pages_menu"]');
  <? else: ?>
// mw_after_content_save<? print $rand; ?>();
                             <? endif; ?>
	

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

        mw.tools.scaleTo('#mw-main-postpage-editor', 950, 600);

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
			 mw.$('#admin_edit_page_form_content_parent_info<? print $rand; ?>').html('<a href="javascript:edit_page_open_page_and_menus<? print $rand; ?>()">Parent: '+ title+'</a>');
		}

	 }
 }
 
 function edit_page_open_page_and_menus<? print $rand; ?>(){
	   mw.$('.ed_page_and_menus_opener_link').addClass('active');
   		mw.$('.page_and_menus_holder').show();
   
  		 var sel_hl = mw.$('.mw_parent_page_sel_holder')[0];
		 
		  mw.tools.scrollTo(sel_hl)
	 		 mw.tools.highlight(sel_hl, '#d8ffc3',300,5000)
	 
	 
	 
 }
 
 function mw_on_save_complete<? print $rand; ?>(){
	//alert(1);
    mw.notification.success("<?php _e('All changes are saved'); ?>.");
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
		
				mw.reload_module('[data-type="pages_menu"]');
				  <? if($edit_post_mode != false): ?>
					mw.reload_module('[data-type="posts"]');
				  <? endif; ?>

			$id = $id.toString();

				mw.reload_module('#admin_edit_page_form_<? print $rand; ?> .module[data-type="custom_fields"]');
				if( typeof $id =='string'){
							$id = $id.replace(/"/g, "");
					$.get('<? print site_url('api_html/content_link/') ?>'+$id, function(data) {
						   window.top.location.href = data+'/editmode:y';
			
					});
			
				}
			

			
			mw_on_save_complete<? print $rand; ?>()
			


		}
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
if(intval($data['id']) == 0 and isset($params['subtype']) and trim($params['subtype']) != '') {
		  $data['subtype'] = $params['subtype'];
		  $t =      $data['subtype'];
	} else if($data['content_type'] == 'post'){

     //   $data['subtype'] = 'post';
        $t =      $data['subtype'];
     } else {
            $t =      $data['content_type'];
     }


 ?>
      <? if(intval($data['id']) > 0): ?>
      <span class="mw-title-field-label mw-title-field-label-<?php print strtolower(ucfirst( $t)); ?>"></span>
      <input name="title" class="mw-ui-field mw-title-field"  type="text" value="<? print ($data['title'])?>" />
      <? else : ?>
      <?
    

     ?>
      <span class="mw-title-field-label mw-title-field-label-<?php print strtolower(ucfirst( $t)); ?>"></span>
      <input name="title" class="mw-ui-field mw-title-field"   type="text" value="<?php print ucfirst($t); ?> <? if($data['content_type'] == 'post' and $data['subtype'] == 'post'):?><?php _e("Title"); ?><? else : ?><?php _e("Name"); ?><? endif ?>" />
      <? endif; ?>
    </div>
    <div class="edit-post-url"> <span class="view-post-site-url"><?php print site_url(); ?></span><span class="view-post-slug active" onclick="mw.slug.toggleEdit()"><? print ($data['url'])?></span>
      <input name="content_url" class="edit-post-slug" onkeyup="mw.slug.fieldAutoWidthGrow(this);" onblur="mw.slug.toggleEdit();mw.slug.setVal(this);" type="text" value="<? print ($data['url'])?>" />
      <span class="edit-url-ico" onclick="mw.slug.toggleEdit()"></span> </div>
    <div class="admin_edit_page_content_parent" id="admin_edit_page_form_content_parent_info<? print $rand; ?>"></div>
  </div>
  <?
    if(!isset($data['content'])){
        $data['content'] = '';
    }?>
  <script>
 
load_iframe_editor = function(){
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
	      	
		 mw.wysiwyg.iframe_editor(area, ifr_ed_url+'&isolate_content_field=1&edit_post_mode=<? print  $edit_post_mode ?>&content_type=<? print  $data['content_type'] ?>'+ifr_ed_url_more);

		
		
		
		

  }
  





  __loadpreview = function(){

  if(!!mw.templatePreview){
          if(!mw.templatePreview._once){
            mw.templatePreview._once = true;
            mw.templatePreview.generate();
          }
        }

  }
</script>

<div class="mw-scaleto-holder">
    <div id="mw-main-postpage-editor">
      <div id="mw-main-postpage-editor-drag-handle"><span class="mw-close"></span></div>
      <div id="mw-editor<? print $rand; ?>" style="height: 310px;width:623px;"></div>
      <textarea name="content"  style="display:none" id="mw-editor<? print $rand; ?>_src"></textarea>
      <div class="mw-postaction-bar">
        <div class="left">
            <a href="javascript:;" id="mw-scaleeditor" class="mw-ui-btn-rect mw-btn-single-ico"><span class="ico ifullscreen"></span></a>
      </div>
        <div class="right">
          <?php /*     <span class="mw-ui-btn">Preview</span>
        <span class="mw-ui-btn mw-ui-btn-green">Publish Page</span> */ ?>
          <span class="mw-ui-btn go-live">Go Live Edit</span> <span class="mw-ui-btn mw-ui-btn-green" style="min-width: 66px;" onclick="$(this).parents('form').submit();">Save</span> </div>
      </div>
      <div class="iframe_fix"></div>

    </div>

</div>

  <? if($edit_post_mode == false): ?>
  <a class="toggle_advanced_settings mw-ui-more" onclick="mw.tools.toggle('.pictures-editor-holder', this);" href="javascript:;">Pictures Gallery</a>
  <div class="pictures-editor-holder" style="display: none;">
    <microweber module="pictures" view="admin" for="content" for-id=<? print $data['id'] ?> />
  </div>
  <div class="vSpace"></div>
  <? endif; ?>
  <? /* PAGES ONLY  */ ?>
  <? if($edit_post_mode == false): ?>
  <a class="toggle_advanced_settings mw-ui-more" onclick="mw.tools.toggle('.mw-layout-selector-holder', this, __loadpreview);" href="javascript:;">Template</a>
  <?  //  d($data); ?>
  <div class="mw-layout-selector-holder" style="display: none;">
    <module data-type="content/layout_selector" data-page-id="<? print ($data['id'])?>"  />
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
  <a href="javascript:;" onclick="mw.tools.toggle('.page_and_menus_holder', this);"  class="toggle_advanced_settings mw-ui-more ed_page_and_menus_opener_link">
  <?php _e('Page &amp; Menus'); ?>
  </a>
  <div class="page_and_menus_holder" style="display: none;">
    <div class="vSpace"></div>
    <div class="mw-ui-field-holder mw_parent_page_sel_holder">
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
  <a href="javascript:;" onclick="mw.tools.toggle('#edit_post_select_category', this);" class="mw-ui-more toggler-active">
  <?php _e("Add to Page &amp; Category"); ?>
  </a>
  <div class="vSpace"></div>
  <div id="edit_post_select_category" style="display: block">
    <div class="mw-ui-field mw-tag-selector" id="mw-post-added-<? print $rand; ?>" style="width: 602px;">
      <input type="text" class="mw-ui-invisible-field" value="Click here to add to categories and pages." data-default="Click here to add to categories and pages." style="width:250px;" />
    </div>
    <script>

        $(document).ready(function(){


          mw.tools.tag({
            tagholder:'#mw-post-added-<? print $rand; ?>',
            items: ".mw-ui-check",
            itemsWrapper: mwd.querySelector('#mw-category-selector-<? print $rand; ?>'),
            method:'parse',
            onTag:function(){
                load_iframe_editor();
            },
            onUntag:function(){
                load_iframe_editor();
            }
          });


        });

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
      <? $in_cats = get('from=taxonomy_items&fields=parent_id&to_table=table_content&to_table_id='.$data['id']);
	if(isarr($in_cats)){
	foreach($in_cats as $in_cat){
		$categories_active_ids = $categories_active_ids.','.$in_cat['parent_id'];
	}
	}
	 //d($categories_active_ids);
	 ?>
      <microweber module="categories/selector"  categories_active_ids="<? print $categories_active_ids; ?>" for="content" id="categorories_selector_for_post_<? print $rand; ?>" to_table_id="<? print $data['id'] ?>"  active_ids="<? print intval($data['parent']) ?>" <? print $strz ?> <? print $shopstr ?> />
      <? else: ?>
      <? if(isset($params["parent-page-id"]) and intval($params["parent-page-id"]) > 0){
		 
		 $selected_parent_ategory_id = 'active_ids="'.$params["parent-page-id"].'"';
	 } ?>
      <microweber module="categories/selector"   categories_active_ids="<? print $categories_active_ids; ?>"  id="categorories_selector_for_post_<? print $rand; ?>" to_table_id="<? print $data['id'] ?>"  active_ids="<? print intval($data['parent']) ?>" for="content" <? print $strz ?> <? print $selected_parent_ategory_id ?> <? print $shopstr ?> />
      <? endif; ?>
    </div>
    <div class="vSpace"></div>
    <script type="text/javascript">
    $(mwd).ready(function(){
		if(!!mw.treeRenderer){
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
  <a class="toggle_advanced_settings mw-ui-more" onclick="mw.tools.toggle('.pictures-editor-holder', this);" href="javascript:;">Pictures Gallery</a>
  <div class="pictures-editor-holder" style="display: none;">
    <module type="pictures" view="admin" for="content" for-id=<? print $data['id'] ?>  />
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
  <a href="javascript:;" class="mw-ui-more" onclick="mw.tools.toggle('#custom_fields_for_post_<? print $rand; ?>', this);">
  <?php _e("Custom Fields"); ?>
  </a>
  <div class="vSpace"></div>
  <?php /* <a href="javascript:;" class="mw-ui-btn-rect" onclick="mw.tools.toggle('#the_custom_fields', this);"><span class="ico iSingleText"></span><?php _e("Custom Fields"); ?></a>  */ ?>
  <div id="custom_fields_for_post_<? print $rand; ?>"  style="display:none;">
    <div class="vSpace"></div>

    <module type="custom_fields/admin"    for="table_content" to_table_id="<? print $data['id'] ?>" id="fields_for_post_<? print $rand; ?>" content-subtype="<? print $data['subtype'] ?>" />


    <div class="custom_fields_from_parent"  id="custom_fields_from_categorories_selector_for_post_1<? print $rand; ?>" ></div>



    <script  type="text/javascript">


$(document).ready(function(){

		  mw_load_post_cutom_fields_from_categories<? print $rand; ?>();
		  mw.$('#categorories_selector_for_post_<? print $rand; ?> input[type="radio"]').bindMultiple('change', function(e){
		    mw_load_post_cutom_fields_from_categories<? print $rand; ?>();
          });


    <? if($edit_post_mode != false): ?>

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
 var holder1 = mw.$('#custom_fields_from_categorories_selector_for_post_1<? print $rand; ?>');
 if(vals != undefined){
	 i = 1;

				 holder1.attr('for','table_content');
				 holder1.attr('save_to_content_id','<? print $data['id'] ?>');
				 holder1.attr('to_table_id',vals);

		
				 mw.load_module('custom_fields/list','#custom_fields_from_categorories_selector_for_post_1<? print $rand; ?>', function(){

				 // holder1.find('[name="to_table_id"]').val('<? print $data['id'] ?>');
					 });
	 
 }

 
 
 
 
 
// mw.log(vals);
	
}
</script>

  </div>
  <? //endif; ?>
  <div class="mw_clear">&nbsp;</div>
  <? /* ONLY FOR POSTS  */ ?>
  <div class="advanced_settings"> <a href="javascript:;" onclick="mw.tools.toggle('.advanced_settings_holder', this);"  class="toggle_advanced_settings mw-ui-more">
    <?php _e('Advanced Settings'); ?>
    </a>
    <?php /* <a href="javascript:;" onclick="mw.tools.toggle('.advanced_settings_holder', this);"  class="toggle_advanced_settings mw-ui-btn-rect">
       <span class="ico ioptions"></span> <?php _e('Advanced Settings'); ?>
    </a> */ ?>
    <div class="advanced_settings_holder">
      <div class="vSpace"></div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Description</label>
        <textarea
            class="mw-ui-field" name="description"
            onfocus="mw.form.dstatic(event);"
            onblur="mw.form.dstatic(event);" data-default="Describe your page in short"><?php if($data['description']==''){print 'Describe your page in short';} else{print $data['description'];} ?>
</textarea>
      </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Meta Keywords</label>
        <textarea class="mw-ui-field" name="metakeys"
            onfocus="mw.form.dstatic(event);"
            onblur="mw.form.dstatic(event);"
            data-default="Type keywords describing your content best - Example: Blog, Online News, Phones for Sale etc.">Type keywords describing your content best - Example: Blog, Online News, Phones for Sale etc.</textarea>
      </div>
      <? /* PAGES ONLY  */ ?>
      <? if($edit_post_mode == false): ?>
      <div class="vSpace"></div>
      <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Active?</div>
        <label class="mw-ui-check">
          <input name="is_active" type="radio"  value="n" <? if( '' == trim($data['is_active']) or 'n' == trim($data['is_active'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_active" type="radio"  value="y" <? if( 'y' == trim($data['is_active'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
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
        <div class="mw-ui-label left" style="width: 130px">Is Shop?</div>
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
      <? /* PAGES ONLY  */ ?>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Password <small>(Only the users which have a password can have a access)</small></label>
        <input name="password" style="width: 603px;" class="mw-ui-field" type="password" value="" />
      </div>
    </div>
    <div class="mw_clear vSpace"></div>
  </div>
</form>
