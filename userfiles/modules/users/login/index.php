<?php if(isset($_GET['reset_password_link'])): ?>
<module type="users/forgot_password" />
<?php else:  ?>




<script  type="text/javascript">
    mw.require('forms.js', true);
</script>
<script  type="text/javascript">





$(document).ready(function(){



	 mw.$('#user_login_<?php print $params['id'] ?>').submit(function() {
          var subm = mw.$('[type="submit"]', this);
          d(subm)

     if(!subm.hasClass("disabled")){
       mw.tools.disable(subm, '<?php _e("Signing in..."); ?>');

 mw.form.post(mw.$('#user_login_<?php print $params['id'] ?>') , '<?php print site_url('api/user_login') ?>', function(a, b){

			  mw.response('#user_login_<?php print $params['id'] ?>',this);
			 if(typeof this.success === 'string'){
				  mw.reload_module('[data-type="<?php print $config['module'] ?>"]');
				  window.location.reload();
                  return false;
			 }
             mw.notification.msg(this, 5000);
             mw.tools.enable(subm);
	 });
   }

 return false;


 });

});
</script>
<?php
$module_template = mw('option')->get('data-template',$params['id']);
				if($module_template == false and isset($params['template'])){
					$module_template =$params['template'];
				}





				if($module_template != false){
						$template_file = module_templates( $config['module'], $module_template);

				} else {
						$template_file = module_templates( $config['module'], 'default');

				}

				//d($module_template );
				if(isset($template_file) and is_file($template_file) != false){
					include($template_file);
				} else {

						$template_file = module_templates( $config['module'], 'default');
				include($template_file);
					//print 'No default template for '.  $config['module'] .' is found';
				}

?><?php endif; ?>