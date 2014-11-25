<?php //$rand = uniqid(); ?>
<?php $pages = get_content('content_type=page&limit=1000'); ?>
<?php $posts_parent_page = get_option('data-parent', $params['id']); ?>
<?php $posts_maxdepth = get_option('maxdepth', $params['id']); ?>
<?php $include_categories = get_option('include_categories', $params['id']); ?>
<?php

?>
<script type="text/javascript">

 mw.add_new_page = function(id){
	   if(id == undefined){
	    var id = 0;
	   }
	   
	   var par_page = $("#mw_change_pages_parent_root").val();
	   
       pTabs.set(3);
	   mw.$('#mw_page_create_live_edit').removeAttr('data-content-id');
 	   mw.$('#mw_page_create_live_edit').attr('from_live_edit',1);
	   mw.$('#mw_page_create_live_edit').attr('content_type', 'page');
	   
	   mw.$('#mw_page_create_live_edit').attr('parent', par_page);
	   
	   mw.$('#mw_page_create_live_edit').attr('content-id', id);
	   mw.$('#mw_page_create_live_edit').attr('quick_edit',1);
	   mw.$('#mw_page_create_live_edit').removeAttr('live_edit');
      mw.load_module('content/edit_page', '#mw_page_create_live_edit', function(){
        parent.mw.tools.modal.resize("#"+thismodal.main[0].id, 900, mw.$('#settings-container').height()+25, false);

        mw.$(".preview_frame_wrapper .mw-overlay").removeAttr("onclick");
        mw.$("#mw_pages_list_tree_live_edit_holder").show().visibilityDefault()

      })
 	}


 $(document).ready(function(){
    pTabs = mw.tabs({
        nav:".mw-ui-btn-nav-tabs a",
        tabs:".tab"
    });
 });
 
  $(document).ready(function(){
 
	   mw.$("#mw_change_pages_parent_root").change(function () {
		  var val = this.value;
		  mw.$('#mw_pages_list_tree_live_edit').attr('parent',val);
			mw.reload_module('#mw_pages_list_tree_live_edit')
		});
 
  });

</script>

<style>

.tab{
  display: none;
}

#content-edit-settings-tabs.fixed{
  margin-top: -70px;
}

</style>

<div class="module-live-edit-settings">

    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium pull-right" onclick="mw.add_new_page();"><span class="mw-icon-page"></span><?php _e("Add new page"); ?></a>
	<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
		<a class="mw-ui-btn active" href="javascript:;">
			<?php _e("Options"); ?>
			</a>
		<a href="javascript:;" class="mw-ui-btn">
			<?php _e("Skin/Template"); ?>
			</a>
		<a href="javascript:;" class="mw-ui-btn">
			<?php _e("Manage pages"); ?>
			</a>
	   <a id="add_new_post" href="javascript:;"></a>
	</div>
	<div class="mw-ui-box mw-ui-box-content">


	<div class="tab" style="display: block">
      	   <div class="mw-ui-row-nodrop">
                <div class="mw-ui-col">
                    <label class="mw-ui-label">
              			<?php _e("Pages & Sub-Pages From"); ?>
              		</label>
        			<select name="data-parent" id="mw_change_pages_parent_root" class="mw-ui-field mw_option_field">
        				<option
                            valie="0"   <?php if ((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>>
        				<?php _e("None"); ?>
        				</option>
        				<?php
                            $pt_opts = array();
                            $pt_opts['link'] = "{empty}{title}";
                            $pt_opts['list_tag'] = " ";
                            $pt_opts['list_item_tag'] = "option";

                            $pt_opts['active_ids'] = $posts_parent_page;

                            $pt_opts['active_code_tag'] = '   selected="selected"  ';

                            pages_tree($pt_opts);


                        ?>
        				<?php if (defined('PAGE_ID')): ?>
        				<option value="<?php print PAGE_ID; ?>">[use current page]</option>
        				<?php endif; ?>
        			</select>
                </div>
              <div class="mw-ui-col">
                      <label class="mw-ui-label">
            			<?php _e("Show Categories from page"); ?>
            		    </label>
            			<select name="include_categories" class="mw-ui-field mw_option_field">
            				<option value="y"  <?php if ('y' == $include_categories): ?>   selected="selected"  <?php endif; ?> >
            				<?php _e("Yes"); ?>
            				</option>
            				<option value="n"  <?php if ('y' != $include_categories): ?>   selected="selected"  <?php endif; ?> >
            				<?php _e("No"); ?>
            				</option>
            			</select>
              </div>
</div>



<div class="mw-ui-field-holder">
		<label class="mw-ui-label">
			<?php _e("Max depth"); ?>
		</label>

			<select name="maxdepth" class="mw-ui-field mw_option_field">
				<option value="none" selected>
				<?php _e("Default"); ?>
				</option>
				<?php for ($i = 1; $i < 10; $i++): ?>
				<option
                        value="<?php print $i ?>" <?php if (($i == $posts_maxdepth)): ?>   selected="selected"  <?php endif; ?>> <?php print $i ?></option>
				<?php endfor; ?>
			</select>
</div>

	</div>
	<div class="tab">
		<module type="admin/modules/templates"/>
	</div>
	<script type="text/javascript">
        mw.require('forms.js', true);
    </script> 
	<script type="text/javascript">
	
	mw.on.moduleReload("mw_pages_list_tree_live_edit", function(){
		 mw.manage_pages_sort();
		
	 });


        mw.manage_pages_sort = function () {
            if (!mw.$("#mw_pages_list_tree_live_edit").hasClass("ui-sortable")) {
                 mw.$("#mw_pages_list_tree_live_edit ul").sortable({
                    items: 'li',

                    handle: '.pages_tree_link',
                    update: function () {
                        var obj = {ids: []}
                        $(this).find('.pages_tree_link').each(function () {
                            var id = this.attributes['data-page-id'].nodeValue;
                            obj.ids.push(id);
                        });

						if(mw.notification != undefined){
							mw.notification.success('Saving...');
						  }

                        $.post("<?php print api_link('content/reorder'); ?>", obj, function () {

						 if(mw.notification != undefined){
							mw.notification.success('Reloading module...');
						  }
                            mw.reload_module_parent('pages');


                        });
                    },
                    start: function (a, ui) {
                        $(this).height($(this).outerHeight());
                        $(ui.placeholder).height($(ui.item).outerHeight())
                        $(ui.placeholder).width($(ui.item).outerWidth())
                    },

                    //placeholder: "custom-field-main-table-placeholder",
                    scroll: false


                });

            }
        }
        $(document).ready(function () {
            mw.manage_pages_sort();
        });
    </script>
	<div class="tab">
		<div class="mw-ui-category-selector mw-ui-manage-list" id="mw_pages_list_tree_live_edit_holder" style="visibility: visible;display: block">
			<?php
        $pt_opts = array();
        $pt_opts['link'] = '<a data-page-id="{id}" class="pages_tree_link {nest_level}"  data-type="{content_type}"   data-shop="{is_shop}"  subtype="{subtype}"   href="javascript:mw.add_new_page({id})">{title}</a>';
$pt_opts['ul_class'] = 'pages_tree cat_tree_live_edit';
$pt_opts['li_class'] = 'sub-nav';
 
      //  pages_tree($pt_opts);


        ?>
		
		<module type="pages" link="javascript:mw.add_new_page({id})" ul-class="pages_tree cat_tree_live_edit"  li-class="sub-nav" id="mw_pages_list_tree_live_edit" parent="<?php print $posts_parent_page ?>" />
		
		
		
		</div>
	</div>
	<div class="tab">
		<div id="mw_page_create_live_edit"></div>
	</div>
	</div>
</div>
