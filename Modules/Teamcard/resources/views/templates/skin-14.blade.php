<?php
/*

type: layout

name: Skin-14

description: Skin-14

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

<div class="row d-flex justify-content-center justify-content-lg-between">
    <?php foreach ($data as $key => $slide): ?>
        <div class="col-sm-12 col-lg-6 mb-3">
            <div class="d-block">
                <?php if ($slide['file']) { ?>
                    <div class="img-as-background square mb-3">
                        <img loading="lazy" src="<?php print thumbnail($slide['file'], 800); ?>"/>
                    </div>
                <?php } else { ?>
                    <div class="img-as-background square mb-3">
                        <img loading="lazy" src="<?php print template_url() ?>modules/teamcard/templates/default-image.svg"/>
                    </div>
                <?php } ?>
                <div>
                    <h3><?php print array_get($slide, 'name'); ?></h3>
                    <p><?php print array_get($slide, 'role'); ?></p>
                    <p class="mw-big-team-bio"><?php print array_get($slide, 'bio'); ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<?php endif; ?>
