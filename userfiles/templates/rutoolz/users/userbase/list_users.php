
<div id="comunity-bar">
  <form 
	action="<? print site_url('/userbase/users_do_search') ; ?>" 
	method="post"  
	enctype="multipart/form-data" 
	onblur="this.value==''?this.value='Search':''" onfocus="this.value=='Search'?this.value='':''" value="Search" 
	>
    <div align="center">
      <input 
			type="text" 
			name="search_by_keyword" 
			id="search_by_keyword" 
			value="<?php print $search_by_keyword?>"
		/>
      &nbsp;
      <input type="submit" value="" class="type-submit" />
    </div>
  </form>
  <h2 class="title">Users</h2>
</div>
<div class="main">
  <div class="users-search-bar">
    <ul class="users-search-nav">
      <li class="active"><a href="#">Top Contributтors</a></li>
      <li><a href="#">Fastest Climbers</a></li>
      <li><a href="#">Top Bloggers</a></li>
      <li><a href="#">Top Commentators</a></li>
    </ul>
    <div class="c border-bottom">&nbsp;</div>
    <form method="post" action="#" class="xform">
      <input class="cinput" type="text" value="Name"  onblur="this.value==''?this.value='Name':''" onfocus="this.value=='Name'?this.value='':''" />
      <input class="cinput" type="text" value="Other" onblur="this.value==''?this.value='Other':''" onfocus="this.value=='Other'?this.value='':''"  />
      <input class="cinput" type="text" value="Other" onblur="this.value==''?this.value='Other':''" onfocus="this.value=='Other'?this.value='':''"  />
      <a href="#" class="submit">Filter</a>
    </form>
  </div>
  <!-- /.users-search-bar-->
  <? foreach($users_list as $item): ?>
  <div class="post" style="padding-left:0"> <a href="<? print site_url('userbase/action:profile/username:').$item['username'] ?>" class="eimg"> <span style="background-image: url('<? print   $thumb = $this->users_model->getUserThumbnail($item['id'], 118); ?>');"></span> </a>
    <h2 class="post-title"><a href="<? print site_url('userbase/action:profile/username:').$item['username'] ?>"><? print $item['first_name'] ?> <? print $item['last_name'] ?></a></h2>
    <p><? print character_limiter($item['user_information'], 60, '...'); ?></p>
  </div>
  <? endforeach; ?>
  <? if(!empty($content_pages_links)): ?>
  <div class="pad"> <span class="paging-label left"> Browse pages </span>
    <ul class="paging inline">
      <li><a href="#" class="quo laquo2"><span>&lsaquo;&lsaquo;</span></a></li>
      <li><a href="#" class="quo laquo"><span>&lsaquo;</span></a></li>
      <!--<li class="back"><a href="javascript:;">&nbsp;</a></li>-->
      <? $i = 1; foreach($content_pages_links as $page_link) : ?>
      <li <? if($content_pages_curent_page == $i) : ?>  class="active"  <?  endif; ?> ><a href="<? print $page_link ;  ?>"   ><? print $i ;  ?></a></li>
      <!--<li class="next"><a href="javascript:;">&nbsp;</a></li>
      <li class="forward"><a href="javascript:;">&nbsp;</a></li>-->
      <? $i++; endforeach;  ?>
      <li><a href="#" class="quo raquo"><span>&rsaquo;</span></a></li>
      <li><a href="#" class="quo raquo2"><span>&rsaquo;&rsaquo;</span></a></li>
    </ul>
  </div>
  <? endif; ?>
</div>
<!-- /.main-->
<div class="sidebar">
  <div class="pad border-bottom">
    <h2 class="title">How to?</h2>
    <br />
    <ul class="how-to-nav">
      <li><a href="#">Where am I?</a></li>
      <li><a href="#">How this website will helps me?</a></li>
      <li><a href="#">How to ask questions?</a></li>
      <li><a href="#">Great, now what?</a></li>
    </ul>
  </div>
  <div style="width:303px;margin:auto">
    <? require (ACTIVE_TEMPLATE_DIR.'sidebar_learning_center.php') ?>
  </div>
  <h2 class="title clear">Products</h2>
  <ul class="profile-products">
    <li class="border-bottom"> <a style="background-image: url(<? print TEMPLATE_URL; ?>img/demo_profile_products.jpg)" class="img" href="#"></a>
      <h3><a href="#">Product title</a></h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the </p>
      <a class="more" href="#">Read more</a> </li>
    <li class="border-bottom"> <a style="background-image: url(<? print TEMPLATE_URL; ?>img/demo_like_profile_products_3.jpg)" class="img" href="#"></a>
      <h3><a href="#">Product title</a></h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the </p>
      <a class="more" href="#">Read more</a> </li>
    <li class="border-bottom"> <a style="background-image: url(<? print TEMPLATE_URL; ?>img/demo_profile_products.jpg)" class="img" href="#"></a>
      <h3><a href="#">Product title</a></h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the </p>
      <a class="more" href="#">Read more</a> </li>
    <li class="border-bottom"> <a style="background-image: url(<? print TEMPLATE_URL; ?>img/demo_like_profile_products_3.jpg)" class="img" href="#"></a>
      <h3><a href="#">Product title</a></h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the </p>
      <a class="more" href="#">Read more</a> </li>
  </ul>
  <a href="#" class="btn right wmarg">See All Products</a> </div>
<!-- /.sidebar -->
<? //require (ACTIVE_TEMPLATE_DIR.'users/userbase/right_sidebar.php') ?>
