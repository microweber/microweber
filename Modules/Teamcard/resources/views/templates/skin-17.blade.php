<?php
/*

type: layout

name: Skin-17

description: Skin-17

*/
?>

<script>
    $(document).ready(function () {
        $(".mw-big-team-bio").each(function (i) {
            var len = $(this).text().trim().length;
            if (len > 100) {
                $(this).text($(this).text().substr(0, 120) + '...');
            }
        });
    });
</script>


<?php if (isset($data) and $data): ?>

    <div class="row">
        <?php foreach ($data as $key => $slide): ?>
            <div class="col-xxl-3 col-lg-4 col-lg-6 col-12">
                <div class="team-member">
                    <div class="main-content">

                        <?php if ($slide['file']) { ?>
                            <img loading="lazy" src="<?php print thumbnail($slide['file'], 800); ?>"/>
                        <?php } else { ?>
                            <img loading="lazy"
                                 src="<?php print template_url() ?>modules/teamcard/templates/default-image.svg"/>
                        <?php } ?>
                        <span class="category"><?php print array_get($slide, 'role'); ?></span>
                        <h4><?php print array_get($slide, 'name'); ?></h4>
                        <p class="mw-big-team-bio"><?php print array_get($slide, 'bio'); ?></p>


                        <div class="social-icons">
                            <module type="social_links" id="teamcard-socials-{{ $params['id'] }}"
                                    template="skin-1"/>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
<?php endif; ?>
