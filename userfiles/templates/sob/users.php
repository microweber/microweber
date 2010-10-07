<?php dbg(__FILE__); ?>
<div id="comunity-bar">
  <form action="#" method="post">
    <input type="text" onblur="this.value==''?this.value='Search':''" onfocus="this.value=='Search'?this.value='':''" value="Search" class="type-text">
    <input type="submit" class="type-submit" value="">
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
  <div class="post" style="padding-left:0"> <a href="#" class="eimg"> <span style="background-image: url();"></span> </a>
    <h2 class="post-title"><a href="#">Petar Vasilev Nikolov</a></h2>
    <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.</p>
  </div>
  <div class="post" style="padding-left:0"> <a href="#" class="eimg"> <span style="background-image: url();"></span> </a>
    <h2 class="post-title"><a href="#">Petar Vasilev Nikolov</a></h2>
    <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.</p>
  </div>
  <div class="pad"> <span class="paging-label left"> Browse pages </span>
    <ul class="paging inline">
      <li><a href="#" class="quo laquo2"><span>&lsaquo;&lsaquo;</span></a></li>
      <li><a href="#" class="quo laquo"><span>&lsaquo;</span></a></li>
      <li><a href="#" class="active">1</a></li>
      <li><a href="#">2</a></li>
      <li><a href="#">3</a></li>
      <li><a href="#">4</a></li>
      <li><a href="#">5</a></li>
      <li><a href="#" class="quo raquo"><span>&rsaquo;</span></a></li>
      <li><a href="#" class="quo raquo2"><span>&rsaquo;&rsaquo;</span></a></li>
    </ul>
  </div>
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
    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_learning_center.php') ?>
  </div>
  <h2 class="title clear">Products</h2>
  <ul class="profile-products">
    <li class="border-bottom"> <a style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_profile_products.jpg)" class="img" href="#"></a>
      <h3><a href="#">Product title</a></h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the </p>
      <a class="more" href="#">Read more</a> </li>
    <li class="border-bottom"> <a style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_like_profile_products_3.jpg)" class="img" href="#"></a>
      <h3><a href="#">Product title</a></h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the </p>
      <a class="more" href="#">Read more</a> </li>
    <li class="border-bottom"> <a style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_profile_products.jpg)" class="img" href="#"></a>
      <h3><a href="#">Product title</a></h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the </p>
      <a class="more" href="#">Read more</a> </li>
    <li class="border-bottom"> <a style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_like_profile_products_3.jpg)" class="img" href="#"></a>
      <h3><a href="#">Product title</a></h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the </p>
      <a class="more" href="#">Read more</a> </li>
  </ul>
  <a href="#" class="btn right wmarg">See All Products</a> </div>
<!-- /.sidebar -->
<?php dbg(__FILE__, 1); ?>