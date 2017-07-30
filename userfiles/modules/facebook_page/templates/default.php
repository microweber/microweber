<?php

/*

type: layout

name: Default

description: Default

*/
?>

<style>
    .fb-page,
    .fb-page  iframe[style] {
        max-width: 100% !important;
    }
</style>


<div class="row">
    <div class="col-xs-12 fb-page">
        <iframe src="https://www.facebook.com/plugins/page.php?href=<?php print $fbPage; ?><?php print $timeline; ?>&width=<?php print $width; ?>&height=<?php print $height; ?>&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=<?php print $friends; ?>"
                width="<?php print $width; ?>" height="<?php print $height; ?>" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
    </div>
</div>

<?php if (is_admin()): ?>
    <?php print notif(_e('Click here to edit the FB Page URL', true)); ?>
<?php endif; ?>
