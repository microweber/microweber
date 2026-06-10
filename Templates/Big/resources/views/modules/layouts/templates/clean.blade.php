<?php

/*

type: layout

name: Clean

position: 10

categories: Content

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = 'pb-0';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<section class="section <?php print $layout_classes; ?> ">
    <div class="edit " field="layout-content-{{ $params['id'] }}" rel="module">

        <div class="my-md-5 my-3 container ">
            <div class="row">
                <div class="col-12 mx-auto allow-select allow-drop" style="min-height: 50px">
                    <h2 class="my-md-5 my-3">My title</h2>
                    <p>
                        My text content.
                    </p>
                </div>
            </div>
        </div>
    </div>

</section>

