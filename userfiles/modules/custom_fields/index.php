<?  $more = get_custom_fields($params['id']); ?>
<? if(!empty($more )): ?>
<? foreach($more  as $field): ?>
<?
 print  make_field($field);
   ?>
<? endforeach; ?>
<? endif; ?>
