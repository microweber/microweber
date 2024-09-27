<?php
/*

type: layout

name: Skin-1

description: Skin-1

*/
?>

<script>
    $(document).ready(function () {
        $('.js-show-team-member', '#<?php echo $params['id']; ?>').on('click', function () {
            var id = $(this).data('id');
            $('.js-member').hide();
            $('.js-member[data-id="' + id + '"]').show();
        });
    });
</script>

<div class="row text-center text-md-start d-flex align-items-center justify-content-center justify-content-lg-between">
    <div class="col-sm-10 col-md-6 col-lg-5 col-lg-4 mb-5 mb-md-0">
        <?php if (isset($data) and $data): ?>
            <?php foreach ($data as $key => $slide): ?>
                <div class="w-250 mx-auto js-member" data-id="<?php echo $key; ?>" style="<?php if ($key > 0): ?>display: none; <?php endif; ?>">
                    <div class="img-as-background   square">
                        <img src="<?php print thumbnail($slide['file'], 850); ?>"/>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="col-sm-10 col-md-6 col-lg-5 col-lg-4">
        <?php if (isset($data) and $data): ?>
            <?php foreach ($data as $key => $slide): ?>
                <div class="js-member" data-id="<?php echo $key; ?>" style="<?php if ($key > 0): ?>display: none; <?php endif; ?>">
                    <h1 class="mb-1"><?php print array_get($slide, 'name'); ?></h1>
                    <p class="lead mb-3"><?php print array_get($slide, 'role'); ?></p>
                    <p class="lead"><?php print array_get($slide, 'bio'); ?></p>
                    <module type="social_links" template="skin-2"/>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="col-sm-10 col-md-12 col-lg-2">
        <div class="d-flex flex-lg-column align-items-center justify-content-center mt-7 mt-lg-0">
            <?php if (isset($data) and $data): ?>
                <?php foreach ($data as $key => $slide): ?>
                    <div class="w-80 m-4 cursor-pointer js-show-team-member" data-id="<?php echo $key; ?>">
                        <div class="img-as-background rounded-circle square">
                            <img src="<?php print thumbnail($slide['file'], 80); ?>"/>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
