<?

 $rand = uniqid().rand(); ?>
<script  type="text/javascript">




$(document).ready(function(){
	 	window.addEventListener('message', receiveMessage, false);

function receiveMessage(evt)
{
   
    alert("got message: "+evt.data);
   
}
	














mw.$('#modules_admin_categories_<? print $rand  ?> .category_tree a[data-category-id]').live('click',function(e) { 

	$p_id = $(this).parent().attr('data-category-id');
	
	mw.$('#modules_admin_<? print $rand  ?>').attr('data-category', $p_id);
 
 mw.reload_module('#modules_admin_<? print $rand  ?>');
 	 //mw.$('#modules_admin_<? print $rand  ?>').removeAttr('cleanup_db'); 

 //	 alert($p_id);
return false;
 
 
 
 
 });
 






});





function mw_reload_all_modules(){

	mw.$('#modules_admin_<? print $rand  ?>').attr('reload_modules',1);
		 	mw.$('#modules_admin_<? print $rand  ?>').attr('cleanup_db',1);

		 
  	 mw.load_module('admin/modules/elements','#modules_admin_<? print $rand  ?>');
	
}


 


 
</script>
<button onclick="mw_reload_all_modules()">Reload elements</button>




<table width=" 100%" border="1">
  <tr>
    <td><module type="categories/selector" to_table="table_elements" id="modules_admin_categories_<? print $rand  ?>" /></td>
    <td><module type="admin/modules/elements" id="modules_admin_<? print $rand  ?>"    /></td>
  </tr>
</table>

<!--<iframe src="http://192.168.0.3/" ></iframe>
-->
