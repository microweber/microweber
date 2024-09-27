<?php

/*

type: layout

name: Text block 7

position: 7

categories: Text block

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-text-block-skin-7-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row d-flex justify-content-center justify-content-md-between">
            <div class="col-12 col-sm-10 col-md-6 col-lg-5 mb-4">
                <div class="allow-drop">
                    <h3>Like anything else, can go from the simple to the very complex. To gaze at the moon with the naked eye, making yourself.</h3>
                </div>
            </div>

            <div class="col-12 col-sm-10 col-md-6 col-lg-6 mb-4">
                <div class="allow-drop">
                    <p>When you enter into any new area of science, you almost always find yourself with a baffling new language of technical terms to learn before you can converse with the experts.</p>

                    <p>This is certainly true in astronomy both in terms of terms that refer to the cosmos and terms that describe the tools of the trade, the most prevalent being the telescope. So to get us off of first base, let’s define some of the key terms that pertain to telescopes to help you be able to talk to them more intelligently. </p>
                </div>
            </div>
        </div>
    </div>
</section>
