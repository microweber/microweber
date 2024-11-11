<?php

/*

type: layout

name: Text block 8

position: 8

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-text-block-skin-8-<?php print $params['id'] ?>" rel="module">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="row">
            <div class="col-12 col-lg-10 col-lg-10 mx-auto">
                <div class="row">
                    <div class="col-md-6 mb-6 allow-drop cloneable">

                        <p class="lead">Arrange your room for optimal picture and sound by reducing screen and hard surface reflections. Do not forget the TV picture is not very pretty when light is reflecting off the screen.</p>
                    </div>

                    <div class="col-md-6 mb-6 allow-drop cloneable">

                        <p class="lead">Arrange your room for optimal picture and sound by reducing screen and hard surface reflections. Do not forget the TV picture is not very pretty when light is reflecting off the screen.</p>
                    </div>

                    <div class="col-md-6 mb-6 allow-drop cloneable">

                        <p class="lead">Arrange your room for optimal picture and sound by reducing screen and hard surface reflections. Do not forget the TV picture is not very pretty when light is reflecting off the screen.</p>
                    </div>

                    <div class="col-md-6 mb-6 allow-drop cloneable">

                        <p class="lead">Arrange your room for optimal picture and sound by reducing screen and hard surface reflections. Do not forget the TV picture is not very pretty when light is reflecting off the screen.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
