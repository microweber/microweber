<?php

return [

    // --------------------------------------------------------------------------
    // A list of predefined resources.
    // --------------------------------------------------------------------------
    // Predefined groups of resources.  Note that the order of files determines
    // the order in which they are loaded.  Hence each collection should specify
    // its dependencies before its own files.
    'collections' => [
        'bootstrap' => 'bootstrap4',
        'bootstrap3' => [
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js'
        ],
        'bootstrap4' => [
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js'
        ],
        'datatables' => [
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/css/jquery.dataTables.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/js/jquery.dataTables.min.js',
        ],
        'datatables-bootstrap' => [
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/css/dataTables.bootstrap.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/js/jquery.dataTables.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.9/js/dataTables.bootstrap.min.js',
        ],
        'font-awesome' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css',
        'google-maps-api' => 'https://maps.googleapis.com/maps/api/js#.js',
        'smalot-bootstrap-datetimepicker' => [
            'https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.4/css/bootstrap-datetimepicker.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.4/js/bootstrap-datetimepicker.min.js',
        ],
        'jquery' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js',
        'jquery-ui' => [
            'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js',
            'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',

        ],
    ],
];
