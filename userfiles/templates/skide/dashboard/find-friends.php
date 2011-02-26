


 <script type="text/javascript">
function users_list($kw){
   
   if($kw == false){
	$kw = '';   
   }
   
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: ({module : 'users/list' ,
			// user_id : $user_id, 
			 wrap_element : 'div', 
			 wrap_element_items : 'div',  
			 wrap_element_class : 'field friend_request',  
			 wrap_element_items_class : 'fieldcontent',  
			 show_results_count_title : true,  
			 keyword : $kw,  

			 show_user_controls: true }),
     // dataType: "html",
      async:false,

  success: function(resp) {
     // alert(resp);
   $('#users_list_ajax').html(resp);
    $('#users_list_ajax').fadeIn();
   // alert('Load was performed.');
  }
    });
}

$(document).ready(function() {
  users_list();

  $(".frienfinder").onStopWriting(function(){
       users_list(this.value);
  });

});

</script>
 
 
<div id="wall">

    <div class="bluebox">
      <div class="blueboxcontent">
        <div class="field left">
          <input type="text" style="width: 453px;" class="frienfinder"  />
        </div>
      </div>
    </div>

  <br />
 
  <br />
  <div class="bluebox">
    <div class="blueboxcontent">
   
      <div id="users_list_ajax"></div>
      
       </div>
  </div>
</div>
