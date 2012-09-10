<?

 $rand = uniqid(); ?>
<script  type="text/javascript">




$(document).ready(function(){
	 	window.addEventListener('message', receiveMessage, false);

function receiveMessage(evt)
{
   
    alert("got message: "+evt.data);
   
}
	


});





function mw_reload_all_modules(){

	$('#modules_admin_<? print $rand  ?>').attr('reload_modules',1);
		 
		 
  	 mw.load_module('admin/modules','#modules_admin_<? print $rand  ?>');
	
}


 


 
</script>
<button onclick="mw_reload_all_modules()">Reload modules</button>

<!--<iframe src="http://192.168.0.3/" ></iframe>
-->
<module type="admin/modules" id="modules_admin_<? print $rand  ?>" />