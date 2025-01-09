<?php
/*

type: layout

name: Skin-15

description: Skin-15

*/
?>

<script>
    $(document).ready(function ()
        { $(".mw-big-team-bio").each(function(i){
            var len=$(this).text().trim().length;
            if(len>100)
            {
                $(this).text($(this).text().substr(0,120)+'...');
            }
        });
    });
</script>

<?php if (isset($data) and $data): ?>

    <div class="d-flex justify-content-center align-items-center mt-5 flex-wrap">
        <?php foreach ($data as $key => $slide): ?>

        <div class="col-xl-3 col-md-6 col-sm-8 col-12 mx-auto d-flex justify-content-center align-items-center py-4">
            <div class="flower-card card w-100" style="border-radius: 0 20px 0 20px;">
                <?php if ($slide['file']) { ?>
                    <img loading="lazy" class="flower-team-card-img card-img-top" src="<?php print thumbnail($slide['file'], 800); ?>"/>
                <?php } else { ?>
                    <img loading="lazy" class="flower-team-card-img card-img-top" src="<?php print template_url() ?>modules/teamcard/templates/default-image.svg"/>
                <?php } ?>


                <div class="card-body">
                    <h5><?php print array_get($slide, 'name'); ?></h5>
                    <p><?php print array_get($slide, 'role'); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

<?php endif; ?>
