<?php
$form_values = $params;

if(($form_values['content_subtype'] == 'blog_section')) :   ?>
<script type="text/javascript">
$("input[name='subtype_change_set_form_value']").change(
         nv = $(this).val() 
         
 
         
         //$("input[name='content_subtype_value']").val(nv);
);
</script>
<?php // var_dump($form_values) ?>

<div class="ullist">
  <mw module="admin/content/category_selector"  active_category="<? print $form_values['content_subtype_value'] ?>" update_field="#content_subtype_value" />
  <script type="text/javascript">
/*$(document).ready(function () {
 $('#content_subtype_value').change(function() {
 });

});*/
</script>
</div>
<?php endif; ?>
<?php if(($form_values['content_subtype'] == 'module')) :   ?>
<script type="text/javascript">
//$("input[name='subtype_change_set_form_value']").change(
$("#subtype_change_set_form_value").change(
                                                                                   nv = $(this).val();
          //$("input[name='content_subtype_value']").val(nv);
);
</script>
<?php $modules  = CI::model('core')->plugins_getLoadedPlugins (); ?>
<select name="subtype_change_set_form_value" id="subtype_change_set_form_value">
  <option value="">None</option>
  <?php foreach($modules as  $k => $v): ?>
  <option value="<?php print $k ?>"><?php print $k ?></option>
  <?php endforeach; ?>
</select>
<?php endif; ?>