<?php

/*

type: layout

name: Basic

description: Basic contact form

*/

?>

<script>
    mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css", true);
</script>

<div class="contact-form-container contact-form-template-basic">
    <div class="edit" data-field="contact_form_title" rel="newsletter_module" data-id="<?php print $params['id'] ?>">
        <h3>Write us a letter</h3>
        <hr>
    </div>
    <form class="mw_form" data-form-id="<?php print $form_id ?>" name="<?php print $form_id ?>" method="post">

        <module type="custom_fields" for-id="<?php print $params['id'] ?>" data-for="module" default-fields="name,email,message"/>
      
    </form>

</div>



