<? $thumb = $this->users_model->getUserThumbnail( $comment['created_by'], 50); ?>
<? $author = $this->users_model->getUserById($comment['created_by']); ?>

<div class="status-comment">
	<a href="#" class="img" style="background-image: url(<? print $thumb; ?>)"></a>
    <strong><a href="<? print site_url('userbase/action:profile/username:'.$author['username']) ?>"><? print $author['first_name'].' '.$author['last_name']; ?></a></strong>
    <? print $comment['comment_body']; ?>
</div>