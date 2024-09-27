<?php
/*

type: layout

name: Skin-5

description: Skin-5

*/
?>

<script>
    $(document).ready(function () {
        $(".mw-big-team-bio").each(function(i){
            var len=$(this).text().trim().length;
            if(len>100)
            {
                $(this).text($(this).text().substr(0,120)+'...');
            }
        });
    });
</script>

<?php if (isset($data) and $data): ?>
    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center">
        <?php foreach ($data as $key => $slide): ?>
            <div class="col-sm-6 col-md-4 col-lg-4 mb-8 px-1">
                <div class="d-block position-relative show-on-hover-root">
                    <div class="img-as-background mh-400 mb-3">
                        <img src="<?php print thumbnail($slide['file'], 800); ?>"/>
                    </div>

                    <div class="show-on-hover position-absolute bg-body-opacity-8 d-flex flex-column justify-content-between mh-400 w-100 top-0 mb-3 pt-6 pb-3 px-5">
                        <h3 class="mb-2"><?php print array_get($slide, 'name'); ?></h3>

                        <p class="lead-2 mw-big-team-bio"><?php print array_get($slide, 'bio'); ?></p>

                        <p class="lead-2 mb-4"><?php print array_get($slide, 'role'); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
