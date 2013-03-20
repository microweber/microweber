<div id="mw_edit_pages_content">

<div id="mw_index_contact_form">
  <div id="mw_edit_page_left" style="width: 192px;">
    <? $mw_notif =  (url_param('mw_notif'));
if( $mw_notif != false){
 $mw_notif = read_notification( $mw_notif);	

}

 
  ?>
    <? if(isarr($mw_notif) and isset($mw_notif['to_table_id'])): ?>
    <script type="text/javascript">

$(document).ready(function(){
 
    window.location= "<? print $config['url']; ?>/load_list:<? print $mw_notif['to_table_id']; ?>";

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
    <div class="mw-admin-sidebar">
    <div class="mw-admin-side-nav">
      <?php $info = module_info($config['module']);  ?>
       <?php module_ico_title($info['module']); ?>
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
    </div>
  </div>
  <div class="mw_edit_page_right" style="padding: 20px; width: 730px;">
    <?




 if($load_list): ?>
    <script type="text/javascript">


function mw_forms_data_to_excel(){
    $('#forms_data_module').attr('export_to_excel', 1 );
    mw.reload_module('#forms_data_module');
}

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
 </script>
    <?php $def =  _e("Search for data", true);  ?>
    <?php $data = get_form_lists('single=1&id='.$load_list); ?>

    <h2 class="left to-edit" style="max-width: 360px" onclick="mw.tools.liveEdit(this, false);"><?php print ($data['title']); ?></h2>

    <input
        name="forms_data_keyword"
        id="forms_data_keyword"
        autocomplete="off"
        class="right"
        type="search"
        value="<?php print $def; ?>"
        placeholder='<?php print $def; ?>'
        onkeyup="mw.form.dstatic(event);mw.on.stopWriting(this, function(){mw.url.windowHashParam('search', this.value)});"
      />
    <div class="export-label"> <span>Export data:</span> <a href="javascript:;" onclick="mw_forms_data_to_excel()"><span class="ico iexcell"></span>Excel</a> </div>
    <div class="mw_clear"></div>
    <div class="vSpace"></div>
    <module type="forms/list" load_list="<? print $load_list ?>"  for_module="<? print $config["the_module"] ?>" id="forms_data_module" />



    <span class="mw-ui-delete right" onclick="mw.forms_data_manager.delete_list(<?php print $load_list; ?>);">Delete list</span>

    <? endif; ?>
  </div>
</div>
</div>
