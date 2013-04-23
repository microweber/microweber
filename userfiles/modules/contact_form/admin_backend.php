<div id="mw_edit_pages_content">
  <div id="mw_index_contact_form">
    <div id="mw_edit_page_left" class="mw_edit_page_default">
      <? $mw_notif =  (url_param('mw_notif'));
if( $mw_notif != false){
 $mw_notif = read_notification( $mw_notif);	

}

 
  ?>
      <? if(isarr($mw_notif) and isset($mw_notif['rel_id'])): ?>
      <script type="text/javascript">

$(document).ready(function(){
 
    window.location= "<? print $config['url']; ?>/load_list:<? print $mw_notif['rel_id']; ?>";

});
 
</script>
      <? else :  ?>
      <? endif; ?>
      <?
 
mark_notifications_as_read('contact_form');
 
	
$load_list = 'default';
if((url_param('load_list') != false)){
    $load_list = url_param('load_list');
}


   ?>
      <? 
$templates = '';
$load_templates = false;
if((url_param('templates') != false)){
    $templates = url_param('templates');
	if($templates == 'browse' or $templates == 'add_new'){
		$load_list = false;
		$load_templates = $templates;
	}
}

?>
      <div class="mw-admin-sidebar">
        <?php $info = module_info($config['module']);  ?>
        <?php module_ico_title($info['module']); ?>
        <div class="mw-admin-side-nav side-nav-max">
          <div class="vSpace"></div>
          <ul>
            <li><a   <?php if($load_list == 'default'){ ?> class="active" <?php } ?> href="<? print $config['url']; ?>/load_list:default" >Default list</a></li>
            <? $data = get_form_lists('module_name=contact_form'); ?>
            <? if(isarr($data )): ?>
            <? foreach($data  as $item): ?>
            <li><a <?php if($load_list == $item['id']){ ?> class="active" <?php } ?> href="<? print $config['url']; ?>/load_list:<? print $item['id']; ?>"><? print $item['title']; ?></a></li>
            <? endforeach ; ?>
            <? endif; ?>
          </ul>
          <div class="vSpace"></div>
        </div>
        <h2>Templates</h2>
        <a href="<? print $config['url']; ?>/templates:browse" class="<?php if($templates == 'browse'){ ?> active <?php }?> mw-ui-btn mw-ui-btn-hover">My templates</a> <a href="<? print $config['url']; ?>/templates:add_new" class="<?php if($templates == 'add_new'){ ?> active <?php }?>mw-ui-btn mw-ui-btn-green">Get more templates</a> </div>
    </div>
    <div class="mw_edit_page_right" style="padding: 20px;">
      <?




 if($load_list): ?>
      <script type="text/javascript">


 

mw.on.hashParam('search', function(){


  var field = mwd.getElementById('forms_data_keyword');

  if(!field.focused){ field.value = this; }

  if(this  != ''){
    $('#forms_data_module').attr('keyword',this);
  } else {
    $('#forms_data_module').removeAttr('keyword' );
  }

  $('#forms_data_module').removeAttr('export_to_excel');


 mw.reload_module('#forms_data_module', function(){
    mw.$("#forms_data_keyword").removeClass('loading');
 });


});


 

$(document).ready(function(){
	 $("#form_field_title").click(function() {
		mw.tools.liveEdit(this, false,  function(){
			var new_title =  this 
			 mw.forms_data_manager.rename_form_list('<? print $load_list ?>',new_title );
		 });

		
	});
   
});
 
 

 </script>
      <module type="forms/list_toolbar"  load_list="<? print $load_list ?>"   />
      <div class="vSpace"></div>
      <module type="forms/list" load_list="<? print $load_list ?>"  for_module="<? print $config["the_module"] ?>" id="forms_data_module" />
      <? if(strtolower(trim($load_list)) != 'default'): ?>
      <span class="mw-ui-delete right" onclick="mw.forms_data_manager.delete_list('<?php print addslashes($load_list); ?>');">Delete list</span>
      <? endif; ?>
      <? endif; ?>
      <? if($load_templates == true): ?>
      <module type="admin/templates/browse" for="<? print $config["the_module"] ?>"  />
      <? else : ?>
      <? endif; ?>
    </div>
  </div>
</div>
