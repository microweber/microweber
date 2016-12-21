<?php 
if(!isset($params['option-group'])){
return;	
}


$data = get_testimonials("group_by=project_name");

$selected = get_option('show_testimonials_per_project',$params['option-group']);




 ?>
<?php if(!empty($data)): ?>
<select class="mw-ui-field mw_option_field" name="show_testimonials_per_project" option-group="<?php print $params['option-group'] ?>">
  <option value="">All projects</option>
  <?php foreach($data as $item): ?>
  <?php if($item['project_name'] != ''): ?>
  <option <?php if($item['project_name'] == $selected): ?> selected="selected" <?php endif; ?> value="<?php print $item['project_name'] ?>"><?php print mw()->format->titlelize($item['project_name']) ?></option>
  <?php endif; ?>
  <?php endforeach; ?>
</select>
<?php endif; ?>
