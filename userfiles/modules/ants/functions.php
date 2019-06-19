<?php

function antzz()
{
    exit('antzz is here!!!');
}


event_bind('mw.front', function($content){
    $content['title'] = 'Whats up';
    return $content;
});