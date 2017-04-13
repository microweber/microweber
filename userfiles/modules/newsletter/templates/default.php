<?php

/*

type: layout

name: Default

description: Default

*/
?>
<div class="newsletter-module-wrapper well">

    <h2><?php _e('Newsletter'); ?></h2>

    <p><?php _e('Subscribe to our newsletter and stay tuned.'); ?></p>
    <form method="post" id="newsletters-form-<?php print $params['id'] ?>">
        <?php print csrf_form(); ?>

        <div class="form-group hide-on-success">
            <label class="control-label requiredField" for="email1">
                <?php _e('Email'); ?>
                <span class="asteriskField">*</span>
            </label>
            <input class="form-control" name="email" placeholder="your@email.com" type="text"/>
        </div>
        <div class="form-group  hide-on-success">
            <div>
                <button class="btn btn-primary " name="submit" type="submit">
                    <?php _e('Submit'); ?>
                </button>
            </div>
        </div>
    </form>


</div>