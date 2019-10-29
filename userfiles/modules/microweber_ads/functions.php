<?php
event_bind('mw.front', function () {
    $css = '
        <style>
        .js-microweber-add-iframe {
            top: 0px;
            background: #fff;
            z-index: 10000000;
            padding: 7px;
            min-height: 20px;
            position: absolute;
            height: 20px;
        }
        body {
            padding-top:20px;
        }
        </style>
    ';

    mw()->template->foot($css . '<iframe class="js-microweber-add-iframe" src=""><center>Create your website with Microweber</center></iframe>');
});