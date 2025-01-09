<?php
/*

type: layout

name: Skin-12

description: Skin-12

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

    <div class="row text-start text-sm-start d-flex justify-content-center justify-content-lg-between">
        <?php foreach ($data as $key => $slide): ?>

            <div class="col-sm-12 mx-auto ">
                <div class="d-block d-sm-flex align-items-center">
                    <div class="my-4 me-md-5 d-flex justify-content-center position-relative">
                        <div class="w-250">
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

                    <div class="  position-relative ps-5 py-3">
                        <div class="border-end position-absolute h-100 left-0 top-0 d-none d-sm-block  "></div>

                        <h4><?php print array_get($slide, 'name'); ?></h4>
                        <p><?php print array_get($slide, 'role'); ?></p>
                        <p class="mw-big-team-bio"><?php print array_get($slide, 'bio'); ?></p>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
<?php endif; ?>
