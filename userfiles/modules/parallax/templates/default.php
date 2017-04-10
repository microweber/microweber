<?php

/*

type: layout

name: Default

description: Default

*/
?>

<style>
    .parallax {
        background-image: url("<?php print $parallax; ?>");
    }
</style>

<div class="row module-parallax">
    <div class="col-xs-12 col-md-6">
        <div class="parallax"></div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="info-box">
            <div class="middle-content">
                <div class="row">
                    <div class="col-xs-12 col-md-3">
                        <img src="<?php print $infoImage; ?>"
                             alt=""/>
                    </div>
                    <div class="col-xs-12 col-md-9">
                        <div class="edit" field="parallax_text" rel="<?php print $params['id']; ?>">
                            <?php print $infoBox; ?>

                            <module type="btn" id="parallax-btn-<?php print $params['id']; ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (is_admin()): ?>
    <?php print notif(_e('Click here to edit the Parallax', true)); ?>
<?php endif; ?>
