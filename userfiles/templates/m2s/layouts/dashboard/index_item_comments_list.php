<? if(!empty($comments)) : ?>

<div class="mw_comments_wrapper">
<? foreach($comments as $item): ?>
<? $author = $this->users_model->getUserById( $item['created_by']); ?>
<? $thumb = $this->users_model->getUserThumbnail( $author['id'], 80); ?>

<div class="comment" id="comment-list-id-<? print $item['id'];?>"> <a href="<? print profile_link($author['id']);?>" class="user_photo" style="background-image: url('<?  print $thumb; ?>')"></a> <a href="<? print profile_link($author['id']);?>" class="mw_blue_link"><? print user_name( $author['id']) ?></a> said:<br />
  <? print ($item['comment_body']); ?> 
  
   <? if($item['created_by'] == user_id()): ?>
      
      <a href="javascript:mw.comments.del(<? print $item['id'];?>, '#comment-list-id-<? print $item['id'];?>')" class="comment_delete">delete</a>
      <? endif; ?>
  </div>
<?  endforeach ; ?>
</div>
<? endif; ?>
