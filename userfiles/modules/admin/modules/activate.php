<?php if(!isset($params['prefix'])): ?>
<?php return; ?>
<?php endif; ?>
<script  type="text/javascript">
$(document).ready(function(){

	 mw.$('#activate-form-<?php print $params['id']; ?>').submit(function() {

     mw.form.post(mw.$('#activate-form-<?php print $params['id']; ?>') , '<?php print site_url('api') ?>/mw_save_license', function(){
		mw.notification.msg(this);
	 });

     return false;


 });
});
</script>
<form class="mw-license-key-activate" id="activate-form-<?php print $params['id'] ?>">
            <label class="mw-ui-label">License key</label>
            <input type="text" name="rel" class="mw-ui-field w100" value="<?php print $params['prefix']; ?>">
            <input type="text" name="local_key" class="mw-ui-field w100">
            <button type="submit" value="Activate" class="mw-ui-btn mw-ui-btn-invert">Save key</button>
             
</form>
 
         