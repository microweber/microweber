<? $dashboard_user = user_id_from_url(); ?>
<?   $cat_base = (!$base_category) ? false : $base_category;  ?> 
 
<? if($dashboard_user == user_id()) : ?>
<script type="text/javascript">
function add_edit_post($id){
 
 mw.module({
		   module : 'posts/add' , 
		   display : 'add' ,
		   post_id : $id, 
		   title: "<? print $a = (!$a_title) ? 'Add new post' : $a_title;  ?>",
		   category: "<? print  get_category_id($cat_base); ?>",
		   view:"blocks/my_posts_add.php",
		  // redirect_on_success : "<? print site_url('dashboard/action:toys'); ?>",
		  // display_1 : "add_pictures",
		  // display_2 : "add_pricing",

	 
		  /* callback : function(){
			refresh_questions();   
			},*/
		   title_label : "Title:",
		   submit_btn_text : "Save",
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

 
</script>
<? endif; ?><div   class="contentpanel user_posts_hide_on_edit">

  <editable rel="page" field="custom_field_dash_<? print $cat_base ?>">
  <h1>Manage your content</h1>
  <br />

Here you can manage your content 
</editable>


<p>
<a  class="add_new_post" href="javascript:add_edit_post(0)"><? print $a = (!$b2_title) ? 'Add new post' : $b2_title;  ?></a>
</p>
<!--  <a href="javascript:add_new_post()" class="add_new_post">Add new post </a>
-->  
  
<div class="nav">
    <ul class=""><li id=""><a href=""><? print $a = (!$b1_title) ? 'My posts' : $b1_title;  ?></a></li> 
<!--     <li id="" ></li>--> </ul> 
  </div>  </div>
<div id="post_dash" class="contentpanel"> </div>
 
<div   class="contentpanel user_posts_hide_on_edit">
 
  <microweber module="posts/list" category="<? print  get_category_id($cat_base); ?>" limit="500" view="blocks/my_posts_list.php"   created_by="<? print $dashboard_user;  ?>" no_results_text="<? print user_name($dashboard_user); ?> doesn't have any posts"></microweber>
</div>
