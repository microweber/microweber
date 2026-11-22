<?php

return [
    'name' => 'Plain',
    // A deliberately minimal template: a Bootstrap-compatible grid + a small set of
    // layout utilities and a bare reset, with NO brand colours, typography scale or
    // component styling. The design comes entirely from the AI agent's GLOBAL
    // apply_css (and Live-Edit edits), so a page built on Plain starts from a clean
    // slate with nothing fighting the applied design.
    'icon' => asset('templates/plain/img/icon.svg'),

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
