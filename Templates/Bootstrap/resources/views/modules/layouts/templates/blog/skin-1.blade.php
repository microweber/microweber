<?php

/*

type: layout

name: Blog 1

categories: blog

position: 1

categories: Blog

*/

?>

@component('templates.bootstrap::partials.layout-section', [
    'params'         => $params,
    'classes'        => $classes,
    'layout_classes' => $layout_classes ?? '',
    'sectionClass'   => 'section',
    'fieldName'      => 'layout-blog-skin-1',
    'noDrop'         => true,
])
    <module type="posts" id="{{ $params['id'] }}-posts" template="skin-1" slides-md="2" slides-lg="3" adaptive_height="true" />
@endcomponent
