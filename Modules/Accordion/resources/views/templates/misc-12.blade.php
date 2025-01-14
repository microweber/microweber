<?php

/*

  type: layout

  name: Misc-12

  description: Misc-12 template


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

<style>
    .misc-skin-12 .tab-pane .element {
        display: flex;
        align-items: center;


    }

    @media (max-width: 550px) {
        .misc-skin-12 .tab-pane .element {
            flex-wrap: wrap;
        }
    }

</style>

<div class="row" id="mw-accordion-module-{{ $params['id'] }}">
    <div class="col-xl-2 col-lg-4">
        <ul class="nav flex-column" id="accordion-{{ $params['id'] }}" role="tablist">
            <?php foreach ($json as $key => $slide): ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($key == 0): ?>active<?php endif; ?>" id="<?php print $params['id'] . '-' . $key; ?>-tab" data-bs-toggle="tab" href="#tab-<?php print $params['id'] . '-' . $key; ?>" role="tab" aria-controls="home"
                       aria-selected="true"><?php print isset($slide['icon']) ? $slide['icon'] . ' ' : ''; ?><?php print isset($slide['title']) ? $slide['title'] : ''; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="col-xl-10 col-lg-8">
        <div class="tab-content misc-skin-12" id="accordion-{{ $params['id'] }}-content">
            <?php foreach ($json as $key => $slide): ?>

                <?php
                $edit_field_key = $key;
                if (isset($slide['id'])) {
                    $edit_field_key = $slide['id'];
                }

                ?>


                <div class="tab-pane fade <?php if ($key == 0): ?>show active<?php endif; ?>" id="tab-<?php print $params['id'] . '-' . $key; ?>" role="tabpanel" aria-labelledby="<?php print $params['id'] . '-' . $key; ?>-tab">
                    <h4>
                        <?php print isset($slide['title']) ? $slide['title'] : ''; ?>
                    </h4>


                    <?php include modules_path() . 'accordion/templates/partials/render_accordion_item_content.php'; ?>


                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
