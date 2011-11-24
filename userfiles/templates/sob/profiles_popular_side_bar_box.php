<?php dbg(__FILE__); ?>

<div class="c" style="padding-top: 20px;">&nbsp;</div>
<?php if($no_profiles_popular_title == false): ?>
<?php endif; ?>
<?php $data  = array();
  $data['is_active'] = 'y';
   $data['is_popular'] = 'y';
 
  $users = $this->users_model->getUsers($data,$limit = array(0,50), $count_only = false );
  shuffle($users);
  $users = array_slice($users, 0, 5);
 // p(  $users );
  ?>
<div class="pop-side">
  <h2 class="pop-profiles-title" title="Popular Profiles">Popular Profiles</h2>
  <ul>
    <?php foreach($users as $author): ?>
    <?php $thumb = $this->users_model->getUserThumbnail( $author['id'], 60); ?>
    <li><a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>" class="img" style="background-image: url('<?php print $thumb ?>')"></a>
      <h3><a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>"><?php print $author['first_name']; ?> <?php print $author['last_name']; ?></a></h3>
      <span class="date"><?php print $author['user_status']; ?></span>
      <p><?php print character_limiter(strip_tags(html_entity_decode($author['user_information'])), 100, '...'); ?></p>
    </li>
    <?php endforeach; ?>
  </ul>
  <a href="<?php print site_url('userbase/'); ?>" class="btn right" style="margin-right: 7px;">Show all</a>
  <div class="c" style="padding-bottom: 12px"></div>
</div>
<?php dbg(__FILE__, 1); ?>
