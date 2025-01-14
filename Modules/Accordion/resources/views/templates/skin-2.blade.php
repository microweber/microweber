<?php

/*

  type: layout

  name: skin-2

  description: Skin-2 template

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
                .find("i.mdi.arrow.sk2")
                .toggleClass('mdi-chevron-down mdi-chevron-up')
                .toggleClass('active')
        }
        $('#accordion-sk2').on('hidden.bs.collapse', toggleChevron);
        $('#accordion-sk2').on('shown.bs.collapse', toggleChevron);

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
    color: #fff;
    line-height: 1 !important;
    font-size: 20px !important;
}

.card.sk1:hover,
.card.active.sk2 {
    border: 1px var(--primaryColor) solid !important;
}

.card i.active.sk2 {
    background-color: var(--primaryColor) !important;
    color: white !important;
}

.mdi.arrow.sk2 {
    background-color: #f3f3f3;
    color: gray;
    line-height: 1 !important;
    font-size: 20px !important;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: auto;
}
</style>

<div id="mw-accordion-module-{{ $params['id'] }}">
    <div class="accordion" id="accordion-sk2">

        <?php foreach ($json as $key => $slide) : ?>
            <?php
            $edit_field_key = $key;
            if (isset($slide['id'])) {
                $edit_field_key = $slide['id'];
            }

            ?>
            <div class="card sk2 card-collapse border mb-3 <?php if ($key == 0) : ?> active <?php endif; ?>">
                <div class="card-header p-0" id="header-item-{{ $edit_field_key }}">
                    <button class="btn w-100 rounded-0 btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#collapse-accordion-item-{{ $edit_field_key . '-' . $key }}" aria-expanded="true" aria-controls="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}">
                        {{ isset($slide['icon']) ? $slide['icon'] . ' ' : '' }}
                        <h4> {{ isset($slide['title']) ? $slide['title'] : '' }} </h4>
                        <i class="mdi arrow sk2 rounded-circle <?php if ($key == 0) : ?>mdi-chevron-down<?php else : ?>mdi-chevron-down<?php endif; ?>"></i>
                    </button>
                </div>
                <div id="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}" class="collapse <?php if ($key == 0) : ?> show <?php endif; ?>" aria-labelledby="header-item-{{ $edit_field_key }}" data-parent="#mw-accordion-module-{{ $params['id'] }}">
                    <div class="card-body p-5">
                        @include('modules.accordion.templates.partials.render_accordion_item_content')

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
