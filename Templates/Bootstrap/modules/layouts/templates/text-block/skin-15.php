<?php

/*

type: layout

name: Text block 15

position: 15

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-text-block-skin-15-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <div class="row d-flex justify-content-center justify-content-md-between border-top border-bottom py-8 cloneable">
            <div class="col-4">
                <div class="allow-drop">
                    <h6><span>1.</span> Is it easy to build a website?</h6>
                </div>
            </div>

            <div class="col-8">
                <div class="allow-drop">
                    <p class="mb-0">When you enter into any new area of science, you almost always find yourself with a baffling new language of technical terms to learn before you can converse with the experts. When you enter into any new area of science, you almost always find yourself with a baffling new language of technical terms to learn before you can converse with the experts. When you enter into any new area of science, you almost always find yourself with a baffling new language of technical terms to learn before you can converse with the experts. When you enter into any new area of science, you almost always find yourself with a baffling new language of technical terms to learn before you can converse with the experts.</p>
                </div>
            </div>
        </div>
    </div>
</section>
