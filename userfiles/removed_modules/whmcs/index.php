 <?php  $more = get_custom_fields('module',$params['id'],1); 
 
 
 ?>
<?php

if(!empty($more )): ?>
<?php foreach($more  as $field): ?>
<?php
 print  make_field($field);
   ?>
<?php endforeach; ?>
<?php else: ?>
<?php _e("You don't have any custom fields."); ?>

<?php endif; ?>