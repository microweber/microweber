<?php

/*

type: layout

name: Blog 1

position: 1

categories: Blog

*/

?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-blog-skin-1"
    :no-drop="true"
>
    <module type="posts" template="skin-1" slides-md="2" slides-lg="3" adaptive_height="true" />
</x-layout-section>
