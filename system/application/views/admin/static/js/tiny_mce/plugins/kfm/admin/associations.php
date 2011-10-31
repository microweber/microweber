<?php
require_once('initialise.php');
require_once('functions.php');
if(!($kfm->isAdmin() || $kfm->setting('allow_user_file_associations'))) die ('Users are not allowed to create their own file associations');
//$kfm->requireAdmin();
//if($kfm->user_status!=1)die ('No authorization to view this page');
$uid=$kfm->user_id;
$extensions=db_fetch_all('SELECT * FROM '.KFM_DB_PREFIX.'plugin_extensions WHERE user_id='.$uid);
?>
<script type="text/javascript">
$(function(){
          $('#new_association_blueprint').dialog({
            modal: true,
            autoOpen: false,
            width: 500,
            buttons:{ 'Add extension': function(){
                var ext = $('#new_association_extension');
                var plugin = $('#plugin_selector_0222');
				        $.post('association_new.php',{extension:ext.val(),plugin:plugin.val()},function(res){eval(res);});
                $(this).dialog('close');
              }
            }
          });
});
function new_association(){
  $('#new_association_blueprint').dialog('open');
}
function change_association_plugin(id){
	var newval=$("#plugin_selector_"+id).val();
	$.post('association_change_plugin.php',{aid:id,plugin:newval},function(res){eval(res);});
}
function association_delete(id){
  $('<div title="Delete association?">Are you sure you want to delete this file association?</div>').dialog({
    modal: true,
    buttons: {
      "Delete": function(){
        $(this).dialog('close');
        $.post('association_delete.php',{aid:id},function(res){eval(res);});
      },
      "Cancel": function(){$(this).dialog('close')}
    }
  });
}
function association_extension_change(id){
	var newext=$("#association_extension_"+id).val();
	$.post('association_extension_change.php',{aid:id,extension:newext},function(res){eval(res);})
}
function rand (n){
	  return (Math.floor(Math.random()*n+1));
}
</script>
<div>There are <?php echo count($kfm->plugins); ?> plugins available</div>
<div id="new_association_blueprint" title="New file association">
Extension(s): <input id="new_association_extension">
<?php echo get_plugin_list('no_default', '0222'); ?>
</div>
<div id="associations_container" class="ui-widget ui-widget-content">
<table id="association_table">
<thead>
	<tr>
		<th class="ui-widget-header">Extension(s)</th>
		<th class="ui-widget-header">Plugin</th>
		<th></th>
	</tr>
</thead>
<tbody>
<?php
foreach($extensions as $ext){
	echo get_association_row($ext['extension'],$ext['plugin'],$ext['id']);
}
?>
</tbody>
</table>
<br/>
<span class="ui-state-default ui-corner-all button" onclick="new_association()">New association</span>
</div>
