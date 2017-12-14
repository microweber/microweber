<?php

/*

type: layout

name: Default

description: Default

*/
?>


<form class="form--merge form--no-labels newsletter" method="post" id="newsletters-form-<?php print $params['id'] ?>">
    <?php print csrf_form(); ?>
    <input class="col-md-8 col-sm-6 validate-required validate-email" placeholder="Email Address" name="email" type="email" required/>

    <button type="submit" class="btn"><?php _e('Go'); ?></button>
    <br/><br/>
</form>
