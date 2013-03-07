<?php

/*

type: layout

name: Dream

description: Dream

*/

?>



<script>mw.require("<?php print $config['url_to_module']; ?>css/style.css", true);</script>

<div class="contact-form contact-form-template-dream">

    <div class="edit" data-field="form_title" rel="module" data-id="<? print $params['id'] ?>">
      <h3 class="element contact-form-title">Leave a Message</h3>
    </div>
    <form class="mw_form" data-form-id="<? print $form_id ?>" name="<? print $form_id ?>" method="post" >
      <module type="custom_fields" data-id="<? print $params['id'] ?>" data-for="module"   />
      <? if(get_option('disable_captcha', $params['id']) !='y'): ?>
        <div class="control-group">
          <label>Security code</label>
          <div class="input-prepend">
            <span class="add-on" style="width: 100px;background: white"><img width="100" class="mw-captcha-img" src="<? print api_url('captcha') ?>" /></span>
            <input name="captcha" type="text"  class="mw-captcha-input"/>
          </div>
        </div>
      <?  endif;?>
      <input type="submit" class="cft-submit"  value="Send Message" />
    </form>


</div>



