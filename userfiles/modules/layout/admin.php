
<style type="text/css">

.dynamic_layout_choooser li{
  list-style: none;

  margin: 7px 0;
  clear: both;
  overflow: hidden;
}

.mw_module_hold{
  clear: both;
  overflow: hidden;
  display: block;
  padding: 7px; cursor: pointer;
}

.dynamic_layout_choooser li .mw_module_hold:hover, .dynamic_layout_choooser li.active .mw_module_hold{
  background: #EDEDED;
}

.dynamic_layout_choooser li .mw_module_image{
  float: left;
  margin-right: 7px;
}
.dynamic_layout_choooser li .module_name{
  white-space: nowrap;
  float: left;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-top: 8px;
}

</style>



<div class="mw_simple_tabs mw_tabs_layout_simple">
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active"><?php _e("Content list"); ?></a></li>
    <li><a href="javascript:;"><?php _e("Layouts"); ?></a></li>
  </ul>
  <div class="tab">
<?php 
$params['global'] = 1;


include_once($config['path_to_module'].'../posts/admin_live_edit_tab1.php'); ?>
  </div>
  <div class="tab">

 <?php $layout =  get_option('data-layout', $params['id']); ?>
 <input name="data-layout" id="data-layout-set-val" class="mw_option_field semi_hidden" value="<?php print $layout; ?>" />
 <script>
$(document).ready(function(){
    curr_layot = mwd.getElementById('data-layout-set-val').value;
	mw.$(".dynamic_layout_choooser .list-elements li").each(function(){
	  var el = $(this);
      if(el.dataset("module-name") == curr_layot){
          el.addClass("active");
      }
      el.click(function(){
        if(!el.hasClass("active")){
            mw.$(".dynamic_layout_choooser .list-elements li").removeClass("active");
  	        mw.$('#data-layout-set-val').val($(this).attr('data-module-name'));
      	    mw.$('#data-layout-set-val').trigger('change');
            el.addClass("active");
        }

    	});
	});

});
</script>

 <div class="mw-o-box">
 <div class="mw-o-box-header"><?php _e("Choose layout"); ?></div>

     <div class="dynamic_layout_choooser mw-o-box-content">

    <microweber module="admin/modules/list_elements" layout_type="dynamic" />

  </div>


 </div>

  </div>

  </div>
  <div class="mw_clear"></div>
  <div class="vSpace"></div>
</div>

 
 