<?php $rand = uniqid(); ?>
<script  type="text/javascript">


 


 


function mw_select_opt_group<? print $rand  ?>($p_id){
	$('#options_list_<? print $rand  ?>').attr('data-option_group',$p_id);
	//$('#pages_edit_container_<? print $rand  ?>').removeAttr('data-subtype');
	//$('#pages_edit_container_<? print $rand  ?>').removeAttr('data-content-id');
  	 mw.load_module('options/list','#options_list_<? print $rand  ?>');
}
 




</script>
<?  $option_groups = get_option_groups(); ?>

<table width="" border="1">
  <tr>
    <td><div id="option_groups_<? print $rand  ?>">
        <ul>
          <? foreach($option_groups as $item): ?>
          <li><a onclick="javascrip:mw_select_opt_group<? print $rand  ?>('<? print $item ?>')"><? print $item ?></a></li>
          <? endforeach; ?>
        </ul>
      </div></td>
    <td><div id="option_items_<? print $rand  ?>"><microweber module="options/list" data-option_group="website" id="options_list_<? print $rand  ?>"></div></td>
  </tr>
</table>
