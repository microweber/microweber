<?
//p($params);

$rand = 'cf_vals_'.rand();

 

if($params['params_default']){
	
//$d = 	
}
 
?>
<script type="text/javascript"> 
  function make_cf_append_new(){
	  	$str_to_append1="<li><span></span><input type='text' value='Type here' /></li>";
		$('#cf_render_<? print $rand; ?> ul').append($str_to_append1);
		mw_cf_edit_apply_sorted_positions<? print $rand; ?>()
  }
 
 function make_cf_edit<? print $rand; ?>(){
	 $('#cf_render_<? print $rand; ?>').html('');
	  $t = $('#param_values_<? print $rand; ?>').val();
	  if(1==1){
		var mySplitResult = $t.split(",");
			
		var	$str_to_append='<ul class="cf_values_reorder">'; 
			for(i = 0; i < mySplitResult.length; i++){
				 if(mySplitResult[i] != undefined && mySplitResult[i] != null && mySplitResult[i]!=''){
				$str_to_append=$str_to_append+ "<li><span></span><input type='text' value='"+mySplitResult[i]+"' /><b></b></li>";
				 }
				//$('#cf_render_<? print $rand; ?>').append("<br /> Element " + i + " = " + mySplitResult[i]); 
			}
			
			
			
			
			
			
			
			
			//$str_to_append=$str_to_append+ "<li><span></span><input type='text' value='add new' /></li>";
			//$str_to_append=$str_to_append+ '</ul>'; 
			$str_to_append=$str_to_append+ '</ul><a class="xbtn" href="javascript:make_cf_append_new()" ><img height="16" class="cf_form_type_selector_arrow" width="16" src="<?php   print( ADMIN_STATIC_FILES_URL);  ?>img/silk/pencil--plus.png" />Add new</a>'; 
			$('#cf_render_<? print $rand; ?>').html($str_to_append); 
			
			
			
			
			
			
			
			
			
			
			$('#cf_render_<? print $rand; ?> ul li').hover(
  function () {
	  $(this).find("span:first").css("visibility","visible");
	  $(this).find("b").css("visibility","visible");
 
    //$(this).append($("<span> ***</span>"));
  }, 
  function () {
	    $(this).find("span:first").css("visibility","hidden");
		 $(this).find("b").css("visibility","hidden");
    //$(this).find("span:last").remove();
  }
  
  
  
  
  
  
  
);
			
			
			
								$('#cf_render_<? print $rand; ?> ul li b').click(
  function () {
	  
	  
	  

   
   var raaa=confirm("Delete value?");
if (raaa==true)
  {
   $(this).parent().remove();
   mw_cf_edit_apply_sorted_positions<? print $rand; ?>()
  }
else
  {
 
  }
 
   
   
  } 
);

			
			
			
			
					$('#cf_render_<? print $rand; ?> ul li input').change(
  function () {
  mw_cf_edit_apply_sorted_positions<? print $rand; ?>()
  } 
);

			
			
			$('#cf_render_<? print $rand; ?> ul').sortable( "destroy" );
			
			 $('#cf_render_<? print $rand; ?> ul').sortable({
					 //handle: 'img',
					  stop:function(e,ui) {
							// order = $('#cf_render_<? print $rand; ?>').sortable("toArray");
							 
							//order_j =  order.join(","); 
							
					  mw_cf_edit_apply_sorted_positions<? print $rand; ?>()
						} 

														 
														 
	  });
	  }
	  
	
	  
	  
 }
 
 

function mw_cf_edit_apply_sorted_positions<? print $rand; ?>(){
	 $('#param_values_<? print $rand; ?>').val( $("#cf_render_<? print $rand; ?> ul li input").map(function(){
      							return $(this).val();
    							}).get().join(",") );


$fst = $("#cf_render_<? print $rand; ?> ul li input:first").val();
$('#params_default_<? print $rand; ?>').val($fst);


						   //$('#param_values_<? print $rand; ?>').val(order);
						   make_cf_edit<? print $rand; ?>();
	
}
 
 
 $(document).ready(function() {
							 
			 make_cf_edit<? print $rand; ?>()				 
	 
		
			 
  });
</script>

<div id="cf_render_<? print $rand; ?>"></div>
<input name="param_values" onblur="make_cf_edit<? print $rand; ?>()" class="cf_form_<? print $params['id'] ?>" id="param_values_<? print $rand; ?>"  type="hidden" value="<? print $params['param_values'] ?>"  />
<input name="params_default" class="cf_form_<? print $params['id'] ?>"  id="params_default_<? print $rand; ?>"  type="hidden" value="<? print $params['params_default'] ?>"  />
