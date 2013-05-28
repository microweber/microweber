<?php only_admin_access(); ?>
<script  type="text/javascript">
mw.require('forms.js', true);
</script>

<script type="text/javascript">
 

 function save_lang_form(){
 
 
 mw.form.post('#language-form-<?php print $params['id'] ?>', '<?php print site_url('api/save_language_file_content'); ?>',
			function(msg) {
mw.notification.msg(this);
			 
			});
            return false;
   
 }


</script>

<?php


$cont  = get_language_file_content();


 


 ?>
<?php if(!empty($cont)): ?>
<form id="language-form-<?php print $params['id'] ?>">
<input name="unicode_temp_remove" type="hidden" value="☃"  />
<table width="100%" border="0" class="mw-ui-admin-table" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <th scope="col">Key</th>
    <th scope="col">Value</th>

  </tr>
  </thead>
  <tbody>
  <?php foreach($cont as $k => $item): ?>
  <tr> 
    <td><? print $k ?></td>
    <td><input name="<? print $k ?>" class="mw-ui-field" value="<? print $item ?>" style="width: 200px;" type="text" onchange="save_lang_form()" /></td>

  </tr>
  </tbody>
  <? endforeach; ?>
</table>
</form>
<? endif; ?>
