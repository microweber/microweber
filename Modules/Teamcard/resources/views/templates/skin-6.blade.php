<?php
/*

type: layout

name: Skin-6

description: Skin-6

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

    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-between">
        <?php foreach ($data as $key => $slide): ?>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="d-block d-sm-flex align-items-center h-100">
                    <div class="me-sm-4 mb-5 mb-sm-0 mx-auto mx-sm-0">
                        <div class="w-175 h-200 mx-auto">
                            <?php if ($slide['file']) { ?>
                                <div class="img-as-background square">
                                    <img loading="lazy" src="<?php print thumbnail($slide['file'], 800); ?>"/>
                                </div>
                            <?php } else { ?>
                                <div class="img-as-background square">
                                    <img loading="lazy" src="<?php print asset('templates/big2/modules/teamcard/templates/default-image.svg'); ?>"/>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="ms-3">
                        <h4 class="mb-1"><?php print array_get($slide, 'name'); ?></h4>
                        <p class="mb-3"><?php print array_get($slide, 'role'); ?></p>
                        <p class="mw-big-team-bio mb-1"><?php print array_get($slide, 'bio'); ?></p>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
