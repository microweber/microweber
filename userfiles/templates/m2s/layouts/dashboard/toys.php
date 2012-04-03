
<? $dashboard_user = user_id_from_url(); ?>

 <? if($dashboard_user == user_id()) : ?>
<script type="text/javascript">
function add_edit_toy($id){
 
 mw.module({
		   module : 'posts/add' ,
		   //display : 'add_questions' ,
		   post_id : $id, 
		   title: "Post your toy",
		   category: "6",
		   redirect_on_success : "<? print site_url('dashboard/action:toys'); ?>",
		   display_1 : "add_pictures",
		   display_2 : "add_pricing",

	 
		  /* callback : function(){
			refresh_questions();   
			},*/
		   title_label : "Title:",
		   submit_btn_text : "Save your toy",
		   body_label:"Description: "
		   },'#post_dash');
 
 
 $('#post_dash').show();

}



 
</script>


   <? endif; ?>





<div id="wall"> 

 <? if($dashboard_user == user_id()) : ?>
<a href="#" class="mw_btn_s right" onclick="add_edit_toy(0)"><span>Add new toy</span></a>
  <h2>My toys</h2>
   <? else: ?>
   
       <h2><? print user_name($dashboard_user); ?>'s toys for sale</h2>
   
   <? endif; ?>
   
   



  <br />
  <div id="post_dash" style="display: none">
 
  </div>
  <div class="c">&nbsp;</div>
  <br />
 
    <microweber module="posts/list" category="6" limit="500" display="posts_list_toys"  created_by="<? print $dashboard_user;  ?>" no_results_text="<? print user_name($dashboard_user); ?> doesn't have any toys for sale"></microweber>

</div>
