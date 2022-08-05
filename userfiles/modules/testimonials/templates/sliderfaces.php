<?php

/*

type: layout

name: Faces

description: Testimonials displayed in Slider with Faces

*/

?>

<script>mw.module_css("<?php print $config['url_to_module'] ?>templates/templates.css", 'testimonials_css', true);</script>
<script>mw.lib.require('slick');</script>

<script>
    $(document).ready(function () {
        var items = $("#<?php print $params['id']; ?> .mwt-face-holder")
        $("#<?php print $params['id']; ?> .mwt-faces").append(items.eq(0).clone(true))
        $("#<?php print $params['id']; ?> .mwt-faces").prepend(items.eq(items.length - 1).clone(true))
        var configFaces = function (nextSlide) {
            nextSlide = nextSlide || 0;
            var active = $("#<?php print $params['id']; ?> .mwt-face-holder")
                .removeClass('active subactive')
                .one('click', function () {
                    $("#<?php print $params['id']; ?> .mw-testimonials-faces").slick('slickGoTo', $(this).attr('data-index'))
                    return false;
                })
                .eq(nextSlide + 1)
                .addClass('active');
            active.prev('.mwt-face-holder').addClass('subactive')
            active.next('.mwt-face-holder').addClass('subactive')
        }
        var el = $("#<?php print $params['id']; ?> .mw-testimonials-faces");
        el.slick({
            infinite: true,
            dots: true,
            arrows: false,
            adaptiveHeight: true,
            rtl: document.documentElement.dir === 'rtl',
        })
            .on('beforeChange init reInit', function (event, slick, currentSlide, nextSlide) {
                configFaces(nextSlide)
            });
        configFaces()
    })
</script>
<?php
$bgImage = get_option('bg-image', $params['id']);
if ($bgImage == false) {
    $wrapperStyle = '';
} else {
    $wrapperStyle = 'background-image: url(' . $bgImage . ');';
}
?>
<div class="testimonials-faces-wrapper" style="<?php print $wrapperStyle; ?>">
    <div class="title-holder edit" rel="testimonials-title-<?php print $params['id']; ?>" field="testimonials-title" id="testimonials-title">
        <h2>Client Testimonials</h2>
    </div>

    <?php if ($all_have_pictures): ?>
        <div class="mwt-faces">
            <?php $count = -1;
            foreach ($data as $item): $count++; ?>
                <span class="mwt-face-holder" data-index="<?php print $count; ?>">
                <?php if (isset($item['client_website'])): ?>
                    <a href="<?php print $item['client_website']; ?>" class="mwt-face" style="background-image: url(<?php print thumbnail($item['client_picture'], 250); ?>);"></a>
                    <a href="<?php print $item['client_website']; ?>" class="mwt-face-name"><?php print $item['name']; ?></a>
                <?php else : ?>
                    <span class="mwt-face" style="background-image: url(<?php print thumbnail($item['client_picture'], 250); ?>);"></span>
                    <span class="mwt-face-name"><?php print $item['name']; ?></span>
                <?php endif; ?>
            </span>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="mwt-faces">
            <?php $count = -1;
            foreach ($data as $item): $count++; ?>
                <span class="mwt-face-holder" data-index="<?php print $count; ?>">
                <?php if (isset($item['client_website'])): ?>
                    <a href="<?php print $item['client_website']; ?>" class="mwt-face-name"><?php print $item['name']; ?></a>
                <?php else : ?>
                    <span class="mwt-face-name"><?php print $item['name']; ?></span>
                <?php endif; ?>
            </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="testimonials-wrapper">
        <div class="mw-testimonials mw-testimonials-faces">
            <?php foreach ($data as $item): ?>
                <div class="mw-testimonials-item-faces">
                    <div class="mw-testimonials-item-faces-content">
                    <span class="mw-testimonials-item-faces-role"><em><?php print $item['client_role']; ?></em> &nbsp;<?php if (isset($item['client_company']) and isset($item['client_role'])) {
                            _e('at');
                        }; ?>&nbsp;<strong><?php print $item['client_company']; ?></strong></span>

                        <?php if (isset($item["project_name"])) { ?>
                            <h5><?php print $item["project_name"]; ?></h5>
                        <?php } ?>

                        <p><span class="faces-laquo"></span><?php print $item['content']; ?><span class="faces-raquo"></span></p>
                        <?php if (isset($item["read_more_url"])) { ?>
                            <div><a href="<?php print $item["read_more_url"]; ?>" target="_blank"><?php _e('Read more'); ?></a></div>
                        <?php } ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="clearfix"></div>
</div>
