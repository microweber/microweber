<script type="text/javascript">
function users_list(){
   
  
   $.ajax({
  url: '<? print site_url('api/module') ?>',
   type: "POST",
      data: ({module : 'users/friends' ,
			// user_id : $user_id, 
			 wrap_element : 'div',
			 wrap_element_items : 'div',
			 wrap_element_class : 'field friend_request',
			 wrap_element_items_class : 'fieldcontent',
			 show_results_count_title : true,
			

			 show_user_controls: true }),
     // dataType: "html",
      async:true,

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



});

</script> 
<h2>My friends</h2>
<div id="users_list_ajax" class="users_list_ajax"></div>
