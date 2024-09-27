<?php

/*

type: layout

name: Features 2

position: 2

categories: Features

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>


<section class="section <?php print $layout_classes; ?> edit safe-mode   " field="layout-features-skin-2-<?php print $params['id'] ?>" rel="module">
    <div class="container px-4 py-5" id="hanging-icons">
        <h2 class="pb-2 border-bottom">Hanging icons</h2>
        <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
            <div class="col d-flex align-items-start">
                <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                  <a class="mb-2" href=""><i class="mdi mdi-check" style="font-size: 40px;"></i></a>
                </div>
                <div>
                    <h2>Featured title</h2>
                    <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
                     <module type="btn" button_style="btn btn-primary" button_text="Primary button"/>
                </div>
            </div>
            <div class="col d-flex align-items-start">
                <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                    <a class="mb-2" href=""><i class="mdi mdi-check" style="font-size: 40px;"></i></a>
                </div>
                <div>
                    <h2>Featured title</h2>
                    <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
                     <module type="btn" button_style="btn btn-primary" button_text="Primary button"/>
                </div>
            </div>
            <div class="col d-flex align-items-start">
                <div class="icon-square bg-light text-dark flex-shrink-0 me-3">
                     <a class="mb-2" href=""><i class="mdi mdi-check" style="font-size: 40px;"></i></a>
                </div>
                <div>
                    <h2>Featured title</h2>
                    <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
                     <module type="btn" button_style="btn btn-primary" button_text="Primary button"/>
                </div>
            </div>
        </div>
    </div>

</section>
