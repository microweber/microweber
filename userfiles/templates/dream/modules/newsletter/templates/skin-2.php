<?php

/*

type: layout

name: CTA with Newsletter Form

description: Skin 2

*/
?>


<div class="boxed bg--white box-shadow edit nodrop" field="module-<?php print $params['id'] ?>" rel="module">
    <h4>Stand with us</h4>
    <span>Sign up and join the campaign</span>

    <form class="" method="post" id="newsletters-form-<?php print $params['id'] ?>">
        <?php print csrf_form(); ?>
        <input name="name" type="text" placeholder="<?php _e('Your Name'); ?>" required>
        <input name="email" type="email" placeholder="<?php _e('Email Address'); ?>" required>
        <button type="submit" class="btn btn--primary vpe"><?php _e('Join The Campaign'); ?></button>
        <br />
    </form>

    <span class="type--fine-print">View our <a href="#">privacy policy</a> </span>
</div>