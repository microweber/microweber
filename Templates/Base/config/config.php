<?php

return [
    'name' => 'Base',

    // Icon libraries this template ships. Loaded as a single cached CSS file
    // (see MicroweberPackages\Template\Adapters\TemplateIconFonts) instead of
    // per-set AJAX. Registry keys or full inline set defs; override at runtime
    // via set_option('icon_sets', [...], 'template').
    'icon_sets' => [
        'iconsMindLine',
        'iconsMindSolid',
        'fontAwesome',
        'materialDesignIcons',
    ],
];