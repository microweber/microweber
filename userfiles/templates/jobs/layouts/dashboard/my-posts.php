<? $dashboard_user = user_id_from_url(); ?>
<?   $cat_base = (!$base_category) ? false : $base_category;  ?>
<? if($dashboard_user == user_id()) : ?>




<? 
$cat_base = 'jobs';
 
  ?>


<script type="text/javascript">
function add_edit_post($id){
 
 mw.module({
		   module : 'posts/add' , 
		   //display : 'add' ,
		   post_id : $id, 
		   title: "<? print $a = (!$a_title) ? 'Add new job ad' : $a_title;  ?>",
		   category: "<? print  get_category_id($cat_base); ?>",
		 
custom_fields :    [

{"name": "Location", "type": "text",  "value": ""},

 {"name": "Speciality", "type": "text",  "value": ""},
 
 
 
        {"name": "Sallary range", "type": "text",  "value": ""},
        {"name": "Employer Responsibilities", "type": "textarea", "value": ""},
        {"name": "Employer Benefits", "type": "textarea"},
		
		{"name": "Facility Name", "type": "text"},
		{"name": "Community Description", "type": "textarea"},
		{"name": "Benefits", "type": "textarea"}
		
    ]
 ,
	 
		  /* callback : function(){
			refresh_questions();   
			},*/
		   title_label : "Title:",
		   submit_btn_text : "Save",

redirect_on_success: '<? print url() ?>',

		   body_label:"Content: "
		   },'#post_dash');
 
 
 $('.user_posts_hide_on_edit').hide();
 $('#post_dash').show();

}







function post_after_save(){
	mw.reload_module('posts/list') ;
	 $('.user_posts_hide_on_edit').show();
	  $('#post_dash').hide();
}


 
   
    $('.posts-list').find('a').live('click', function (e) {
    $post_edit_id =   $(this).attr('data-post-id');
if( $post_edit_id  != undefined &&  $post_edit_id != ''){

	add_edit_post($post_edit_id)

 e.stopPropagation();
 e.preventDefault() 
}


    });
 






 
</script>
<? endif; ?>
<div   class=" user_posts_hide_on_edit">
 
<div class="row-fluid">
  <div class="span8"><editable rel="page" field="custom_field_dash_<? print $cat_base ?>">
    <h1>Manage your job ads</h1>
      Here you can manage your job ads </editable></div>
  <div class="span4">

 
  	<a  class="add_new_post" href="javascript:add_edit_post(0)">&nbsp;</a></div>
</div>


 
  
 
</div>
<div id="post_dash" class="contentpanel"> </div>
<div   class="contentpanel user_posts_hide_on_edit">
  <microweber module="posts/list" category="<? print  get_category_id($cat_base); ?>" limit="500"   read_more_link_text="Edit" delete_post_link="Delete" created_by="<? print $dashboard_user;  ?>" no_results_text="<? print user_name($dashboard_user); ?> doesn't have any posts"></microweber>
</div>




