<? if($params['for_module_id']): ?>
	<?   $more = get_custom_fields($params['for_module_id']);    ?>
    <? if(!empty( $more)):  ?>
    <? foreach( $more as $field): ?>
    <?  print  make_field($field, false, 1); ?>
    <? endforeach; ?>
    <? endif; ?>
<? endif; ?>
