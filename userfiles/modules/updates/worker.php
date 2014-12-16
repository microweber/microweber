<?php 

only_admin_access();
?>

<script>


$(document).ready(function (){
    mw_apply_upd();
});

function mw_apply_upd(){
 $.ajax({                                      
      url: '<?php print api_link(); ?>mw_apply_updates_queue',        
      type: "post",          
      dataType: 'html', 
	   error: function (request, status, error) {
		  setTimeout(mw_apply_upd, 5000);
    	},
      success:  function (resp) {
		  $('#mw-update-res-log').append(resp); 
		  if(resp == 'done'){
			$(window).trigger('mw_updates_done')  
			
		  } else {
			   setTimeout(mw_apply_upd, 5000);
		  }
	  	  
		}
   });
   	
}

</script>
<pre id="mw-update-res-log" style="word-wrap: break-word;"></pre>