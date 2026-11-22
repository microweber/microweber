<?php

return [
    'name' => 'Bootstrap',
    'icon' => asset('templates/bootstrap/img/icon.svg'),

    // Icon libraries this template ships. Loaded as a single cached CSS file
    // (see MicroweberPackages\Template\Adapters\TemplateIconFonts) instead of
    // per-set AJAX. Each entry is a registry key (iconsMindLine, iconsMindSolid,
    // fontAwesome, materialDesignIcons, materialIcons) or a full inline set def.
    // Users can override/extend at runtime via set_option('icon_sets', [...], 'template').
    'icon_sets' => [
        'iconsMindLine',
        'iconsMindSolid',
        'fontAwesome',
        'materialDesignIcons',
    ],
];
