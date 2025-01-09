<?php
/*

type: layout

name: Skin-3

description: Skin-3

*/
?>

<?php if (isset($data) and $data): ?>
<div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center">
        <?php foreach ($data as $key => $member): ?>
    <div class="col-sm-6 col-md-4 col-lg-4 mb-8">
        <div class="d-block position-relative show-on-hover-root">
                <?php if ($member['file']) { ?>
            <div class="img-as-background square">
                <img loading="lazy" src="<?php print thumbnail($member['file'], 800); ?>"/>
            </div>
            <?php } else { ?>
            <div class="img-as-background square">
                <img loading="lazy" src="<?php print asset('templates/big2/modules/teamcard/templates/default-image.svg'); ?>"/>
            </div>
            <?php } ?>

            <div class="show-on-hover position-absolute bg-body border border-color-primary d-flex align-items-center justify-content-center mh-400 w-100 top-0 mb-3 p-5">
                <div class="text-center">
                    <h4 class="mb-2"><?php print array_get($member, 'name'); ?></h4>
                    <p class="mb-4"><?php print array_get($member, 'role'); ?></p>
                    <p><?php print array_get($member, 'bio'); ?></p>
                    <module type="social_links" template="skin-1"/>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
