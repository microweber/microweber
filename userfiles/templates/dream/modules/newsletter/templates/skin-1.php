<?php

/*

type: layout

name: Newsletter Form

description: Skin 1

*/
?>


<div class="form-subscribe-1 boxed boxed--lg bg--white text-center box-shadow-wide edit nodrop" field="module-<?php print $params['id'] ?>" rel="module">
    <h4>Keep Informed</h4>
    <p class="lead">
        Subscribe for free resources and news updates.
    </p>

    <form class="" method="post" id="newsletters-form-<?php print $params['id'] ?>">
        <?php print csrf_form(); ?>
        <div class="input-with-icon">
            <label for="fieldName">Your Name</label>
            <i class="icon icon-Male-2"></i>
            <input id="fieldName" name="name" type="text" required>
        </div>

        <div class="input-with-icon">
            <label for="fieldEmail">Email Address</label>
            <i class="icon icon-Mail-2"></i>
            <input class="validate-required validate-email" id="fieldEmail" name="email" type="email" required>
        </div>

        <button type="submit" class="btn"><?php _e('Subscribe Now'); ?></button>
        <br /><br />
    </form>
</div>