<?php $pages = get_content('content_type=page&subtype=dynamic&limit=1000'); ?>
<?php $posts_parent_page = get_option('data-content-id', $params['id']); ?>


 

<script type="text/javascript">


    mw.live_edit_load_cats_list = function () {
       
        mw.$('.mw-module-category-manager').hide();
        mw.$("#mw-live-edit-cats-tab").removeClass('active');
		 
		
		var cont_id = 	 mw.$("#mw_set_categories_tree_root_page").val();	
 
		
		mw.$("#mw_add_cat_live_edit").attr("page-id",cont_id);
        mw.load_module('categories/manage', '#mw_add_cat_live_edit', function () {

        });
		 CatTabs.set(3);
    }
    mw.load_quick_cat_edit = function ($id) {
        CatTabs.set(2);

        if ($id == undefined) {
            mw.$("#mw_select_cat_to_edit_dd").val();
        }
        mw.$("#mw_add_cat_live_edit").attr("data-category-id", $id);
		 

			var cont_id = 	 mw.$("#mw_set_categories_tree_root_page").val();	
			if(cont_id == 0){
			var cont_id = 	 mw.$("#mw_page_id_front").val();
		 
		}
 
		
		mw.$("#mw_add_cat_live_edit").attr("page-id",cont_id);
		
        mw.load_module('categories/edit_category', '#mw_add_cat_live_edit', function () {
            $(mwd.body).removeClass("loading");
        });
    }

    $(mwd).ready(function(){
        CatTabs = mw.tabs({
          nav:'.mw-ui-btn-nav-tabs a',
          tabs:'.tab',
          onclick:function(){
            mw.$('.mw-module-category-manager').show();
          }
        });
    });
	
	
	$(document).ready(function(){
 
	   mw.$("#mw_set_categories_tree_root_page").change(function () {
		  var val = this.value;
		   
		  mw.$('#mw_add_cat_live_edit').attr('page-id',val);
			mw.reload_module('#mw_add_cat_live_edit')
		});
 
  });
  
  
  
</script>




<div class="mw-ui-box-content" >
    <style scoped="scoped">

    .tab{
      display: none;
    }

    </style>

    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
        <a class="mw-ui-btn active" href="javascript:;">
                <?php _e("Options"); ?>
            </a>
        <a class="mw-ui-btn" href="javascript:;">
                <?php _e("Skin/Template"); ?>
            </a>
        <a class="mw-ui-btn" href="javascript:;" id="mw-live-edit-cats-tab" onclick="mw.live_edit_load_cats_list()">
                <?php _e("Edit categories"); ?>
            </a>
    </div>
    <a href="javascript:mw.load_quick_cat_edit(0);" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert pull-right">
    <span class="mw-icon-category"></span>
    <span>
	<?php _e("New category"); ?>
	</span></a>

<div class="mw-ui-box mw-ui-box-content">    <div class="tab" style="display: block">
        <label class="mw-ui-label">
            <?php _e("Show Categories From"); ?>
        </label>
<input type="hidden" id="mw_page_id_front" value="<?php print PAGE_ID ?>" />






        <select name="data-content-id" id="mw_set_categories_tree_root_page" class="mw-ui-field mw_option_field" data-also-reload="<?php print  $config['the_module'] ?>">
            <option value="0"   <?php if ((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>
                    title="<?php _e("None"); ?>">
                <?php _e("None"); ?>
            </option>
            <?php
            $pt_opts = array();
            $pt_opts['link'] = "{empty}{title}";
            $pt_opts['list_tag'] = " ";
            $pt_opts['list_item_tag'] = "option";
            $pt_opts['active_ids'] = $posts_parent_page;
            //$pt_opts['include_categories'] = true;
            $pt_opts['active_code_tag'] = '   selected="selected"  ';
            pages_tree($pt_opts);


            ?>
        </select>
        
        
     
     
     <?php if($posts_parent_page != false and intval($posts_parent_page) > 0): ?>
<?php $category_id =  get_option('data-category-id', $params['id']); ?>
 
<div class="mw-ui-field-holder">
<label class="mw-ui-label"><?php _e("Show only from category"); ?></label>

  <select name="data-category-id" id="selcted_categogy_for_parent_category"  class="mw-ui-field mw_option_field"   data-also-reload="<?php print  $config['the_module'] ?>"    >

    <option  value=''  <?php if((0 == intval($category_id))): ?>   selected="selected"  <?php endif; ?>><?php _e("Select a category"); ?></option>

    <?php
        $pt_opts = array();
        $pt_opts['link'] = "{empty}{title}";
        $pt_opts['list_tag'] = " ";
        $pt_opts['list_item_tag'] = "option";
        $pt_opts['active_ids'] = $category_id;
        $pt_opts['active_code_tag'] = '   selected="selected"  ';
        $pt_opts['rel'] = 'content';
        $pt_opts['rel_id'] = $posts_parent_page;
        category_tree($pt_opts);
  ?>
  	    </option>

  </select>

</div>
<?php endif; ?>
     
        
        
        
        
        
        
        

    </div>
    <div class="tab">
        <module type="admin/modules/templates"/>
    </div>
    <div class="tab">
        <div id="mw_add_cat_live_edit"></div>
     </div></div>
</div>