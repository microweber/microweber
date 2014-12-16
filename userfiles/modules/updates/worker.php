<?php 

only_admin_access();
?>

<script>


$(document).ready(function (){
    mw_apply_upd();
});

function mw_apply_upd(){
	
var timeout = 3000;	
	
 $.ajax({                                      
      url: '<?php print api_link(); ?>mw_apply_updates_queue',        
      type: "post",          
      dataType: 'html', 
	   error: function (request, status, error) {
		  $('#mw-update-res-log').append('Composer is working...');  
		  setTimeout(mw_apply_upd, timeout);
    	},
      success:  function (resp) {
		  $('#mw-update-res-log').append(resp); 
		  if(resp == 'done'){
			$(window).trigger('mw_updates_done')  
			
		  } else {
			   setTimeout(mw_apply_upd, timeout);
		  }
	  	  
		}
   });
   	
}

</script>
<pre id="mw-update-res-log" style="word-wrap: break-word;"></pre>