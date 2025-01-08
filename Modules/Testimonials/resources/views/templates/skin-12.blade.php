<?php

/*

type: layout

name: Skin-12

description: Skin-12

*/

?>

<?php

$rand = uniqid();
$limit = 40;

?>


<script>mw.lib.require('slick')</script>
<script>
    $(document).ready(function () {
        $('.slickslider', '#<?php echo $params['id']; ?>').slick();
    });
</script>

<style>
    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-track {
        display: flex !important;
    }

    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-dots {
        position: relative;
        bottom: -30px;
    }

    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-slide {
        height: inherit !important;
    }

    <?php echo '#' . $params['id'] . ' '; ?>
    .slick-list {
        overflow: hidden;
    }

</style>


<div class="slick-arrows-1">
    <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        <?php foreach ($testimonials as $item): ?>
            <div class="row text-center">
                <div class="col-11 col-sm-10 col-lg-8 col-lg-5 mx-auto">
                    <div class="border testimonials-background-variable testimonialBorderVariable  hover-border-color-primary   mx-3 h-100 p-5">
                        <?php if ($item['client_picture']): ?>
                            <div class="w-80 mx-auto my-4">
                                <div class="img-as-background rounded-circle square">
                                    <img loading="lazy" src="<?php print thumbnail($item['client_picture'], 120); ?>" class="d-block"/>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($item['name']): ?>
                            <h5 class=" mb-0"><?php print $item['name']; ?></h5>
                        <?php endif; ?>
                        <?php if ($item['client_company']): ?>
                            <p class="mb-0"><?php print $item['client_company']; ?></p>
                        <?php endif; ?>

                        <?php if ($item['client_website']): ?>
                            <a class="my-1 d-block" href="<?php print $item['client_website']; ?>"><?php print $item['client_website'] ?></a>
                        <?php endif; ?>

                        <?php if ($item['client_role']): ?>
                            <p><?php print $item['client_role']; ?></p>
                        <?php endif; ?>

                        <i class="mdi mdi-format-quote-close icon-size-46px   my-3 d-block safe-element"></i>

                        <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
