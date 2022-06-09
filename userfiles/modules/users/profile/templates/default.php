<div class="mw-user-logged-holder">
    <div class="mw-user-welcome">
        <?php _e("Welcome"); ?>
        <?php print user_name(); ?> </div>
    <a href="<?php print site_url() ?>">
        <?php _e("Go to"); ?>
        <?php print site_url() ?></a><br/>
    <a href="<?php print logout_url() ?>">
        <?php _e("Log Out"); ?>
    </a>
    <?php if (is_admin()): ?>
        <div class="mw-user-logged-holder"><a href="<?php print admin_url() ?>">
                <?php _e("Admin panel"); ?>
            </a></div>
    <?php endif; ?>
</div>
