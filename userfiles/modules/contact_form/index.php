<?php  $form_id = "mw_contact_form_".$params['id'];  ?>
<script  type="text/javascript">
	mw.require("forms.js", true);
</script>

<script  type="text/javascript">

if(typeof  processContactForm !== 'object'){

processContactForm = {
   send: function(selector, msgselector){
        mw.form.post(selector, undefined, function(form){
			var data2 = this;

			if(typeof data2.error === 'string'){
                mw.response(mw.$(selector), data2);
		    }
            else {
                processContactForm.done(form, msgselector);
            }
     	}, true );
   },
   done: function(form, selector){
      var form = mw.$(form);
      form.addClass("deactivated");
      mw.$(selector).css("top", "20%");
      if(form.find(".mw-captcha-img")[0].length > 0){
          mw.tools.refresh_image(form.find(".mw-captcha-img")[0]);
      }
      form[0].reset();
      form.find(".alert-error").remove();
      setTimeout(function(){
          mw.$(selector).css("top", "-100%");
          form.removeClass("deactivated");
      }, 3200);
   },
   upload:function(form, callback){
        if(window['formHasUploader'] !== true ){
            callback.call(form);
        }
        else{
            __done = 0;
            var l = mw.$(".mw-uploader-explorer", form).length;
            mw.$(".mw-uploader-explorer", form).each(function(){


              this.contentWindow['uploader'].settings.url = mw.url.set_param("captcha",  $(form).find(".mw-captcha-input").val(), this.contentWindow['uploader'].settings.url);


              if(this.contentWindow['uploader'].files.length == 0){
                 __done ++;
                    if(__done == l){
                       callback.call(form);
                    }
              }
              else{
                if(!$(this).hasClass("binded")){
                    $(this).addClass("binded");
                    $(this).bind("FileUploaded", function(a,b){

                      d(a);
                      d(b);
                        __done ++;
                        if(__done == l){
                           callback.call(form);
                        }
                    });
                }
                 this.contentWindow['uploader'].start();
              }
            });
        }
   }
}



}


$(document).ready(function(){
	$('form[data-form-id="<?php print $form_id ?>"]').submit(function() {
		var this_form = this;
		var append_to_form = '<input type="hidden" name="module_name" value="<?php print $params['module']; ?>" />';
		$(this).append(append_to_form);
        processContactForm.upload(this_form, function(){
          processContactForm.send('form[data-form-id="<?php print $form_id ?>"]', "#msg<?php print $form_id ?>");
        })

    	return false;
    });
});
</script>
<?php $save_as = get_option('form_name', $params['id']);

if($save_as == false){
	$save_as = $params['id'];
}

$module_template = get_option('data-template', $params['id']);

if($module_template != false and $module_template != 'none'){
	$template_file = module_templates( $config['module'], $module_template);
} else {
	if(isset($params['template'])){
			$template_file = module_templates( $config['module'], $params['template']);
	} else {
			$template_file = module_templates( $config['module'], 'default');
	}
	
}
 $template_file_def = module_templates( $config['module'], 'default');
 
if(isset($template_file) and is_file($template_file) != false){
 	include($template_file);
} elseif(isset($template_file_def) and is_file($template_file_def) != false){
 	include($template_file_def);
} else {
	print 'No template for contact form is found';
}
?>