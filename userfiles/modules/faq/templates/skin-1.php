<?php

/*

type: layout

name: FAQ Skin 1

description: FAQ Skin 1

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
        $('#accordion-faq-default-skin-1').on('hidden.bs.collapse', toggleChevron);
        $('#accordion-faq-default-skin-1').on('shown.bs.collapse', toggleChevron);

        $(".card.sk2").click(function() {
            $(".card.sk2").removeClass("active");
            $(this).addClass("active");
        });

    })
</script>

<style>
    .card.sk2 {
        border: none;
    }

    .mdi.arrow.sk2 {
        color: gray;
        line-height: 1 !important;
        font-size: 20px !important;
    }
</style>

<div class="row text-center mb-5">
    <div class="col-xs-12 edit " rel="module-<?php print $params['id']; ?>" field="title-content">
        <h3>Here’s a few answers to our most common questionsss</h3>
    </div>
</div>
<div class="accordion" id="accordion-faq-default-skin-1">
    <?php foreach ($data as $key => $slide) : ?>

        <?php
        $edit_field_key = $key;
        if (isset($slide['id'])) {
            $edit_field_key = $slide['id'];
        }

        ?>

        <div class="card sk2 card-collapse border mb-3 <?php if ($key == 0) : ?> active <?php endif; ?>">
            <div class="card-header p-0" id="header-item-<?php print $edit_field_key ?>">
                <button class="btn p-5 w-100" data-bs-toggle="collapse" data-bs-target="#collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>" aria-expanded="true" aria-controls="collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>">
                    <?php //module icon -
                    //print isset($slide['icon']) ? $slide['icon'] . ' ' : '';
                    ?>
                    <h4> <?php print isset($slide['question']) ? $slide['question'] : ''; ?> </h4>
                    <i class="mdi arrow sk2 ms-auto me-0 <?php if ($key == 0) : ?>mdi-chevron-down<?php else : ?>mdi-chevron-down<?php endif; ?>"></i>
                </button>
            </div>


            <div class="collapse allow-drop"   id="collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>" class="collapse <?php if ($key == 0) : ?> show <?php endif; ?>" aria-labelledby="header-item-<?php print $edit_field_key ?>" data-parent="#mw-accordion-module-<?php print $params['id'] ?>">
                <div class="card-body px-5 py-5 ">
                    <div class="allow-drop">
                        <div class="element">
                            <p class="lead text-black"> <?php print isset($slide['answer']) ? $slide['answer'] : 'FAQ Answer' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


