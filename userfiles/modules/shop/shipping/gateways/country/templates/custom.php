<?php

/*

type: layout

name: Custom

description: Shipping with custom fields

*/
?>
<div class="<?php print $config['module_class'] ?>">
 
   <module type="custom_fields" data-id="shipping-info<?php print $params['id'] ?>" data-for="module"  default-fields="city,state,zip,street"   />

</div>
