<?php
/*

type: layout

name: Skin-3

description: Skin-3

*/
?>

<?php if (isset($data) and $data): ?>
    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center">
        <?php foreach ($data as $key => $slide): ?>
            <div class="col-sm-6 col-md-4 col-lg-4 mb-8">
                <div class="d-block position-relative show-on-hover-root">
                    <div class="img-as-background   mh-400 mb-3">
                        <img src="<?php print thumbnail($slide['file'], 800); ?>"/>
                    </div>

                    <div class="show-on-hover position-absolute bg-body border border-color-primary   d-flex align-items-center justify-content-center mh-400 w-100 top-0 mb-3 p-5">
                        <div class="text-center">
                            <h4 class="mb-2"><?php print array_get($slide, 'name'); ?></h4>
                            <p class="mb-4"><?php print array_get($slide, 'role'); ?></p>
                            <p><?php print array_get($slide, 'bio'); ?></p>
                            <module type="social_links" template="skin-1"/>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
