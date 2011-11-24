<?php dbg(__FILE__); ?>
<?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_navigation.php') ?>
<?php $action = $this->core_model->getParamFromURL ( 'action' ); ?>
<?php require (ACTIVE_TEMPLATE_DIR.'users/userbase/profile_sidebar.php') ?>
<?php //p($user_about); ?>

<div class="main" style="padding-top: 20px">
  <?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_inner.php') ?>
  <div class="posts">
    <div class="content-title" style="padding-left:20px;">
      <h2 class="title left"><?php print $action  ?></h2>
    </div>
    <div class="pad2">
      <h3 class="title-contacts border-bottom">Location</h3>
      <ul class="contact-list">
        <?php if(trim($user_about['country']) != ''): ?>
        <li><strong>Country:</strong><?php echo ($user_about['country']);  ?></li>
        <?php endif; ?>
        <?php if(trim($user_about['city']) != ''): ?>
        <li><strong>City:</strong><?php echo ($user_about['city']);  ?></li>
        <?php endif; ?>
        <?php if(trim($user_about['state']) != ''): ?>
        <li><strong>State:</strong><?php echo ($user_about['state']);  ?></li>
        <?php endif; ?>
        <?php if(trim($user_about['addr1']) != ''): ?>
        <li><strong>Address:</strong><?php echo ($user_about['addr1']);  ?></li>
        <?php endif; ?>
        <?php if(trim($user_about['zip']) != ''): ?>
        <li><strong>Zip:</strong><?php echo ($user_about['zip']);  ?></li>
        <?php endif; ?>
        <?php if(trim($user_about['addr2']) != ''): ?>
        <li><strong>Address:</strong><?php echo ($user_about['addr2']);  ?></li>
        <?php endif; ?>
        <?php if(trim($user_about['phone']) != ''): ?>
        <li><strong>Phone:</strong><?php echo ($user_about['phone']);  ?></li>
        <?php endif; ?>
      </ul>
      <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
      <h3 class="title-contacts border-bottom"><?php print $this->users_model->getPrintableName($user_about['id'], $mode = 'first'); ?> on the web</h3>
      <ul class="contact-list">
        <?php if(trim($user_about['user_website']) != ''): ?>
        <li><strong>Website URL:</strong><?php print auto_link($user_about['user_website']); ?></li>
        <?php endif; ?>
        <?php if(trim($user_about['user_blog']) != ''): ?>
        <li><strong>Blog:</strong><?php print auto_link($user_about['user_blog']); ?></li>
        <?php endif; ?>
        <?php if(trim($user_about['email']) != ''): ?>
        <li><strong>Email:</strong><?php echo safe_mailto($user_about['email'], $user_about['email']);  ?></li>
        <?php endif; ?>
        
        
        
        
        
        <?php $more = $this->core_model->getCustomFields('table_users', $user_about['id']); ?>
         <?php if(!empty($more)): ?>
        <?php foreach($more as $k => $v): ?>
         <?php if(trim($v) != '')  : ?>
        <?php if(stristr($k, 'website_') != FALSE)  : ?>
 

 <li><strong>Website:</strong><a href="<?php print (prep_url(trim($v))); ?>"><?php print (trim($v)); ?></a></li>

        
         <?php endif; ?>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
        
        
        
      </ul>
      <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
      <ul class="blinklist">
        <?php if(trim($user_about['chat_skype']) != ''): ?>
        <li><span class="blink blink-skype title-tip" title="Skype"><?php echo ($user_about['chat_skype']);  ?></span></li>
        <?php endif; ?>
        <?php if(trim($user_about['chat_icq']) != ''): ?>
        <li><span class="blink blink-icq title-tip" title="ICQ"><?php echo ($user_about['chat_icq']);  ?></span></li>
        <?php endif; ?>
        <?php if(trim($user_about['social_facebook']) != ''): ?>
        <li><span class="blink blink-facebook title-tip" title="facebook"><?php echo ($user_about['social_facebook']);  ?></span></li>
        <?php endif; ?>
        <?php if(trim($user_about['social_linkedin']) != ''): ?>
        <li><span class="blink blink-linkedin title-tip" title="Linked in"><?php echo ($user_about['social_linkedin']);  ?></span></li>
        <?php endif; ?>
        <?php if(trim($user_about['social_youtube']) != ''): ?>
        <li><span class="blink blink-youtube title-tip" title="Youtube"><?php echo ($user_about['social_youtube']);  ?></span></li>
        <?php endif; ?>
        <?php if(trim($user_about['chat_googletalk']) != ''): ?>
        <li><span class="blink blink-gtalk title-tip" title="Gtalk"><?php echo ($user_about['chat_googletalk']);  ?></span></li>
        <?php endif; ?>
        <?php if(trim($user_about['chat_msn']) != ''): ?>
        <li><span class="blink blink-msn title-tip" title="msn"><?php echo ($user_about['chat_msn']);  ?></span></li>
        <?php endif; ?>
        <?php if(trim($user_about['social_myspace']) != ''): ?>
        <li><span class="blink blink-myspace title-tip" title="myspace"><?php echo ($user_about['social_myspace']);  ?></span></li>
        <?php endif; ?>
        <?php if(trim($user_about['social_twitter']) != ''): ?>
        <li><span class="blink blink-twitter title-tip" title="Twitter"><?php echo ($user_about['social_twitter']);  ?></span></li>
        <?php endif; ?>
        
        <!--  <li><span class="blink blink-skype title-tip" title="Skype">My.skype</span></li>
        <li><span class="blink blink-icq title-tip" title="ICQ">My.icq</span></li>
        <li><span class="blink blink-facebook title-tip" title="Facebook">Facebook</span></li>
        <li><span class="blink blink-linkedin title-tip" title="Linkedin">My.linkedin</span></li>
        <li><span class="blink blink-youtube title-tip" title="Youtube">My.Youtube</span></li>
        <li><span class="blink blink-gtalk title-tip" title="Google Talk">My.gtalk</span></li>
        <li><span class="blink blink-msn title-tip" title="MSN">My.MSN</span></li>
        <li><span class="blink blink-myspace title-tip" title="Myspace">My.myspace</span></li>
        <li><span class="blink blink-twitter title-tip" title="Twitter">My.Twitter</span></li>-->
      </ul>
    </div>
    <?php //print __FILE__ ?>
  </div>
</div>
<?php dbg(__FILE__, 1); ?>