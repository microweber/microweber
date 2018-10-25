<?php

/*

type: layout

name: Full Width with Text

description: Full width background

*/
?>

<style>
    .parallax-wrapper.parallax-fluid-1{
        height: <?php print $height; ?>px;
    }

    .parallax-wrapper.parallax-fluid-1 .parallax {
        opacity: <?php print $alpha; ?>;
    }
</style>

<div class=" module-parallax">
    <div class="parallax-wrapper parallax-fluid-1">
        <div class="info-box-fluid">
            <div class="middle-content">

                <div class="edit plain-text" field="parallax_text" rel="<?php print $params['id']; ?>">
                    <p><?php print $infoBox; ?></p>

                    <module type="btn" id="parallax-btn-<?php print $params['id']; ?>" template="bootstrap" button_style="btn-primary"/>
                </div>

            </div>
        </div>

        <div class="parallax" style="background-image: url(<?php print $parallax; ?>); "></div>
    </div>
</div>
<div class="clearfix"></div>

<?php if (is_admin()): ?>
    <?php print lnotif(_e('Click here to edit the Parallax', true)); ?>
<?php endif; ?>
