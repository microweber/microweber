<?php
return [
    [
        'name'   => '__tawkuuid',
        'domain' => '.'.str_replace('www.', '', $_SERVER['HTTP_HOST'])
    ],
    ['name' => 'TawkConnectionTime'],
    ['name' => 'Tawk_'.$this->getConfig('Tawk.to', 'code')],
];
