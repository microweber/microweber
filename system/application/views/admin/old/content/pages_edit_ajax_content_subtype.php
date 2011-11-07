

<?php if(($form_values['content_subtype'] == 'dynamic')) :   ?>

<script type="text/javascript">
$("input[name='subtype_change_set_form_value']").change(
	function()
	{
	  //alert($(this).val())
	ajax_content_subtype_change_set_form_value($(this).val());
	}
);
</script>

<?php // var_dump($form_values) ?>


<div class="ullist">
  <?php //content_helpers_getCaregoriesUlTree($content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false) {
	  
//	$link = site_url('admin/content/taxonomy_categories/category_edit:');
//	$link2 = site_url('admin/content/taxonomy_categories_delete/id:');
 $this->firecms = get_instance();
 $this->firecms->content_model->content_helpers_getCaregoriesUlTree(0, "<label><input type='radio' name='subtype_change_set_form_value' id='subtype_change_set_form_value'   {active_code}   value='{id}' >{taxonomy_value}</label>", $actve_ids = array($form_values['content_subtype_value']), $active_code = 'checked');  ?>
</div>


<?php endif; ?>

<?php if(($form_values['content_subtype'] == 'module')) :   ?>



<script type="text/javascript">
//$("input[name='subtype_change_set_form_value']").change(
$("#subtype_change_set_form_value").change(
	function()
	{
	  //alert($(this).val())
	ajax_content_subtype_change_set_form_value($(this).val());
	}
);
</script>


<?php $modules  = $this->core_model->plugins_getLoadedPlugins (); ?>
<select name="subtype_change_set_form_value" id="subtype_change_set_form_value">
<option value="">None</option>
<?php foreach($modules as  $k => $v): ?>
<option value="<?php print $k ?>"><?php print $k ?></option>
<?php endforeach; ?>
</select>
<?php endif; ?>