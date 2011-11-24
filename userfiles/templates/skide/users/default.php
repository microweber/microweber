<div class="pad">

     
     
 
          <?php if(strval($user_session['is_logged'] ) == 'yes'):  ?>
           <?php $user_data = CI::model('users')->getUserById ( $user_session ['user_id'] );   ?>
         <?php //   var_dump($user_data); ?>
         Hello, <?php print $user_data['username'] ;  ?><br />

         
         
         
         <?php else :  ?>
          <a href="<?php print site_url('users/user_action:login'); ?>" class="<?php if($user_action == 'login') : ?> active<?php endif; ?>">Login</a>
          <a href="<?php print site_url('users/user_action:register'); ?>" class="<?php if($user_action == 'register') : ?> active<?php endif; ?>">Register</a>
          <?php endif; ?>
          
 
     
  <?php //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar.php') ?>
</div>
