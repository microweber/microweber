<?php if(is_admin()==false) { 
return array('error' => 'Not logged in as admin');
 }


if(!isset($params['load_list'])){
 return 'Error: Provide load_list parameter!'; 	
}

 ?>
<script  type="text/javascript">
  mw.require('<?php print $config['url_to_module']; ?>forms_data_manager.js');

  </script>
<?php $def =  _e("Search for data", true); 

$load_list = $params['load_list'];
 ?>
<?php 
if(trim($load_list) == 'default'){
	$data = array();
	$data['title'] = "Default list";
	$data['id'] = "default";
} else {
	$data = get_form_lists('single=1&id='.$load_list); 

}

?>

<h2 class="left to-edit"  <?php  if(trim($load_list) != 'default'): ?>id="form_field_title" <?php endif; ?> style="max-width: 360px" ><?php print ($data['title']); ?></h2>
<input
        name="forms_data_keyword"
        id="forms_data_keyword"
        autocomplete="off"
        class="right mw-ui-searchfield"
        type="search"

        placeholder='<?php print $def; ?>'
        onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"
      />
<div class="export-label"> <span><?php _e("Export data"); ?>:</span> <a href="javascript:;" onclick="javascript:mw.forms_data_manager.export_to_excel('<?php print $data['id'] ?>');"><span class="ico iexcell"></span><?php _e("Excel"); ?></a> </div>
<div class="mw_clear"></div>
