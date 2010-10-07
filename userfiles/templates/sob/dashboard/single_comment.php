<?php $thumb = $this->users_model->getUserThumbnail( $comment['created_by'], 50); ?>
<?php $author = $this->users_model->getUserById($comment['created_by']); ?>

<div class="status-comment">
	<a href="#" class="img" style="background-image: url(<?php print $thumb; ?>)"></a>
    <strong><a href="<?php print site_url('userbase/action:profile/username:'.$author['username']) ?>"><?php print $author['first_name'].' '.$author['last_name']; ?></a></strong>
    <?php print $comment['comment_body']; ?>
</div>