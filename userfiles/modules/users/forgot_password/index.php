
<?php if(isset($_GET['reset_password_link'])): ?>
<module type="users/forgot_password/reset_password" />
<?php else:  ?>


<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>
<?php if($user != false): ?>
<module type="users/profile" />
<?php else:  ?>



<?php $form_btn_title =  get_option('form_btn_title', $params['id']);
		if($form_btn_title == false) { 
		    $form_btn_title = _e("Reset password", true);
		}
 
 		 ?>
<?php //$rand = uniqid(); ?>
<script  type="text/javascript">

mw.require('forms.js', true);

formenabled = true;


$(document).ready(function(){
	

	 
	 mw.$('#user_forgot_password_form{rand}').submit(function(e) {

          if(formenabled){
              formenabled = false;
              var form = this;
              $(form).addClass('loading');

              mw.tools.disable(mw.$(".btn", form)[0]);
              mw.form.post(mw.$('#user_forgot_password_form{rand}') , '<?php print site_url('api') ?>/user_send_forgot_password', function(a){
                  mw.response('#form-holder{rand}',this);
                  formenabled = true;
                  $(form).removeClass('loading');
                  mw.tools.enable(mw.$(".btn", form)[0]);
          	 });
           }


           return false;
     });
 
});
</script>


<?php

$module_template = get_option('data-template',$params['id']);
				if($module_template == false and isset($params['template'])){
					$module_template =$params['template'];
				}





				if($module_template != false){
						$template_file = module_templates( $config['module'], $module_template);

				} else {
						$template_file = module_templates( $config['module'], 'default');
                  }


                  	if(isset($template_file) and is_file($template_file) != false){
					include($template_file);
				}     else {

						$template_file = module_templates( $config['module'], 'default');
				include($template_file);
					//print 'No default template for '.  $config['module'] .' is found';
				}




?>
    
<?php endif; ?>
<?php endif; ?>