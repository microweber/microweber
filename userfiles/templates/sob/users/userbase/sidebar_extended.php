<?php if($extended_user_sidebar == true): ?>
<div class="sidebar">
  <h2 class="profile-title">About Me</h2>
  <ul class="about-list">
    <?php if(strval(trim( $author['email'])) != '') :?>
    <li>Email:<?php print auto_link($author['email'], 'both', TRUE); ?></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['user_website'])) != '') :?>
    <li>Website:<?php print auto_link($author['user_website'], 'both', TRUE); ?></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['user_blog'])) != '') :?>
    <li>Blog:<?php print auto_link($author['user_blog'], 'both', TRUE); ?></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['country'])) != '') :?>
    <li>Country: <?php print $author['country']; ?></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['city'])) != '') :?>
    <li>City: <?php print $author['city']; ?></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['zip'])) != '') :?>
    <li>Zip: <?php print $author['zip']; ?></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['phone'])) != '') :?>
    <li>Phone: <?php print $author['phone']; ?></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['birthday'])) != '') :?>
    <li>Birthday: <?php print $author['birthday']; ?></li>
    <?php endif; ?>
  </ul>
  <br />
  <h2 class="profile-title">Follow me</h2>
  <ul class="about-list">
    <?php if(strval(trim( $author['social_twitter'])) != '') :?>
    <li><a href="<?php print prep_url($author['social_twitter']); ?>" target="_blank">Twitter</a></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['social_facebook'])) != '') :?>
    <li><a href="<?php print prep_url($author['social_facebook']); ?>" target="_blank">Facebook</a></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['social_myspace'])) != '') :?>
    <li><a href="<?php print prep_url($author['social_myspace']); ?>" target="_blank">Myspace</a></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['social_linkedin'])) != '') :?>
    <li><a href="<?php print prep_url($author['social_linkedin']); ?>" target="_blank">Linkedin</a></li>
    <?php endif; ?>
    <?php if(strval(trim( $author['social_youtube'])) != '') :?>
    <li><a href="<?php print prep_url($author['social_youtube']); ?>" target="_blank">Youtube</a></li>
    <?php endif; ?>
    <li><a href="<?php print  site_url('main/rss/author:').$author['id']; ?>" target="_blank"><img src="<?php print TEMPLATE_URL; ?>img/rss.ico.jpg" />Get RSS</a></li>
  </ul>
  <br />
  <h2 class="profile-title">Chat with me</h2>
  <div class="about-sidebar-block"> <br />
    <ul class="about-list">
      <?php if(strval(trim( $author['chat_skype'])) != '') :?>
      <li>Skype: <?php print ($author['chat_skype']); ?></li>
      <li>Googletalk: <?php print ($author['chat_googletalk']); ?></li>
      <li>Icq: <?php print ($author['chat_icq']); ?></li>
      <li>Msn: <?php print ($author['chat_msn']); ?></li>
      <?php endif; ?>
    </ul>
    <br />
  </div>
  <h2 class="profile-title">My Gallery</h2>
  <div class="about-sidebar-block">
    <ul class="profile-gallery-list">
      <li> <a href="#" class="img"></a> <a href="#">SOB Professional Live Seminar </a> </li>
      <li> <a href="#" class="img"></a> <a href="#">At the Munich Airport with Mike Klingler</a> </li>
      <li> <a href="#" class="img"></a> <a href="#">Affiliate Summit 2009</a> </li>
    </ul>
  </div>
  <h2 class="profile-title">Products</h2>
  <div class="about-sidebar-block">
    <ul class="profile-products">
      <li> <a href="#" class="img" style="background-image: url(img/demo_profile_products.jpg)"></a>
        <h3><a href="#">Product title</a></h3>
        <a href="#">There are many variations of passages</a> </li>
      <li> <a href="#" class="img" style="background-image: url(img/demo_profile_products.jpg)"></a>
        <h3><a href="#">Product title</a></h3>
        <a href="#">There are many variations of passages</a> </li>
      <li> <a href="#" class="img" style="background-image: url(img/demo_profile_products.jpg)"></a>
        <h3><a href="#">Product title</a></h3>
        <a href="#">There are many variations of passages</a> </li>
    </ul>
  </div>
  </div> <?php endif; ?>

<!-- /#profile-sidebar -->