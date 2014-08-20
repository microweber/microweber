<?php only_admin_access(); ?>
<?php $lic = mw()->update->get_licenses('limit=10000'); ?>
<?php if(is_array($lic) and !empty($lic)): ?>
<script>

mw.edit_licence = function($lic_id){
	
var licensemodal =  mw.modal({
	content:'<div type="settings/group/license_edit"  lic_id="'+$lic_id+'" class="module" id="lic_'+$lic_id+'"></div>',
	 onremove:function(){
	 mw.reload_module("#<?php print $params['id'] ?>");
	 },
	 name:'licensemodal'
	 });
 
	mw.reload_module("#lic_"+$lic_id);
}


mw.validate_licenses = function(){
 	$.ajax({
	  url: "<?php print site_url('api') ?>/mw_validate_licenses" 
 	}).done(function() {
	 	 mw.reload_module("#<?php print $params['id'] ?>");

	});
}



</script>

<table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table">
  <thead>
    <tr>
      <th>License</th>
      <th>Key</th>
      <th>Status</th>
      <th>View</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($lic as $item): ?>
    <tr>
      <td><?php print $item['rel']; ?></td>
      <td><?php print $item['local_key']; ?>
        <?php if(isset($item['status']) and $item['status'] == 'active'): ?>
        <small>
        <ul>
          <?php if(isset($item['rel_name']) and $item['rel_name'] != ''): ?>
          <li><?php print $item['rel_name']; ?></li>
          <?php endif; ?>
          <?php if(isset($item['registered_name']) and $item['registered_name'] != ''): ?>
          <li><?php print $item['registered_name']; ?></li>
          <?php endif; ?>
          <?php if(isset($item['company_name']) and $item['company_name'] != ''): ?>
          <li><?php print $item['company_name']; ?></li>
          <?php endif; ?>
          <?php if(isset($item['reg_on']) and $item['reg_on'] != ''): ?>
          <li>registered on <?php print date('d M ,Y',strtotime($item['reg_on'])); ?></li>
          <?php endif; ?>
          <?php if(isset($item['due_on']) and $item['due_on'] != ''): ?>
          <li>next payment on <?php print date('d M ,Y',strtotime($item['due_on'])); ?></li>
          <?php endif; ?>
        </ul>
        </small>
        <?php endif; ?></td>
      <td><?php print ucwords($item['status']); ?></td>
      <td><a class="show-on-hover mw-ui-btn mw-ui-btn-invert mw-ui-btn-medium" href="javascript:mw.edit_licence('<?php print $item['id'] ?>');">Edit</a></td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>
<a class="mw-ui-btn mw-ui-btn-invert mw-ui-btn-medium" href="javascript:mw.validate_licenses();">Validate</a>