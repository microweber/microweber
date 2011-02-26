<? if(!empty($comments)) : ?>

<div id="post_comments_list">
  <? foreach($comments as $item): ?>
  <div class="post_comment" id="comment-list-id-<? print $item['id'];?>">
    <? $author = CI::model('users')->getUserById( $item['created_by']); 
	

	?>
    <? $thumb = CI::model('users')->getUserThumbnail( $author['id'], 70); ?>
    <a class="img" href="<? print profile_link($author['id']);?>"> <span style="background-image: url('<?  print $thumb; ?>');"></span> </a>
    <div class="post_comment_text">
    
    <? if(!empty($author)): ?>
      <div class="post_comment_author"><strong><a href="<? print profile_link( $item['created_by']);?>"><? print user_name( $item['created_by']);?> </a></strong> said:</div>
      <? else: ?>
      <div class="post_comment_author"><strong>Anonymous</strong> said:</div>
      <? endif; ?>
      
      <p class=""><? print ($item['comment_body']); ?> </p>
      <? if($item['created_by'] == user_id()): ?>
      
      <a href="javascript:mw.comments.del(<? print $item['id'];?>, '#comment-list-id-<? print $item['id'];?>')" class="comment_delete">delete</a>
      <? endif; ?>
      
    </div>
    <div class="c">&nbsp;</div>
    <div class="user_activity_bar">
      <div><a class="user_activity_likes" href="<? print voting_link($item['id'], '#post-comments-'.$item['id'], 'comments'); ?>"><strong id="post-comments-<? print ($item['id']); ?>"><? print votes_count($item['id'], false, 'comments'); ?></strong><span></span><strong >Like</strong></a></div>
    </div>
  </div>
  <?  endforeach ; ?>
</div>
<? else: ?>
No comments yet. Be the first to comment!
<? endif; ?>
