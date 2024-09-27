<?php
/*

type: layout

name: Skin-4

description: Skin-4

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
    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center">
        <?php foreach ($data as $key => $slide): ?>
            <div class="col-sm-6 col-md-4 col-lg-4 mb-8 cloneable">
                <div class="d-block position-relative show-on-hover-root">
                    <div class="img-as-background   mh-400 mb-3">
                        <img src="<?php print thumbnail($slide['file'], 800); ?>"/>
                    </div>

                    <div class="show-on-hover position-absolute bg-body border border-color-primary   mh-400 w-100 top-0 mb-3 p-5">
                        <i class="mdi mdi-format-quote-close icon-size-46px  "></i>
                        <p class="lead-2 mw-big-team-bio"><?php print array_get($slide, 'bio'); ?></p>
                    </div>

                    <div class="allow-drop">
                        <h5 class="mb-1"><?php print array_get($slide, 'name'); ?></h5>
                        <p class="mb-3"><?php print array_get($slide, 'role'); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
