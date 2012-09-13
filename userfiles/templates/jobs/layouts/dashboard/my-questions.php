<? $dashboard_user = user_id_from_url(); ?>

 <? if($dashboard_user == user_id()) : ?>
<? $questions = get_page('questions'); ?>
<script type="text/javascript">
function add_edit_question($id){
 
 mw.module({
		   module : 'posts/add' ,
		   //display : 'add_questions' ,
		   post_id : $id, 
		   title: "Post your question",
		   category: "questions",
		   redirect_on_success : "<? print site_url('dashboard/action:my-questions'); ?>",
		  /* callback : function(){
			refresh_questions();   
			},*/
		   title_label : "Question title:",
		   submit_btn_text : "Add your question",
		   body_label:"Question: "
		   },'#post_dash');


 $('#post_dash').show();

}



 
</script>
   <? endif; ?>

<div id="wall"> 
 <? if($dashboard_user == user_id()) : ?>
<a href="javascript:add_edit_question(0);" class="mw_btn_s right"><span>Add new question</span></a>
  <h2>My questions</h2>
   <? else: ?>
   
       <h2><? print user_name($dashboard_user); ?>'s questions</h2>
   
   <? endif; ?>
  
  <br />
  <div id="post_dash" style="display: none"> </div>
  <div class="c">&nbsp;</div>
  <br />
  <div class="" id="q_list">

   <? if($dashboard_user == user_id()) : ?>
  <microweber module="posts/list" category="<? print intval($questions['subtype_value']) ?>" display="questions_and_answers_admin" created_by="<? print $dashboard_user; ?>" no_results_text="I don't have any questions yet."  limit="500"   ></microweber>
  <? else: ?> 
   <microweber module="posts/list" category="<? print intval($questions['subtype_value']) ?>" display="questions_and_answers" created_by="<? print $dashboard_user; ?>" no_results_text="<? print user_name($dashboard_user); ?> haven't asked any questions yet."  limit="500"   ></microweber>
  
  
   <? endif; ?>
  
  
  

   
  </div>
</div>
