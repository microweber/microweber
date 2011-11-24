<?php dbg(__FILE__); ?>
<?php if(empty($author)): ?>
<?php $author = $this->core_model->getParamFromURL ( 'username' );
 $author = $this->users_model->getIdByUsername($author); ?>
<?php $author = $this->users_model->getUserById($author); ?>
<?php endif; ?>
<?php $action = $this->core_model->getParamFromURL ( 'action' ); ?>

<div style="width: 955px;margin: 0 auto;padding-top: 14px;">
  <h2 class="name-title" style="padding-bottom: 10px;"><?php print $author['first_name']; ?> <?php print $author['last_name']; ?></h2>
  <ul id="about-nav" style="width: 955px;">
    <li<?php if($user_profile_active == true): ?>   class="active" <?php endif; ?> ><a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>">
      <?php //print $author['first_name']; ?>
      Profile
      <?php //print $author['last_name']; ?>
      </a></li>
    <li <?php if(($action == 'articles') or ($post['content_subtype'] == 'none')): ?>   class="active" <?php endif; ?>><a href="<?php print site_url('userbase/action:articles/username:'); ?><?php print $author['username']; ?>">Articles</a></li>
    <li <?php if(($action == 'trainings')or ($post['content_subtype'] == 'trainings')): ?>   class="active" <?php endif; ?>><a href="<?php print site_url('userbase/action:trainings/username:'); ?><?php print $author['username']; ?>">Learning center</a></li>
    <li <?php if(($action == 'products')or ($post['content_subtype'] == 'products')): ?>   class="active" <?php endif; ?>><a href="<?php print site_url('userbase/action:products/username:'); ?><?php print $author['username']; ?>">Products</a></li>
    <li <?php if(($action == 'services')or ($post['content_subtype'] == 'services')): ?>   class="active" <?php endif; ?>><a href="<?php print site_url('userbase/action:services/username:'); ?><?php print $author['username']; ?>">Services</a></li>
    <li <?php if(($action == 'blog')or ($post['content_subtype'] == 'blog')): ?>   class="active" <?php endif; ?>><a href="<?php print site_url('userbase/action:blog/username:'); ?><?php print $author['username']; ?>"><?php print $author['first_name']; ?>'s Blog</a></li>
    <li <?php if(($action == 'gallery')or ($post['content_subtype'] == 'gallery')): ?>   class="active" <?php endif; ?>><a href="<?php print site_url('userbase/action:gallery/username:'); ?><?php print $author['username']; ?>">Photos</a></li>
    <li <?php if($action == 'about'): ?>   class="active" <?php endif; ?>><a href="<?php print site_url('userbase/action:about/username:'); ?><?php print $author['username']; ?>">About me</a></li>
    <li <?php if($action == 'contacts'): ?>   class="active" <?php endif; ?>><a href="<?php print site_url('userbase/action:contacts/username:'); ?><?php print $author['username']; ?>">Contacts</a></li>
  </ul>
</div>
<?php dbg(__FILE__, 1); ?>
<div class="border-bottom c" style="padding-bottom: 15px;">&nbsp;</div>
