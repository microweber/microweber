<?php dbg(__FILE__); ?>
<?php //  p($author); ?>
<?php if(empty($author)): ?>
<?php $author = $this->core_model->getParamFromURL ( 'username' ); 
 $author = $this->users_model->getIdByUsername($author);
 
 ?>
<?php $author = $this->users_model->getUserById($author); ?>
<?php endif; ?>

<div class="wrap" style="padding: 0 0 20px 20px">
  <?php $thumb = $this->users_model->getUserThumbnail( $author['id'], 205); ?>
  <?php if($thumb != ''): ?>
  <a href="<?php print site_url('userbase/action:profile/username:'.$author['username']); ?>" class="profile-image">
  <img src="<?php print $thumb; ?>" alt="" />
  <span class="level level-1">&nbsp;</span>

  </a>
  <?php endif; ?>
  <div class="profile-about-me">
    <div style="width: 100px;" class="box-ico-holder right">
      <?php $to_user = $author['id'];
	   include (ACTIVE_TEMPLATE_DIR.'dashboard/profile_small_controlls.php') ?>
    </div>
    <div class="c">&nbsp;</div>
    <h2>About me</h2>
    <?php if($extended_user_profile == true): ?>
   <?php print (character_limiter(strip_tags( html_entity_decode($author['user_information'])), 300, '...')); ?>
    <?php else : ?>

    
    <?php if(trim($author['user_information']) != ''): ?>
    <?php print (character_limiter(strip_tags( html_entity_decode($author['user_information'])), 300, '...')); ?> <a href="<?php print site_url('userbase/action:about/username:'); ?><?php print $author['username']; ?>" class="btn right">Read more</a>
    <?php else : ?>
   Sorry, I don't have any info about me yet :(
   <?php endif; ?>
   
    <?php endif; ?>
  </div>
  <?php // print $author['id']; ?>
</div>
<div class="c border-bottom" style="padding-bottom: 5px;">&nbsp;</div>
<?php if($short_profile = false ): ?>
<?php endif; ?>


<?php dbg(__FILE__, 1); ?>