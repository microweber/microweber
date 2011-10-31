<script type="text/javascript">
$(document).ready(function(){
    $(".richtext .user_activity_comments").click(function(){
        $("html, body").animate({scrollTop: $("#post_comments_list").offset().top - 40});
        return false;
    });
});
</script>

<h2 class="nomargin"><? print $post["content_title"]; ?></h2>
<div class="richtext"> <? print $post["the_content_body"]; ?> <br />
  <div class="user_activity_bar">
    <div> 
    
              <span class="st_sharethis" st_url="<? print post_link($post["id"]); ?>" st_title="<? print addslashes($post["content_title"]); ?>" displayText="Share this"></span>
    
      <div id="share_<? print $post['id'] ?>" class="xhidden"> <a target="_blank" class="share_facebook" href="http://www.facebook.com/sharer.php?u=<? print post_link($post['id']); ?>&t=<? print $post["content_title"]; ?>">Facebook</a> <a target="_blank" class="share_twitter" href="http://twitter.com/home?status=Currently reading <? print post_link($post['id']); ?>">Twitter</a> </div>
      <a class="user_activity_comments" href="<? print post_link($post['id']); ?>"><strong><? print comments_count($post['id']); ?></strong><span></span><strong>Comments</strong></a> <a class="user_activity_likes" href="<? print voting_link($post['id'], '#post-likes-'.$post['id']); ?>"><strong id="post-likes-<? print ($post['id']); ?>"><? print votes_count($post['id']); ?></strong><span></span><strong >Like</strong></a> </div>
  </div>
</div>
<div class="post_list" align="center">  <? include(TEMPLATE_DIR.'banner_wide.php')	; ?> </div>
<br />
<br />
<microweber module="comments/default" post_id="<? print intval($post['id']) ?>" form_title='Reply to this question' list_title="Other people's answers"></microweber>
