<?php $rand = rand(); ?>
<div class="mw-reset mw-newsletter mw-newsletter-default">
  <div class="well">
      <div class="edit" field="newsletter_form_title"  rel="newsletter_module" data-option_group="<? print $params['id'] ?>" data-module="<? print $params['type'] ?>">
        <h3>Subscribe for our Newsletter</h3>
      </div>
      <div class="edit" field="form_sub_title"  rel="newsletter_module" data-option_group="<? print $params['id'] ?>" data-module="<? print $params['type'] ?>">
        <p>To subscribe for out newsletter please fill the form and click subscribe button!</p>
      </div>
      <form method="post" action="#">
        <div class="control-group">
          <label for="mw-newsletter-name-<?php print $rand; ?>"><?php _e("Your Name"); ?></label>
          <input tabindex="1" autocomplete="off" type="text" name="name" id="mw-newsletter-name-<?php print $rand; ?>" value="" />
        </div>
        <div class="control-group">
           <label for="mw-newsletter-email-<?php print $rand; ?>"><?php _e("Your E-mail"); ?></label>
           <input tabindex="2" autocomplete="off" type="text" name="email" id="mw-newsletter-email-<?php print $rand; ?>" value="" />
        </div>
            <input tabindex="3" class="btn btn-info btn-large"  type="submit" value="<?php _e("Subscribe me Now"); ?>" />
        </form>
    </div>
</div>


