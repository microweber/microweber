<?php $id =  false ;?>
<?php $lic =  false ;?>
<?php $local_key =  false ;?>
<?php if(isset($params['lic_id'])): ?>
	<?php $lic = mw()->update->get_licenses('one=1&id='.$params['lic_id']); ?>
<?php elseif(isset($params['prefix'])): ?>   
	<?php $lic = mw()->update->get_licenses('one=1&rel='.$params['prefix']); ?>
 
<?php endif; ?>





<?php if(isset($lic['rel'])): ?>
<?php 

$params['prefix'] = $lic['rel']; 
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
	 });

     return false;


 });
});
</script>

<form class="mw-license-key-activate" id="activate-form-<?php print $params['id'] ?>">
  <label class="mw-ui-label">License key</label>
   <input name="activate_on_save" type="hidden" value="1" />
  <?php if($id): ?>
  <input name="id" type="hidden" value="<?php print $id; ?>" />
  <?php endif; ?>
  <input type="text" name="rel" class="mw-ui-field w100" value="<?php print $params['prefix']; ?>">
  <input type="text" name="local_key" class="mw-ui-field w100" value="<?php print $local_key; ?>" >
  <button type="submit" value="Activate" class="mw-ui-btn mw-ui-btn-invert">Save key</button>
</form>
