<?php

/*

  type: layout

  name: default

  description: Default template


*/

?>
<?php
if ($json == false) {
    print lnotif(_e('Click to edit accordion', true));

    return;
}

if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}
?>

<script>
    $(document).ready(function() {

        function toggleChevron(e) {
            $(e.target)
                .prev('.card-header')
                .find("i.mdi.arrow")
                .toggleClass('mdi-chevron-down mdi-chevron-up')
                .toggleClass('active')
        }
        $('#accordion').on('hidden.bs.collapse', toggleChevron);
        $('#accordion').on('shown.bs.collapse', toggleChevron);

        $(".card").click(function() {
            $(".card").removeClass("active");
            $(this).addClass("active");
        });

    })
</script>
<?php

/*
<style>
    .card:hover,
    .card.active {
        border: 1px #0055ff solid !important;
    }

    .card i.active {
        background-color: #0055ff !important;
        color: white !important;
    }

    .card {
        border: 1px solid #e6e6e6 !important;
        border-radius: 5px !important;
    }

    .mdi.arrow {
        background-color: #f3f3f3;
        color: gray;
        line-height: 1 !important;
        font-size: 20px !important;
    }

    .card.card-collapse .card-header:after {
        border-top: none !important;
    }
</style>*/

?>

<div id="mw-accordion-module-<?php print $params['id'] ?>">
    <div class="accordion" id="accordion">

        <?php foreach ($json as $key => $slide) : ?>
            <?php
            $edit_field_key = $key;
            if (isset($slide['id'])) {
                $edit_field_key = $slide['id'];
            }

            ?>
            <div class="card card-collapse mb-3 <?php if ($key == 0) : ?> active <?php endif; ?>">
                <div class="card-header p-0" id="header-item-<?php print $edit_field_key ?>">
                    <button class="btn p-5 w-100" data-bs-toggle="collapse" data-bs-target="#collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>" aria-expanded="true" aria-controls="collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>">
                        <?php //module icon -
                        //print isset($slide['icon']) ? $slide['icon'] . ' ' : '';
                        ?>
                        <h4> <?php print isset($slide['title']) ? $slide['title'] : ''; ?> </h4>
                        <i class="mdi arrow border rounded-circle ml-auto mr-0 <?php if ($key == 0) : ?>mdi-chevron-up active<?php else : ?>mdi-chevron-down<?php endif; ?>"></i>
                    </button>
                </div>
                <div id="collapse-accordion-item-<?php print $edit_field_key . '-' . $key ?>" class="collapse <?php if ($key == 0) : ?> show <?php endif; ?>" aria-labelledby="header-item-<?php print $edit_field_key ?>" data-parent="#mw-accordion-module-<?php print $params['id'] ?>">
                    <div class="card-body px-5 pt-0 pb-5">
                        <div class="allow-drop" field="accordion-item-<?php print $edit_field_key ?>" rel="module-<?php print $params['id'] ?>">
                            <div class="element">
                                <p class="lead text-black"> <?php print isset($slide['content']) ? $slide['content'] : 'Accordion content' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
