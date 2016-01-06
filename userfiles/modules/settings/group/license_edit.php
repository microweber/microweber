<?php $id =  false ;?>
<?php $lic =  false ;?>
<?php $local_key =  false ;?>
<?php if(isset($params['lic_id'])): ?>
<?php $lic = mw()->update->get_licenses('one=1&id='.$params['lic_id']); ?>
<?php elseif(isset($params['prefix'])): ?>
<?php $lic = mw()->update->get_licenses('one=1&rel='.$params['prefix']); ?>
<?php endif; ?>
<?php if(isset($lic['rel_type'])): ?>
<?php 

$params['prefix'] = $lic['rel_type']; 
$local_key =  $lic['local_key'];
$id =  $lic['id'];
?>
<?php endif; ?>
<?php if(!isset($params['prefix'])): ?>
<?php return; ?>
<?php endif; ?>

<script  type="text/javascript">
$(document).ready(function(){

	 mw.$('#activate-form-<?php print $params['id']; ?>').submit(function() {

     mw.form.post(mw.$('#activate-form-<?php print $params['id']; ?>') , '<?php print site_url('api') ?>/mw_save_license', function(){
		 
		mw.notification.msg(this);
	//	mw.reload_module('#<?php print $params['parent-module']; ?>');
		mw.reload_module('<?php print $params['parent-module']; ?>');
	 });

     return false;


 });
});
</script>

  <small>
  
  <a target="_blank" class="mw-ui-btn mw-ui-btn-info" href="https://microweber.com/goto?prefix=<?php print $params['prefix']; ?>">Get activation key</a>
  
  </small>

<br />
<form class="mw-license-key-activate" id="activate-form-<?php print $params['id'] ?>">
  <label class="mw-ui-label">Enter License Key</label>

  <input name="activate_on_save" type="hidden" value="1" />
  <?php if($id): ?>
  <input name="id" type="hidden" value="<?php print $id; ?>" />
  <?php endif; ?>
  <input type="hidden" name="rel"   value="<?php print $params['prefix']; ?>">
  <input type="text" name="local_key" class="mw-ui-field w100" value="<?php print $local_key; ?>" >
  <button type="submit" value="Activate" class="mw-ui-btn mw-ui-btn-invert">Save key</button>
  <?php if(isset($lic['status'])): ?>
  <div class="mw-ui-box mw-ui-box-content <?php if($lic['status'] =='active'): ?> mw-ui-box-notification <?php endif; ?>">Status: <?php print ucwords($lic['status']) ?></div>
  <?php endif; ?>
</form>
