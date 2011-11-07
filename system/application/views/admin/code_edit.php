<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class="<?php echo css_browser_selector() ?>" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:v='urn:schemas-microsoft-com:vml'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title></title>
<? $url = url_param('url');
$url  = base64_decode($url );
$url = $url.'/editmode:y';
?>
<?  include('header_scripts.php'); ?>


<script type="text/javascript">
function content_json($location) {
	 
	
}	
	
	
function mw_edit_init($location){
	
	
	//$loc = $("#edit_frame").attr('src');
	//alert($location);
	//$data = content_json($location);
	$.ajax({
  		  type: 'POST',
  		  url: $location,
  		  data: { format: 'json'},
           async:true,
           dataType: "json", 
  		  success: function(r) {
    		//content_json.page = data;
			//alert(r);
			
			
			$.ajax({
  url: '<? print site_url('api/module'); ?>',
   type: "POST",
      data: ({module : 'admin/pages/edit' ,id : r.page.id }),
     // dataType: "html",
      async:true,
      
  success: function(resp) {
   	 
	// $("#page_module_holder").html(resp);
	 
	 
	 
  }
    });
			
			
			
  		  }
  		})
	
	
	
	
	
	
	//alert($data);
	//alert($data.page.id);
	
}


</script>

<script>
	$(function() {
		$( "#tabs" ).tabs({
			collapsible: true
		});
	});
	</script>

</head>
<body>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Page</a></li>
		<li><a href="#tabs-2">Settings</a></li>
		<li><a href="#tabs-3">Aenean lacinia</a></li>
	</ul>
	<div id="tabs-1">
    <table width="100%" border="0">
  <tr>
    <td><iframe name="edit_frame" scrolling="auto" src="<? print $url; ?>" height="1000" frameborder="0" width="1000" id="edit_frame" ></iframe></td>
    <td valign="top">
    <mw module="admin/content/modules" />



    
    </td>
  </tr>
</table>

		
	</div>
	<div id="tabs-2">
		<div id="page_module_holder"></div>
	</div>
	<div id="tabs-3">
		<p><strong>Click this tab again to close the content pane.</strong></p>
		<p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
	</div>
</div>



 



<script>
$(document).ready(function() {
 

});
</script>
</body>
</html>
