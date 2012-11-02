<?php

function _ui($type, $attrs, $html) {

    $html = isset($html) ? $html : '';

    switch ($type):
        case 'checkbox':
            return '<label class="mw-ui-check"><input type="checkbox" '.$attrs.' /><span></span></label>';
        case 'radio':
            return '<label class="mw-ui-check"><input type="checkbox" '.$attrs.' /><span></span></label>';




        default:
        return '';
    endswitch;
}


function ui($a,$b,$c){
  $k = isset($c) ? $c : '';
  print _ui($a,$b,$k);
}
?>