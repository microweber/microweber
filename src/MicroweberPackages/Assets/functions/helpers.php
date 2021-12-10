<?php

function assets_all($group = false, array $attributes = []) {
    return app()->assets->all($group, $attributes);
}

function assets_add($asset, $group = false) {

    $type = \MicroweberPackages\Assets\Assets::TYPE_AUTO;

    return app()->assets->add($asset, $type, $group);
}

function assets_add_js($asset, $group = false) {

    $type = \MicroweberPackages\Assets\Assets::TYPE_JS;

    return app()->assets->add($asset, $type, $group);
}

function assets_add_css($asset, $group = false) {

    $type = \MicroweberPackages\Assets\Assets::TYPE_CSS;

    return app()->assets->add($asset, $type, $group);
}
