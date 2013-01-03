 <?  $more = get_custom_fields('module',$params['id'],1); 
 
 
 ?>
<?

if(!empty($more )): ?>
<? foreach($more  as $field): ?>
<?
 print  make_field($field);
   ?>
<? endforeach; ?>
<? else: ?>
<? _e("You don't have any custom fields."); ?>

<? endif; ?>