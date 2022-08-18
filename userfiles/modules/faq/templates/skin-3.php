<?php

/*

type: layout

name: FAQ Skin 3

description: FAQ Skin 3

*/
?>

<script>
    $(document).ready(function() {

        function toggleChevron(e) {
            $(e.target)
                .prev('.card-header')
                .find("i.mdi.arrow.sk2")
                .toggleClass('mdi-chevron-down mdi-chevron-up')
                .toggleClass('active')
        }
        $('#accordion-faq-default-skin-3').on('hidden.bs.collapse', toggleChevron);
        $('#accordion-faq-default-skin-3').on('shown.bs.collapse', toggleChevron);

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
    }

    .collapse.show, .faq-not-collapsed {
        background-color: #f5f9f8;
    }

    .collapse.show .faq-skin-3-answer, .faq-skin-3-title, .faq-skin-3-question {
        color: #003800;
    }

    .faq-not-collapsed.collapsed {
        background-color: unset!important;
    }

</style>

<div class="row">
    <div class="col-xs-12 edit ms-3 mb-3" rel="module-<?php print $params['id']; ?>" field="title-content">
        <h3 class="faq-skin-3-title">Frequently asked questions</h3>
    </div>
</div>
<div class="accordion" id="accordion-faq-default-skin-3">
    <?php foreach ($data as $key => $slide) : ?>

        <?php
        $edit_field_key = $key;
        if (isset($slide['id'])) {
            $edit_field_key = $slide['id'];
        }

        ?>

        <div class="card faq-skin-2 sk2 card-collapse mb-3 <?php if ($key == 0) : ?> active <?php endif; ?>">
            <div class="card-header p-0" id="header-item-<?php print $edit_field_key ?>">
                <button class="btn w-100 faq-not-collapsed collapsed" data-bs-toggle="collapse" data-bs-target="#collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>" aria-expanded="true" aria-controls="collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>">
                    <?php //module icon -
                    //print isset($slide['icon']) ? $slide['icon'] . ' ' : '';
                    ?>
                    <h5 class="faq-skin-3-question"> <?php print isset($slide['question']) ? $slide['question'] : ''; ?> </h5>
                    <i class="mdi arrow sk2 ms-2 <?php if ($key == 0) : ?>mdi-chevron-down<?php else : ?>mdi-chevron-down<?php endif; ?>"></i>
                </button>
            </div>


            <div class="collapse allow-drop"   id="collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>" class="collapse <?php if ($key == 0) : ?> show <?php endif; ?>" aria-labelledby="header-item-<?php print $edit_field_key ?>" data-parent="#mw-accordion-module-<?php print $params['id'] ?>">
                <div class="card-body ">
                    <div class="allow-drop">
                        <div class="element">
                            <p class="faq-skin-3-answer"> <?php print isset($slide['answer']) ? $slide['answer'] : 'FAQ Answer' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


