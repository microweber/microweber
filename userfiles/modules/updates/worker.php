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
     
      success:  function (resp) {
  		 // $('#mw-update-res-log').html(resp); 
		   $('#mw-update-res-log').append(resp); 
		  if(resp == 'done'){
			mw.tools.enable(mwd.getElementById('installsubmit'));
            Alert("Updates are successfully installed.");
			$('#number_of_updates').fadeOut();
				mw.reload_module('#mw-updates', function(){
				mw.bind_update_btns();
			});
		  } else {
			   setTimeout(mw_apply_upd, 5000);
		  }
		  
//		  $('#mw-update-res-log').append(resp); 
		 
		  
		  
		}
   });
   
   	
}

</script>
 


<pre id="mw-update-res-log" style="word-wrap: break-word;"></pre>