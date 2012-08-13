<h2>Are you sure you want to delete this page?</h2>


<script type="text/javascript">
var del_page_confirm = function(){
	data123 = $('#del_page_confirm_form').serialize();
	$.post("<? print site_url('api/content/delete') ?>",  data123 ,	function(data1){ 
																		
																//$('#del_pa').html(data1)	;	
																		alert(data1);
										//$('#'+$form_id).fadeOut().fadeIn();											 
																					 });
}
</script>
<div id="del_pa"></div>
<form id="del_page_confirm_form"  action="" method="post">

<input name="id" type="text" value="<? print $params['id'] ?>">
 <input name="yes" type="button" onClick="del_page_confirm()" value="yes">
</form>