<?php

/*

type: layout

name: Default

description: Testimonials Default

*/

?>


<script>mw.module_css("<?php print $config['url_to_module'] ?>templates/templates.css", true);</script>


<script>

    $(window).on('load resize', function () {
        var root = $("#<?php print $params['id']; ?>"), max = 0;
        $('.mw-testimonials-item', root)
            .height('auto')
            .each(function () {
                var height = $(this).height();
                if (height > max) max = height;
            })
            .height(max)
    })
</script>


<div class="testimonial-bordered">
    <div class="owl-carousel text-center owl-testimonial nomargin"
         data-plugin-options='{"singleItem": true, "autoPlay": 4000, "navigation": false, "pagination": true, "transitionStyle":"fade"}'>
        <?php $data = get_testimonials(); ?>

        <?php foreach ($data as $item) { ?>
            <div class="testimonial">
                <figure>
                    <img class="rounded" src="<?php print $item['client_picture']; ?>" alt=""/>
                </figure>
                <div class="testimonial-content nopadding">
                    <p class="lead"><?php print $item['content']; ?></p>
                    <cite>
                        <?php if (isset($item['client_website'])) { ?>
                            <a href="<?php print $item['client_website']; ?>" target="_blank"><?php print $item['name']; ?></a>
                        <?php } else { ?>
                            <h5><?php print $item['name']; ?></h5>
                        <?php } ?>
                        <?php if (isset($item["client_company"])) { ?>
                            <span><?php print $item['client_company']; ?></span>
                        <?php } ?>
                    </cite>
                </div>
            </div>

        <?php } ?>
    </div>
</div>