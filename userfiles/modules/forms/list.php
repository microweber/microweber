<? if(is_admin()==false) { mw_error('You must be logged as admin', 1); } ?>



<? mw_notif('ssss'); ?>


<? d($params); ?>





  <legend>Form settings</legend>
      <div class="control-group">
        <label class="control-label">Form name</label>
        <div class="controls">
          <input name="form_name" class="mw_option_field"   type="text" data-refresh="contact_form"  value="<?php print get_option('form_name', $params['id']) ?>" />
        </div>
      </div>