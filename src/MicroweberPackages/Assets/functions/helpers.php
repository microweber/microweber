<?php

function assets_all($group = false, array $attributes = []) {
    return app()->assets->all($group, $attributes);
}

function assets_add($asset, $group = false) {

    $type = \MicroweberPackages\Assets\Assets::TYPE_AUTO;

    return app()->assets->add($asset, $type, $group);
}
