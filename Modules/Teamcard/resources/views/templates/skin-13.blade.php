<?php
/*

type: layout

name: Skin-13

description: Skin-13

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

    <div class="row py-4 text-start text-left text-sm-start d-flex justify-content-center justify-content-lg-between">
        <?php foreach ($data as $key => $slide): ?>

            <div class="col-sm-12 col-md-6 col-lg-3 mb-3">
                <div class="d-block text-md-start text-center">
                    <div class="mb-5 mx-auto text-center d-flex justify-content-center justify-content-md-start">
                        <div class="w-200">
                            <?php if ($slide['file']) { ?>
                                <div class="img-as-background square rounded-circle">
                                    <img loading="lazy" src="<?php print thumbnail($slide['file'], 800); ?>"/>
                                </div>
                            <?php } else { ?>
                                <div class="img-as-background square rounded-circle">
                                    <img loading="lazy" src="<?php print template_url() ?>modules/teamcard/templates/default-image.svg"/>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div>
                        <h6><?php print array_get($slide, 'name'); ?></h6>
                        <p><?php print array_get($slide, 'role'); ?></p>
                        <p class="mw-big-team-bio"><?php print array_get($slide, 'bio'); ?></p>
                     </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
