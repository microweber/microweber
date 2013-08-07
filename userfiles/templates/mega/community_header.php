<div id="community-header">


<?php if(user_id() == false){   ?>

      <div class="container">
          <a class="pull-left lite-btn" href="login">Login to add New Post</a>
      </div>


<?php  } else {  ?>


      <div class="container">
          <h5 class="pull-left">Welcome, <?php print user_name(); ?></h5>
          <a class="pull-left iicon" href="<?php print mw_site_url(); ?>profile" ><i class="icon-pencil"></i> <small>Edit profile</small></a>
          <a class="pull-left lite-btn" href="<?php print page_link(CONTENT_ID); ?>?new-topic">New Post</a>
      </div>


<?php } ?>

    </div>