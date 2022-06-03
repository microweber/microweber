<?php  $form_id = "mw_contact_form_".$params['id'];  ?>
<script  type="text/javascript">
	mw.require("forms.js", true);
</script>
<script type="text/javascript">
if(typeof  processContactForm !== 'object'){

    processContactForm = {
       send: function(selector, msgselector){
           var spinner = mw.spinner({
               element: selector,
               size: 60,
               decorate: true
           });
           spinner.show()
           if(spinner && spinner.setState){
           spinner.setState('loading')
           }
			mw.$('input[type="submit"]',selector).attr('disabled', 'disabled');

            mw.form.post(selector, undefined, function(form){
                if(spinner && spinner.setState){
                spinner.setState('done')
                }
    			var data2 = this;
    			if(typeof data2.error === 'string'){
                    mw.response(mw.$(selector), data2);
    		    } else if(typeof data2.redirect === 'string'){
                     window.location.href = data2.redirect;
                }
                else {
                    processContactForm.done(form, msgselector, spinner);
                }
         	}, true, function () {

                var data2 = this.responseJSON;

                spinner.remove()
                mw.$('input[type="submit"]',selector).attr('disabled', false);

                if(typeof data2.error === 'string'){
                    mw.response(mw.$(selector), data2);
                } else if(typeof data2.redirect === 'string'){
                    window.location.href = data2.redirect;
                }

            } );


       },
       done: function(form, selector, spinner){
          form = mw.$(form);
          form.removeClass("was-validated");
          if(typeof form.find(".mw-captcha-img")[0] !== 'undefined'){
              mw.tools.refresh_image(form.find(".mw-captcha-img")[0]);
          }
		  mw.$('input[type="submit"]',form).removeAttr('disabled');
          form[0].reset();
          form.find(".alert-error").remove();
          setTimeout(function(){
			  var cap = document.getElementById('captcha-<?php print $form_id; ?>');
			  if(cap !== null){
			    mw.tools.refresh_image(cap);
			  }
			  mw.$(selector).show();
                if(spinner) {
                  spinner.remove()
              }
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


                  if(this.contentWindow['uploader'].files.length === 0){
                     __done ++;
                        if(__done == l){
                           callback.call(form);
                        }
                  }
                  else{
                    if(!$(this).hasClass("binded")){
                        $(this).addClass("binded");
                        $(this).on("FileUploaded", function(){
                            __done ++;
                            if(__done == l){
                               callback.call(form);
                            }
                            this.contentWindow['uploader'].splice(0);
                        });

                        $(this).on("responseError", function(a,b,c){
                          __done ++;
                          if( __done == l ){

                                mw.response(mw.$(form), b.error);
                            }
                        });
                    }
                    else{
                      this.contentWindow['uploader'].files[0].status = this.contentWindow['plupload'].QUEUED;
                    }

                     this.contentWindow['uploader'].start();
                  }
                });
            }
       }
    }
}

$(document).ready(function(){
	mw.$('form[data-form-id="<?php print $form_id ?>"]','#<?php print $params['id'] ?>')
        .append('<input type="hidden" name="module_name"  value="<?php print($params['module']); ?>" />')
        .on('submit', function() {

            processContactForm.send('form[data-form-id="<?php print $form_id ?>"]', "#msg<?php print $form_id; ?>");
            mw.$('input[type="submit"]','form[data-form-id="<?php print $form_id ?>"]').removeAttr('disabled');
            return false;
        });
	$('body').click(function(){
	   if( $('#msg<?php print $form_id; ?>').is(":visible") ) {
		  $('#msg<?php print $form_id; ?>').hide();
	   }
	});
	mw.element('[data-custom-field-error-text][required]').each(function (){
        this.setCustomValidity(this.value ? '' : this.getAttribute('data-custom-field-error-text'))
        mw.element(this).on('input', function (e) {
             this.setCustomValidity(this.value ? '' : this.getAttribute('data-custom-field-error-text'))
        })
    })
});
</script>

<?php
$save_as = get_option('form_name', $params['id']);

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

/*
$require_terms = get_option('require_terms', $params['module'] . '_default');
$require_terms_when = '';

if($require_terms) {
    if(is_logged()){
    	if(mw()->user_manager->terms_check('terms_contact', user_id())) {
    	    $require_terms = 'n';
    	}
    }
    if($require_terms) {
		$require_terms_when = get_option('require_terms_when', $params['module'] . '_default');
		if($require_terms_when == 'b') {
			$terms_label = get_option('terms_label', 'users');
            $terms_url = get_option('terms_url', 'users');
            if(!$terms_url){
                $terms_url = site_url(). 'terms';
            }

            $terms_label_cleared = str_replace('&nbsp;', '', $terms_label);
			$terms_label_cleared = strip_tags($terms_label_cleared);
			$terms_label_cleared = mb_trim($terms_label_cleared);
			if ($terms_label_cleared == '') {
				$terms_label = 'I agree with the <a href="' . $terms_url . '" target="_blank">Terms and Conditions</a>';
			}
		}
	}
}*/

$show_newsletter_subscription = get_option('newsletter_subscription', $params['module'] . '_default');
if($show_newsletter_subscription == 'y') {
	$newsletter_subscribed = false;
	if(is_logged()){
	    if(mw()->user_manager->terms_check('terms_newsletter', user_id())){
		    $newsletter_subscribed = true;
	    }
	}
}

if(isset($template_file) and is_file($template_file) != false){
 	include($template_file);
} elseif(isset($template_file_def) and is_file($template_file_def) != false){
 	include($template_file_def);
} else {
	print _e('No template for contact form is found');
}
?>
