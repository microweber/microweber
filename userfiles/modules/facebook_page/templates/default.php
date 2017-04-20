<?php

/*

type: layout

name: Default

description: Default

*/
?>


<div class="row">
    <div class="col-xs-12">
        <iframe src="https://www.facebook.com/plugins/page.php?href=<?php print $fbPage; ?>&tabs=timeline&width=<?php print $width; ?>&height=<?php print $height; ?>&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=<?php print $friends; ?>"
                width="<?php print $width; ?>" height="<?php print $height; ?>" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
    </div>
</div>

<?php if (is_admin()): ?>
    <?php print notif(_e('Click here to edit the FB Page URL', true)); ?>
<?php endif; ?>
