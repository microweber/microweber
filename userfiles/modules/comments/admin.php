<? if(isset($params['backend']) == true): ?>
<? include('backend.php'); ?>
<? else : ?>
<strong>Skin/Template</strong>
<module type="admin/modules/templates"  />
<? endif; ?>
