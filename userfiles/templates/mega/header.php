<?php include "head.php"; ?>
<?php if(isset($bodyclass)){
  $b = $bodyclass;
} else{$b='';} ?>
<body class="<?php print $b; ?>">
<div id="header">
    <div class="container">
        <a href="<?php print mw_site_url(); ?>" id="logo" title="Microweber">Microweber</a>
        <span class="hidden-desktop hidden-tablet pull-right fbtn fbtn-small fitem-blue" id="mobile-menu-toggle">Menu&nbsp;&nbsp;<span class="icon-align-justify"></span></span>
        <ul class="nav nav-pills hidden-mobile hidden-phone">
          <li class="active"><a href="<?php print site_url(); ?>">Home</a></li>
          <li class=""><a href="<?php print site_url(); ?>features">Features</a></li>
          <li><a href="<?php print site_url(); ?>plans">Plans</a></li>
          <li><a href="<?php print site_url(); ?>community">Community</a></li>
          <li><a href="<?php print site_url(); ?>support">Support</a></li>
          <li><a href="<?php print site_url(); ?>blog">Blog</a></li>
          <li><a href="<?php print site_url(); ?>download">Download</a></li>
          <li><a href="<?php print site_url(); ?>apidocs">API &amp; Docs</a></li>
          <?php if(user_id() == false){ ?>
            <li><a href="<?php print mw_site_url() ?>login" id="header-login">Login</a></li>
          <?php } else {   ?>
            <li class="pull-right nav-icon-btn"><a href="<?php print mw_site_url(); ?>api/logout" title="Logout"><i class="icon-off"></i></a></li>
            <li><a href="<?php print mw_site_url() ?>profile" id="header-profile"><i class="icon-user"></i>&nbsp;&nbsp;<span>Account</span></a></li>

        <?php  } ?>
        </ul>
    </div> <!-- /#header > .container -->
</div><!-- /# header -->
<div id="content">