<?php

/*

  type: layout

  name: Default

  description: Default template for Contact form
  
  icon: dream.png


*/

?>


<script>
    mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css");
</script>

<div class="contact-form-container contact-form-template-dream">
    <div class="contact-form">
        <div class="edit" data-field="contact_form_title" rel="contact_form_module" data-id="<?php print $params['id'] ?>">
            <h3 class="element contact-form-title"><?php _e("Leave a Message"); ?></h3>
        </div>
        <form class="mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">
		
            <module type="custom_fields" for-id="<?php print $params['id'] ?>" data-for="module" default-fields="name,email,message"/>
            
        </form>
    </div>
    <div class="message-sent" id="msg<?php print $form_id ?>">
        <span class="message-sent-icon"></span>
        <p><?php _e("Your Email was sent successfully"); ?></p>
    </div>
</div>
