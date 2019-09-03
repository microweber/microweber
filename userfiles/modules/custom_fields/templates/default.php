<div class="<?php echo get_template_row_class(); ?>">
	 <?php if (!empty($fields_group)): ?>
		 <?php foreach ($fields_group as $fields): ?>
		 
		 <?php if (!empty($fields)): ?>
		 
		  	  <?php foreach ($fields as $field): ?>
		       		<?php echo $field['html']; ?>
		    <?php endforeach; ?>
		 
		 <?php endif; ?>
		 
		 <?php endforeach; ?>
	 <?php endif; ?>
</div>