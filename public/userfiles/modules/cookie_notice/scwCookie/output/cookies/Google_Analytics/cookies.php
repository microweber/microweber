<?php
return [
    'defaults' => [
        'path'   => '/',
        'domain' => '.'.str_replace('www.', '', $_SERVER['HTTP_HOST']),
    ],
    ['name' => '_ga'],
    ['name' => '_gid'],
    ['name' => '_gat'],
    ['name' => '_gat_gtag_'.str_replace('-', '_', $this->getConfig('Google_Analytics', 'code'))],
    ['name' => '__utma'],
    ['name' => '__utmt'],
    ['name' => '__utmb'],
    ['name' => '__utmc'],
    ['name' => '__utmz'],
    ['name' => '__utmv'],
    ['name' => '__utmx'],
    ['name' => '__utmxx'],
    ['name' => '_gaexp'],
    ['name' => '_utm.gif'],
];
