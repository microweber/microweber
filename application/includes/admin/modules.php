<?

 $rand = uniqid(); ?>
<script  type="text/javascript">




$(document).ready(function(){
	 	window.addEventListener('message', receiveMessage, false);

function receiveMessage(evt)
{
   
    alert("got message: "+evt.data);
   
}
	














$('#modules_admin_categories_<? print $rand  ?> .category_tree a[data-category-id]').live('click',function(e) { 

	$p_id = $(this).parent().attr('data-category-id');
	
	$('#modules_admin_<? print $rand  ?>').attr('data-category', $p_id);
	
 
 mw.reload_module('#modules_admin_<? print $rand  ?>');
 //	 alert($p_id);
return false;
 
 
 
 
 });
 






});





function mw_reload_all_modules(){

	$('#modules_admin_<? print $rand  ?>').attr('reload_modules',1);
		 $('#modules_admin_<? print $rand  ?>').attr('cleanup_db',1);
		 
  	 mw.load_module('admin/modules','#modules_admin_<? print $rand  ?>');
	// $('#modules_admin_<? print $rand  ?>').removeAttr('cleanup_db');
	
}


 


 
</script>
<button onclick="mw_reload_all_modules()">Reload modules</button>




<table width=" 100%" border="1">
  <tr>
    <td><module type="categories" data-for="modules" id="modules_admin_categories_<? print $rand  ?>" /></td>
    <td><module type="admin/modules" id="modules_admin_<? print $rand  ?>"   /></td>
  </tr>
</table>

<!--<iframe src="http://192.168.0.3/" ></iframe>
-->
