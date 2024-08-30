<?php

/*

type: layout

name: FAQ Skin 2

description: FAQ Skin 2

*/
?>

<script>
    $(document).ready(function() {

        function toggleChevron(e) {
            $(e.target)
                .prev('.card-header')
                .find("i.mdi.arrow.sk2")
                .toggleClass('mdi-minus mdi-plus')
                .toggleClass('active')
        }
        $('#accordion-faq-default-skin-2').on('hidden.bs.collapse', toggleChevron);
        $('#accordion-faq-default-skin-2').on('shown.bs.collapse', toggleChevron);

        $(".card.sk2").click(function() {
            $(".card.sk2").removeClass("active");
            $(this).addClass("active");
        });

    })
</script>

<style>

    .card.faq-skin-2 .card-header:after {
        border: none!important;
    }


    .card.sk2 {
        border: none;
    }

    .mdi.arrow.sk2 {
        color: #676767;
        line-height: 1 !important;
        font-size: 20px !important;
        border: 2px solid #676767;
        border-radius: 50%;
    }

    .faq-default-answer {
        color: #7d7d7d;
        font-weight:
    }
</style>

<div class="row text-center mb-5">
    <div class="col-xs-12 edit " rel="module-<?php print $params['id']; ?>" field="title-content">
        <h2>Frequently asked questions</h2>
        <p class="faq-default-answer lead mt-4">Have questions? We're here to help.</p>
    </div>
</div>
<div class="accordion" id="accordion-faq-default-skin-2">
    <?php foreach ($data as $key => $slide) : ?>

        <?php
        $edit_field_key = $key;
        if (isset($slide['id'])) {
            $edit_field_key = $slide['id'];
        }

        ?>

        <div class="card faq-skin-2 sk2 card-collapse mb-3 <?php if ($key == 0) : ?> active <?php endif; ?>">
            <div class="card-header p-0" id="header-item-<?php print $edit_field_key ?>">
                <button class="btn p-5 w-100 d-flex" data-bs-toggle="collapse" data-bs-target="#collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>" aria-expanded="true" aria-controls="collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>">
                    <?php //module icon -
                    //print isset($slide['icon']) ? $slide['icon'] . ' ' : '';
                    ?>
                    <h4> <?php print isset($slide['question']) ? $slide['question'] : ''; ?> </h4>
                    <i class="mdi arrow sk2 ms-auto me-0 <?php if ($key == 0) : ?>mdi-minus<?php else : ?>mdi-minus<?php endif; ?>"></i>
                </button>
            </div>


            <div class="collapse allow-drop"   id="collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>" class="collapse <?php if ($key == 0) : ?> show <?php endif; ?>" aria-labelledby="header-item-<?php print $edit_field_key ?>" data-parent="#mw-accordion-module-<?php print $params['id'] ?>">
                <div class="card-body px-5 py-5 ">
                    <?php include modules_path() . 'faq/templates/partials/render_faq_item_content.php'; ?>

                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


