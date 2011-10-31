<?
$id = intval( $params['id']);


if($id > 0){
$form_values = get_page($id);
}

$par_from_url = url_param('content_parent');
if(intval($par_from_url) > 0){
	$form_values['content_parent'] = $par_from_url;
	
}

?>
<script type="text/javascript">

$(document).ready(function() {
set_template()

	//call_layout_config_module();
});

function set_template(){

	//mw.reload_module('admin/pages/layout_and_category');
	data1 = {}
						   data1.module = 'admin/'+'pages/layout_and_category';
						   data1.id ='<? print $params['id']; ?>';
						  
						   data1.active_site_template = $('#active_site_template').val();
						// data1.type =  $("#media_type").val();
							
						  $('#layout_and_category_holder').load('<? print site_url('api/module') ?>',data1);
						  
						  
						  
						  
						  
}


function set_template_icon($screenshot){
	
	//$laout = load_layout_config_file(true)
	if($screenshot != undefined &&  $screenshot != ''){
			   $('#layout_screenshot').attr('src', $screenshot);
			  //  $('#layout_screenshot').append( $screenshot);
			   $('#layout_screenshot').show();
			   } else {
				   // $('#layout_screenshot').hide();
				   $('#layout_screenshot').attr('src', '<? print $config["url_to_module"]; ?>no_image.gif');
					
			   }
 
  
}


</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><img id="layout_screenshot" src="<? print $config["url_to_module"]; ?>no_image.gif" width="90"  style="border:#CCC solid thin" /></td>
    <td><label>Template</label>
      <? $layouts = CI::model('template')->templatesList();  ?>
      <? if(!empty($layouts)): ?>
      <select name="active_site_template" id="active_site_template">
        <option onclick="set_template()">Default</option>
        <? foreach($layouts as $layout): ?>
        <? if($layout['screenshot']): ?>
        <!-- <a href="<? print $layout['screenshot'] ?>"> <img src="<? print $layout['screenshot'] ?>" height="100" /></a>-->
        <? endif; ?>
        <option onclick="set_template()"  <? if($form_values['active_site_template'] == $layout['dir_name']): ?>   selected="selected"  <? endif; ?>    value="<? print $layout['dir_name'] ?>"   onmouseover="set_template_icon('<? print $layout['screenshot'] ?>')"     ><? print $layout['name'] ?></option>
        <? print $layout['name'] ?> <? print $layout['description'] ?>
        <? endforeach; ?>
      </select>
      <? endif; ?>
      <div id="layout_and_category_holder"></div>
      <br />
      <br />
      <? $p = get_pages($params);  
  
  
 
  
  ?>
      <div class="formitem">
        <label>Parent page</label>
        <span class="formfield">
        <select name="content_parent" id="content_parent">
          <option  <? if(intval($form_values['content_parent']) == 0) :  ?>  selected="selected" <? endif; ?>   value="0">None</option>
          <? foreach($p as $p1):?>
          <option  <? if(($form_values['content_parent']) == $p1['id']) :  ?>  selected="selected" <? endif; ?>   value="<? print $p1['id'] ?>"><? print page_link($p1['id']); ?></option>
          <? endforeach ?>
        </select>
        </span> </div></td>
  </tr>
</table>
