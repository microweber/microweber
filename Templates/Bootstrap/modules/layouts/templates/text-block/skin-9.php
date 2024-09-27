<?php

/*

type: layout

name: Text block 9

position: 9

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-text-block-skin-9-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row d-flex justify-content-center justify-content-md-between">
            <div class="col-12 col-sm-10 col-md-12 col-lg-4 mb-4">
                <div class="allow-drop">
                    <p>Few would argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher</p>
                </div>
            </div>

            <div class="col-12 col-sm-10 col-md-6 col-lg-4 mb-4">
                <div class="allow-drop">
                    <p>Planning to visit Las Vegas or any other vacational resort where casinos are a major portion of their business? I have just the thing for you. Here, I will show you how to pass off as a High Roller and collect many complimentary items and gifts.</p>
                </div>
            </div>

            <div class="col-12 col-sm-10 col-md-6 col-lg-4 mb-4">
                <div class="allow-drop">
                    <p>Can you imagine what we will be downloading in another twenty years? I mean who would have ever thought that you could record sound with digital quality fifty years ago? Now we routinely download whole albums worth of music in a couple of minutes to ipods</p>
                </div>
            </div>
        </div>
    </div>
</section>
