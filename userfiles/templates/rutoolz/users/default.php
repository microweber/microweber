<div class="pad">

     
     
 
          <? if(strval($user_session['is_logged'] ) == 'yes'):  ?>
           <? $user_data = $this->users_model->getUserById ( $user_session ['user_id'] );   ?>
         <? //   var_dump($user_data); ?>
         Hello, <? print $user_data['username'] ;  ?><br />

         
         
         
         <? else :  ?>
          <a href="<? print site_url('users/user_action:login'); ?>" class="<? if($user_action == 'login') : ?> active<? endif; ?>">Login</a>
          <a href="<? print site_url('users/user_action:register'); ?>" class="<? if($user_action == 'register') : ?> active<? endif; ?>">Register</a>
          <? endif; ?>
          
 
     
  <? //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar.php') ?>
</div>
