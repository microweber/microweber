<?
 p($params);
 
$rand = rand();

  
?>
 
 
 <button onclick="make_new_field()" value="make_new_field()">make_new_field()</button>
<div  class="custom-fields-form-wrap custom-fields-form-wrap-<? print $rand ?>" id="custom-fields-form-wrap-<? print $rand ?>">
   
  <? 
  
if(is_file($cf_files )){
include($cf_files);
}
	 ?>
     
     
     
     
</div>






<script type="text/javascript">


function make_new_field(){
					$('#custom-fields-form-wrap-<? print $rand ?>').load('<? print site_url('api/forms/make_field/settings:y/for_module_id:') ?><? print $params['for_module_id']; ?>');

	
}

			$(document).ready(function(){
				
				
			make_new_field()
		 
			});
</script>
