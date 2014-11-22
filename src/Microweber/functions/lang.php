<?php


function _e($text, $to_print = true, $namespace = 'microweber')
{


    $slug = Str::slug($text);
    $ns = Str::slug($namespace);

    $translated = Lang::get($ns . '.' . $slug);

    if (!$translated) {
        $translated = $text;
    }
    if ($to_print) {
        print $text;
    } else {
        return $text;
    }

}