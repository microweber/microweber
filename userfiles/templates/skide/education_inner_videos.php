
<div class="inner_video">
  <h2><? print $post["content_title"]; ?></h2>
  <div class="hr">&nbsp;</div>
  <div class="bluebox">
    <div class="bluebox_content"> <? print html_entity_decode($post['custom_fields']['embed_code']); ?> </div>
  </div>
  <br />
  
  
  
  
  
  
  <a href="<? print site_url('dashboard/action:my-videos'); ?>" class="mw_btn_x right"><span>Add new video</span></a>
  <div class="user_activity_bar video_activity_bar">
    
    
   <a  class="user_activity_likes right"  href="<? print voting_link($post['id'], '#post-likes-'.$post['id']); ?>"><strong id="post-likes-<? print ($post['id']); ?>"><? print votes_count($post['id']); ?></strong> Like</a> 
  <br />
  <h2 style="padding-bottom: 10px;">Who like this video?</h2>
  <div class="hr">&nbsp;</div>
  <ul class="user_friends_list user_friends_list_horizontal" id="voted-users-for-<? print ($post['id']); ?>">




  </ul>
  <div class="c">&nbsp;</div>
    
    
     <? //mw_var($post['id']); ?>
    <script type="text/javascript">



   $(document).ready(function() {
   refresh_voted_users()
});

   mw.ajaxEvent("onAfterVote", function(){

		refresh_voted_users()

});






function refresh_voted_users(){
	$.ajax({
  url: '{SITE_URL}api/module',
   type: "POST",
      data: ({module : 'posts/voted_users' ,post_id : '<? print ($post['id']); ?>' }),
     // dataType: "html",
      async:false,

  success: function(resp) {
	 // alert(resp);
   $('#voted-users-for-<? print ($post['id']); ?>').html(resp);
   // alert('Load was performed.');
  }
	});
}
</script>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
  </div>
  
  
  
  <div class="c">&nbsp;</div>
</div>
<div class="inner_video_side">
  <h2>More videos</h2>
  <div class="hr">&nbsp;</div>
  <div class="bluebox">
    <div class="bluebox_content">
      <?
 
  $params= array();
$params['display']= 'post_item_video_sidebar.php';
 
$params['items_per_page'] = 3;
$params['category'] = $active_categories[0];
$params['curent_page'] = rand(1,5);
 get_posts($params);

   
  ?>
      <a href="<? print category_link($active_categories[0]); ?>" class="more">See more videos</a> </div>
  </div>
</div>
<div class="c">&nbsp;</div>
<br />
  <? include(TEMPLATE_DIR.'banner_wide.php')	; ?>  <br />  
<br />
<div class="c">&nbsp;</div>
<h2>Comments</h2>
<? comments_list($post['id'])  ?>
<br />
<div class="c">&nbsp;</div>
<br />
<h2 class="coment-title">Post your comment</h2>
<? comment_post_form($post['id'])  ?>
