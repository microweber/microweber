<?php



event_bind('on_load',function(){
    \MicroweberPackages\Assets\Facades\Assets::add('antzzzzzzzzzzzz.js','css' );

});

event_bind('frontend',function(){
    \MicroweberPackages\Assets\Facades\Assets::add('frontend.js','js' );

});



function antzz()
{
    exit('antzz is here!!!');
}

