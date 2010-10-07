<!-- <?php print __FILE__ ?>  -->
<?php dbg(__FILE__); ?>
<?php $type = $this->core_model->getParamFromURL ( 'type' ); ?>

<a class="master-help right" href="#help-mycontent">What is this?</a>
<div class="c"></div>

<div class="master-help" id="help-mycontent">
<p>    In this section you can review, edit and delete all of your content - articles, trainings, services, products, personal blog and galleries. Simply choose the desired content from the navigation menu.
    You can also search for specific content using the "search content" tool. Just enter a keyword in the search box and hit "Enter" or the lens icon displayed in the search box.
    You might also want to select certain preferences from the dropdown menus "Most Voted" and "Most Commented".</p>
<p>    By selecting a specific time frame, for example 24 hours or 30 days, the page will automatically load the content corresponding to this specific preference.
    If you choose for example a time frame "1 Day" from the "Most Commented" dropdown menu, the system will display for you your article or product with the most comments from the last 24 hours.</p>
</div>

<div class="c" style="padding-bottom: 10px;"></div>

<ul style="width: auto;" id="about-nav">
  <li <?php if($type == 'all'): ?> class="active" <?php endif; ?> ><a  href="<?php print site_url('users/user_action:posts/type:all'); ?>">All</a></li>
  <li <?php if(($type == 'none') or (!$type)): ?> class="active" <?php endif; ?> ><a    href="<?php print site_url('users/user_action:posts/type:none'); ?>">Articles</a></li>
  <li  <?php if($type == 'trainings'): ?> class="active" <?php endif; ?> ><a   href="<?php print site_url('users/user_action:posts/type:trainings'); ?>">Trainings</a></li>
  <li  <?php if($type == 'services'): ?> class="active" <?php endif; ?> ><a  href="<?php print site_url('users/user_action:posts/type:services'); ?>">Services</a></li>
  <li <?php if($type == 'products'): ?> class="active" <?php endif; ?>><a   href="<?php print site_url('users/user_action:posts/type:products'); ?>">Products</a></li>
  <li <?php if($type == 'blog'): ?> class="active" <?php endif; ?>><a   href="<?php print site_url('users/user_action:posts/type:blog'); ?>">Personal blog</a></li>
  <li <?php if($type == 'gallery'): ?> class="active" <?php endif; ?>><a   href="<?php print site_url('users/user_action:posts/type:gallery'); ?>">Galleries</a></li>
</ul>
<div class="c" style="padding-bottom: 12px">&nbsp;</div>
<?php dbg(__FILE__, 1); ?>
