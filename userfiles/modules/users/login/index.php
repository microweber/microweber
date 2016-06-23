<?php if(isset($_GET['reset_password_link'])): ?>
<module type="users/forgot_password" />
<?php else:  ?>

<script  type="text/javascript">
    mw.require('tools.js', true);
    mw.require('forms.js', true);
</script>
<script  type="text/javascript">

$(document).ready(function(){
  if(!mw.$('#user_login_<?php print $params['id'] ?>').hasClass("custom-submit")){

	 mw.$('#user_login_<?php print $params['id'] ?>').submit(function() {
        var subm = mw.$('[type="submit"]', this);
        if(!subm.hasClass("disabled")){
             mw.tools.disable(subm, '<?php _e("Signing in..."); ?>');
             mw.form.post(mw.$('#user_login_<?php print $params['id'] ?>') , '<?php print api_link('user_login'); ?>', function(a, b){

           		// mw.response('#user_login_<?php print $params['id'] ?>',this);
      			 if(typeof this.success === 'string'){
      			      var c = mw.$('#user_login_<?php print $params['id'] ?>').dataset("callback");
					  if(c == undefined || c == ''){
						 var c = mw.$('#<?php print $params['id'] ?>').dataset("callback");
					  }
					  <?php if(!isset($params['return']) and isset($_REQUEST['return'])): ?>
					  <?php $params['return'] = $_REQUEST['return']; ?>
					  <?php endif; ?>
					  <?php if(isset($params['return'])): ?>
					  <?php 
    					  $goto =  urldecode($params['return']);
						  $goto = mw()->format->clean_xss($goto);
						  
    					  if(stristr($goto, "http://") == false and stristr($goto, "https://") == false ){
    						$goto = site_url($goto);
    					  }
                      ?>
					   window.location.href ='<?php print $goto; ?>';
					   return false;
					  <?php else:  ?>

					   if(typeof this.return === 'string'){
						    window.location.href = this.return;
					  		 return false;

					   }
					    mw.reload_module('[data-type="<?php print $config['module'] ?>"]');
					    if(c == '' ){
                          window.location.reload();
                        }
                        else{
                          if(typeof window[c] === 'function'){
                              window[c]();
                          }
                          else{
                            window.location.reload();
                          }
                        }
						 <?php endif; ?>
                        return false;
      			 }
                 mw.notification.msg(this, 5000);
                 mw.tools.enable(subm);
        	 });
        }
        return false;
     });
     }
});
</script>
<?php
$module_template = get_option('data-template',$params['id']);
if($module_template == false and isset($params['template'])){
    $module_template =$params['template'];
}

if($module_template != false){
    $template_file = module_templates( $config['module'], $module_template);
}
else {
    $template_file = module_templates( $config['module'], 'default');
}

if(isset($template_file) and is_file($template_file) != false){
    include($template_file);
} else {
    $template_file = module_templates( $config['module'], 'default');
    include($template_file);
}

?><?php endif; ?>