<?php if (!isset($params['no-for-fields'])) { ?>
    <input type="hidden" name="for_id" value="<?php print $for_id ?>"/>
    <input type="hidden" name="for" value="<?php print $for ?>"/>
<?php } ?>

<?php if (!empty($data)): ?>
    <?php $price_fields = array(); ?>
    
    <div class="row">
    <?php foreach ($data as $field): ?>
	    
		<?php    
		$field_size = 'small';
		$field_size_class = '';
		if (isset($field['options']['field_size'])) {
			$field_size = $field['options']['field_size'];
		}
		
		if ($field_size == 'extra_small') {
			$field_size_class = 'col-md-2';
		} else if ($field_size == 'small') {
			$field_size_class = 'col-md-4';
		} else if ($field_size == 'medium') {
			$field_size_class = 'col-md-6';
		} else if ($field_size == 'normal') {
			$field_size_class = 'col-md-8';
		} else if ($field_size == 'large') {
			$field_size_class = 'col-md-10';
		} else if ($field_size == 'extra_large') {
			$field_size_class = 'col-md-12';
		}
		?>
    
  	  <div class="<?php echo $field_size_class; ?>">
        <?php
        if (!in_array($field['type'], $skip_types)) {
            if (isset($field['type']) and $field['type'] == 'price') {
                $price_fields[] = $field;
            } else {
                $prined_items_count++;
                $field['params'] = $params;

                print  mw()->fields_manager->make($field);
            }
        }
        ?>
         </div>
    <?php endforeach; ?>
    </div>
    
    <?php if (!in_array('price', $skip_types) and is_array($price_fields)): ?>
        <?php $price_fields_c = count($price_fields); ?>
        <?php if ($price_fields_c > 1) : ?>
            <select name="price">
        <?php endif; ?>
        <?php foreach ($price_fields as $field): ?>
            <?php
            $prined_items_count++;
            if ($price_fields_c > 1) {
                $field['make_select'] = true;
            } ?>
            <?php print  mw()->fields_manager->make($field); ?>
        <?php endforeach; ?>
        <?php if ($price_fields_c > 1) : ?>
            </select>
        <?php else: ?>
        <?php endif; ?>
    <?php endif; ?>
<?php else: ?>
<?php endif; ?>
<?php if ($prined_items_count == 0): ?>
    <?php print lnotif(_e('Click on settings to edit your custom fields.', true)); ?>
<?php endif; ?>
