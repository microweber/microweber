<? $dashboard_user = user_id_from_url(); ?>
 
<script type="text/javascript">
function add_edit_vid($id, $cat){

 mw.module({
		   module : 'posts/add' ,
		   display_2 : 'add_video' ,
		   post_id : $id,
		   title: $id==0?"Add your video":"Edit your video",
		   category: $cat,
		   
		   redirect_on_success : "<? print site_url('dashboard/action:my-videos'); ?>/cat:"+$cat,
		  /* callback : function(){
			refresh_questions();   
			},*/
		   title_label : "Video title:",
		   submit_btn_text : "Add your video",
		   body_label:"Some description: "
		   },'#post_dash');


 $('#post_dash').show();
 $(".video_type").hide();

}



function show_videos($cat){

 mw.module({
		   
				
		   module : 'posts/list' ,
		   display : 'my_videos' ,
 created_by : '<? print $dashboard_user; ?>',
  limit : '100',
    no_results_text : 'No videos',
		   
		   category: $cat,
		   
		  
		   },'#v_list');


 //$('#post_dash').show();
 //$(".video_type").hide();

}

$(document).ready(function() {
<? if(url_param('cat')): ?>
show_videos('<? print url_param('cat'); ?>');
 <? else : ?>
show_videos('8,39');

 <? endif; ?>
});
</script>
 

<div id="wall">

<? if(url_param('id')):
$post_data = get_post(url_param('id'));
?>



 <h2><? print $post_data['content_title']; ?></h2>
 <br />
<? print html_entity_decode($post_data['content_body']); ?>


<div class="my__embed">
 <? print html_entity_decode( $post_data['custom_fields']['embed_code']); ?>

</div>





<div><span class="st_sharethis" st_url="<? print post_link($post_data["id"]); ?>" st_title="<? print addslashes($post_data["content_title"]); ?>" displayText="Share this"></span>

 <a href="#" class="user_activity_comments"><strong><? print comments_count($post_data['id'], false); ?></strong><span></span><strong>Comments</strong></a> <a  class="user_activity_likes right"  href="<? print voting_link($post_data['id'], '#post-likes-'.$post_data['id']); ?>"><strong id="post-likes-<? print ($post_data['id']); ?>"><? print votes_count($post_data['id'], false ); ?></strong> Like</a> </div>
  </div>
  <? $update_element = md5(serialize($post_data));
  $this->template ['comments_update_element'] = $update_element;
	$this->load->vars ( $this->template );
  ?>
  <? comment_post_form($post_data['id'],'dashboard/index_item_comments.php')  ?>
  <div id="<? print $update_element ?>">
    <? comments_list($post_data['id'], 'dashboard/index_item_comments_list.php')  ?>
  </div>
  
  
  

 <? else : ?>
 
 
 
 
 
  <? if($dashboard_user == user_id()) : ?>
  <a href="#" onclick="$('.video_type').slideDown()" class="mw_btn_s right" ><span>Add new video</span></a>
  <h2>My videos</h2>
  <? else: ?>
  <h2><? print user_name($dashboard_user); ?>'s videos</h2>
  <? endif; ?>
  <br />
  <div class="video_type" style="display: none"> <a href="#" onclick="add_edit_vid(0, 39)">Post to my videos</a> <a href="#" onclick="add_edit_vid(0, 8)">Post to Skid-e-Tube</a> </div>
  <div class="c" style="padding-bottom: 15px;">&nbsp;</div>
  <div id="post_dash" style="display: none"> </div>
  <br />
  <div class="whatis_tabs whatis_tabs_act">
    <ul class="tabnav">
      <li class="active"><a href="javascript: show_videos('39');">My videos</a></li>
      <li><a href="javascript: show_videos('8');">My Skid-e-Tube videos</a></li>
    </ul>
  </div>
  <div class="post_list" id="v_list"> </div>
  
   <? endif; ?>
  
  
</div>
