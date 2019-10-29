<?php
event_bind('mw.front', function () {
    $css = '
        <style>
        .js-microweber-add-iframe {
            background: #fff;
            z-index: 99999;
            padding: 7px;
            min-height: 20px;
            position: absolute;
            height: 54px;
            border: 0;
            left: 0;
            right: 0;
            top: 0;
            width: 100%;
            overflow: hidden;
           border-bottom: 1px solid #f1f3f4;
           color: #2d2d2d;
        }
        
        .js-microweber-add-iframe .row{
            display: flex;
            justify-content: center;
            align-items: center;
        }    
        
        .js-microweber-add-iframe .row .col{
           width: 50%;
        }
        body {
            padding-top:20px;
        }
        </style>
    ';

    $iframe_content = '<div class="row">
    <div class="col"><p>Този уебсайт е направен с <strong>Microweber.bg</strong> сайт билдър</p></div>
    <div class="col"><a href="https://microweber.bg" target="_blank">Направи си сайт</a></div>
    </div>';

    mw()->template->foot($css . '<div class="js-microweber-add-iframe" src="">' . $iframe_content . '</div>');
});