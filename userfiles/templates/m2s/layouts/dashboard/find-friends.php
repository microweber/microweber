<script type="text/javascript">
function users_list($kw){
   
   if($kw == undefined){
$kw =  $('#frienfinder').val();
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
			 
			 <? if($show_admin_only): ?>
			 //is_admin : 'y',  
			 <? endif; ?>
			 
			  <? if($kw): ?>
			 keyword : '<? print $kw  ?>',  
			 <? endif; ?>
			 
			 tn_size: 105,

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
  users_list('<? print $kw  ?>');

 
});

</script>
<? //print uri_string(); ?>

<editable rel="page" field="custom_field_text_<? print md5(__FILE__).md5(uri_string()) ?>">
  <h1>Talk with your mentor</h1>
 
  <p>How can a mentor improve your career advancement and your business? Consider engaging in a mentoring relationship. A mentor can guide you, take you under his or her wing, and teach you new skills. Research has shown that mentoring relationships succeed and are satisfying for both parties when both the mentor and mentee take an active role in developing the relationship. Below are 10 tips you can implement to ensure you get what you need out of them.</p>
</editable>
 
 


  <? if($kw == false): ?>
   <h4>Search friends below</h4><br />

<input type="text"   class="frienfinder" id="frienfinder" onchange="users_list();" />

<a href="javascript:users_list();" class="frienfinder_btn">Search</a>
<? endif; ?>




<div id="users_list_ajax" class="users_list_ajax"></div>
