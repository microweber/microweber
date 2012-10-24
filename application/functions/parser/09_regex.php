<?php

$pattern = '(?P<name>\w+)\s*=\s*((?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))';

#/(?P<name>\w+)\s*=\s*((?P<quote>["\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))/si
$all_edits = array();

if (preg_match_all('%</?[a-z][a-z0-9]*[^<>]*>%i', $layout, $matches)) {
 d($matches);
    foreach ($matches as $k) {



//        foreach ($value as $attr) {
//            if(strstr( $attr,'edit')){
//                d($attr);
//            }
//        }
    }
} else {
    # Match attempt failed
}




