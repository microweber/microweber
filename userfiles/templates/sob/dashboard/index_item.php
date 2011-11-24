<?php // p($log); ?>
<?php $author = $this->users_model->getUserById($log['user_id']); ?>
		<?php $thumb = $this->users_model->getUserThumbnail( $log['user_id'], 45); ?>
  	<?php $data = $this->core_model->getById($log['to_table'],$log['to_table_id']); ?>	
 <?php
 $addtinal_style = '';
  if(($log['to_table'] == 'table_votes') and ($data['to_table'] == 'table_followers')){
  $addtinal_style = "display:none;";
 $this->users_model->logDeleteById($log['id']);
  }
  
   if(($log['to_table'] == 'table_votes') and ($data['to_table'] == 'table_comments')){
  $addtinal_style = "display:none;";
 $this->users_model->logDeleteById($log['id']);
  }
  
   if(($log['to_table'] == 'table_votes') and ($data['to_table'] == 'table_users_statuses')){
  $addtinal_style = "display:none;";
 $this->users_model->logDeleteById($log['id']);
  }
  
  if(($log['to_table'] == 'table_votes') and ($data['to_table'] == 'table_users')){
  $addtinal_style = "display:none;";
 $this->users_model->logDeleteById($log['id']);
  }
  
   if(($log['to_table'] == 'table_votes') and ($data['to_table'] == 'table_votes')){
  $addtinal_style = "display:none;";
 $this->users_model->logDeleteById($log['id']);
  }
  
  
  
  
  
   if(($log['to_table'] == 'table_options')){
	$addtinal_style = "display:none;";
 $this->users_model->logDeleteById($log['id']);   
   }
 ?>
<div class="users-status" style="<?php print $addtinal_style ?>">
	      <a href="<?php print site_url('userbase/action:profile/username:'.$log['username']) ?>" class="img" style="background-image: url('<?php print $thumb; ?>')"></a>
	      <div class="users-status-content">
	          <strong style="padding-right: 0">
              
              
              <?php if($log['user_id'] != $this->core_model->userId()): ?>
	          	<a href="<?php print site_url('userbase/action:profile/username:'.$author['username']) ?>">

               
	    		<?php if ($author['first_name'] && $author['last_name']) : ?>
	    			<?php print $author['first_name'] . ' ' . $author['last_name']; ?><?php else : ?><?php print $author['username'];?>
                <?php endif;?></a>
                <?php else: ?>
                You
                <?php endif;?>
	          </strong>
	          <span class="user-posted-status">
              
              
              <?php switch($log['to_table']): ?>
<?php case 'table_users_statuses': ?>
 <?php $statusRow = $this->users_model->statusGetById ($log['to_table_id']);   ?>
updated status to  <?php print  html_entity_decode(($statusRow['status'] )); ?>
<?php break;?>
<?php case 'table_users': ?>
updated profile
<?php break;?>
<?php case 'table_messages': ?>
send a message to <?php print $log['to_table_id'] ?>.. must be implemented
<?php break;?>

<?php case 'table_comments': ?>
<?php $content_data = $this->content_model->contentGetByIdAndCache ( $log ['to_table_id'] );
$url = $this->content_model->getContentURLByIdAndCache($log ['to_table_id']);
 ?>
commented on <a href="<?php print $url; ?>"><?php print( $content_data['content_title']); ?>  </a>
<?php break;?>




<?php case 'table_votes': ?>

 

 <?php if($data['to_table'] == 'table_content') : ?>
 <?php $content_data = $this->content_model->contentGetByIdAndCache ( $data ['to_table_id'] );
$url = $this->content_model->getContentURLByIdAndCache($data ['to_table_id']);
 
  ?>
liked <a href="<?php print $url; ?>"><?php print( $content_data['content_title']); ?></a>
<?php elseif($data['to_table'] == 'table_followers') : ?>
 <?php // p($data); ?>
  <?php else: ?>

  <?php p($log); ?>
                <?php $data = $this->core_model->getById($log['to_table'],$log['to_table_id']);
				 p($data); ?>
                <?php endif;?>
                
                .

<?php break;?>



<?php case 'table_content': ?>
<?php $data = $this->core_model->getById($log['to_table'],$log['to_table_id']);
$content_data = $this->content_model->contentGetByIdAndCache ( $data ['id'] );
$url = $this->content_model->getContentURLByIdAndCache($data ['id']);
 ?>
 <?php // p($data); ?>
posted <a href="<?php print $url; ?>"><?php print( $content_data['content_title']); ?>  </a>
<?php break;?>




<?php case 'table_followers': ?>
<?php $data = $this->core_model->getById($log['to_table'],$log['to_table_id']);
 $data2 = $this->users_model->getUserById( $data['follower_id'] );
  ?>
<?php if(!empty($data)) : ?>
 <?php if($log['is_special'] == 'y') : ?>
added to the circle on influence 
<?php else : ?>

<?php if($log['user_id'] != $this->core_model->userId()): ?>
is following
<?php else: ?>
are following
<?php endif; ?>


<?php endif;?>
<a href="<?php print site_url('userbase/action:profile/username:'.$data2['username']) ?>"><?php print $this->users_model->getPrintableName( $data['follower_id']) ?></a>
<?php else : ?>
<?php $this->users_model->logDeleteById($log['id']); ?>
<?php endif;?>

<?php break;?>
<?php default: ?>
 Updated <?php print $log['to_table'] ?> id   <?php print $log['to_table_id'] ?>
<?php break;?>
<?php endswitch;?>
              
              
              
              
	          
	          </span>
	          <a href="#" class="more">See More</a>
	          <div class="status-nav">
	              <ul>
	                <li><span class="date"><?php echo date(DATETIME_FORMAT, strtotime($log['created_on']));?></span></li>
	                <li><a href="#" class="voteUp dashboard-votes-<?php print $log['id']; ?>"><?php print $this->votes_model->votesGetCount($log['to_table'], $log['to_table_id'], '1 year'); ?></a></li>
                      <li><a href="javascript:mw.content.Vote('<?php print $this->core_model->securityEncryptString($log['to_table']) ?>', '<?php print $this->core_model->securityEncryptString($log['to_table_id']) ?>', '.dashboard-votes-<?php print $log['id'] ?>');" class="like">like</a></li>
                    
                    
                    
	                <li><a href="javascript:mw.comments.getComments('<?php print $log['to_table'] ?>', '<?php print $log['to_table_id'] ?>', '#comments-for-dashboard-ajax-<?php print $log['id'] ?>');"   class="cmm" id="status-comments-<?php print $log['id'] ?>"><?php print $this->comments_model->commentsGetCount($log['to_table'], $log['to_table_id'], $is_moderated = false); ?></a></li>
	              
	                <li><a href="javascript:mw.comments.getComments('<?php print $log['to_table'] ?>', '<?php print $log['to_table_id'] ?>', '#comments-for-dashboard-ajax-<?php print $log['id'] ?>');"   class="add-comment-anchor">Add comment</a></li>
	              </ul>
	          </div>
	      </div>
	      <div class="status-comments" id="users-statuses-comments-list-<?php print $log['id'] ?>">
	          <div class="status-comment add-comment">
              
	              <form action="#" method="post" class="commentForm" onsubmit="setTimeout(function(){mw.comments.getComments('<?php print $log['to_table'] ?>', '<?php print $log['to_table_id'] ?>', '#comments-for-dashboard-ajax-<?php print $log['id'] ?>')}, 1500);">
	              	<input type="hidden" name="related_list" value="users_statuses-comments-list-<?php print $log['id'] ?>">
	              	<input type="hidden" name="to_table" value="<?php print $this->core_model->securityEncryptString($log['to_table']); ?>">
	              	<input type="hidden" name="to_table_id" value="<?php print $this->core_model->securityEncryptString($log['to_table_id']); ?>">
	                <textarea rows="" cols="" name="comment_body">Write a comment...</textarea>
	                <a href="#" class="btn submit">Add comment</a>
	                <input type="submit" value="" class="Xsubmit" />
	              </form>
                  
                  <div class="commentFormSuccess" style="display:none">Your comment is posted.</div>
                  
	          </div>
              
              
              
              
              
              
             
  <div class="c" style="padding-bottom: 20px"></div>
  <?php $comments = array();
$comments ['to_table'] = $log['to_table'];
$comments ['to_table_id'] = $log['to_table_id'];

?>

<script type="text/javascript">
$(document).ready(function(){
	
	
});
</script>
  
             <div id="comments-for-dashboard-ajax-<?php print $log['id'] ?>">
             
             
             
             
             </div> 
              
	      </div>
          
          
          
          
          
          
	  </div>