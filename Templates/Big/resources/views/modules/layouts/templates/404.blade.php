<?php

/*

type: layout

name: 404

position: 10

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = 'p-t-50';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = 'p-b-50';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>

<section class="section {{ $layout_classes }} edit safe-mode" field="layout-404-{{ $params['id'] }}" rel="module">
    <div class="container">
        <div class="row">
            <div class="not_found_text col-4 align-self-center element">
                <h1>{{ _lang("Oops") }}!</h1>
                <p class="my-3">{{ _lang("A 404 error is a standard HTTP error message code that means the website you were trying to reach couldn't be found on the server") }}.</p>
                <module type="btn" button_size="px-6" button_text="Go back"/>
            </div>

            <div class="col-8 text-center not_found_img">
                <img loading="lazy" src="{{ asset('templates/big/img/sections/404_graphic.png') }}" alt=""/>
            </div>
        </div>
    </div>
</section>
