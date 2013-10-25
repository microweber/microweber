<?php

/*

type: layout

name: Custom

description: Shipping with custom fields

*/
?>
<div class="<?php print $config['module_class'] ?>">
 <br />
   <module type="custom_fields" data-id="custom-shipping-info<?php print $params['id'] ?>" default-fields="address"   />

</div>
