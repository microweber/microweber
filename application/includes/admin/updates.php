<?

 $rand = uniqid(); ?>
<script  type="text/javascript">




$(document).ready(function(){
	 	window.addEventListener('message', receiveMessage, false);

function receiveMessage(evt)
{
	
	
	//evt.data
   
   $.post('<? print site_url('api/mw_install_new_from_remote') ?>', { data: evt.data, time: "2pm" } , function(data) {
				 mw.log(data);
			  
			}); 
   
   
   
   
    
   
}
 

});





function mw_reload_all_updates(){

	$('#modules_admin_<? print $rand  ?>').attr('reload_modules',1);
		 
		 
  	 mw.load_module('admin/updates','#modules_admin_<? print $rand  ?>');
	
}


 


 
</script>
<button onclick="mw_reload_all_updates()">mw_reload_all_updates</button>




<table width=" 100%" border="1">
  <tr>
    
    <td><module type="admin/updates" id="modules_admin_<? print $rand  ?>"   /></td>
  </tr>
</table>

 <iframe height="600" width="500" src="<? print site_url('bootstrap/modules/no_editmode:true') ?>" ></iframe>
 