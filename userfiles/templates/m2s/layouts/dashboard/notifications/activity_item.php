<?php $thumb = CI::model ( 'users' )->getUserThumbnail($notification['from_user'], 45); ?>
<?php $author = CI::model ( 'users' )->getUserById($notification['from_user']);

//p($notification);
?>
  
<div id="notificationItem-<?php echo $notification['id']; ?>"
	class="<?php echo ($notification['is_read'] == 'n') ? 'messageUnread' : 'messageRead'?> notification new-notification"
	style="margin-bottom: 0px;"
	onmouseover="mw.users.UserNotification.read(<?php echo $notification['id']; ?>);">
    <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>" class="img" style="background-image: url('<?php echo $thumb; ?>');"></a>
    <div class="notification-content" style="width: 500px">
        <h3><a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>"><?php echo $author['first_name'].' '.$author['last_name']?></a></h3>
        <span class="date inline left"><?php echo date(DATETIME_FORMAT, strtotime($notification['created_on']));?></span>

        
        
        <span class="msg">
        
        
        <?php switch($notification['to_table']): ?>
<?php case 'table_comments': ?>
<?php if(($notification['type'] == 'post') or ($notification['type'] == 'page')) : ?>
<?php $content_data = $this->content_model->contentGetByIdAndCache ( $notification ['to_table_id'] );
$url = $this->content_model->getContentURLByIdAndCache($notification ['to_table_id']);
 ?>
commented on your <?php print $notification['type'];  ?>: <a href="<?php print $url; ?> "><?php print( $content_data['content_title']); ?>  </a>
<?php else: ?>
<?php p($notification); ?>
<?php endif; ?>
<?php break;?>


<?php case 'table_votes': ?>
<?php if(($notification['type'] == 'post') or ($notification['type'] == 'page')) : ?>
<?php $content_data = $this->content_model->contentGetByIdAndCache ( $notification ['to_table_id'] );
$url = $this->content_model->getContentURLByIdAndCache($notification ['to_table_id']);
 ?>
voted for <?php print $notification['type'];  ?>: <a href="<?php print $url; ?> "><?php print( $content_data['content_title']); ?>  </a>
<?php else: ?>
<?php p($notification); ?>
<?php endif; ?>
<?php break;?>



<?php case 'table_followers': ?>
<?php if(($notification['type'] == 'special')) : ?>
added you to the circle of influence.
<?php else: ?>
followed you.
<?php endif; ?>

<?php break;?>
<?php default: ?>
		other <small><?php  var_dump( $notification);?> </small>
<?php break;?>
<?php endswitch;?>
		
		

        
        
        
        
        
        
        </span>
        
        
        
        
        
        
        
        
    </div>
    
    
    
    
    <div class="box-ico-holder box-ico-Vcentered" style="width: 130px">
      <?php $to_user = $notification['from_user'];
	   include (ACTIVE_TEMPLATE_DIR.'dashboard/profile_small_controlls.php') ?>
    </div>
    
     <span title="Delete" class="delete_notification" onclick="mw.users.UserNotification.remove('<?php echo $notification['id']; ?>', 'notificationItem-<?php echo $notification['id']?>')">Delete</span>
    
    
</div>