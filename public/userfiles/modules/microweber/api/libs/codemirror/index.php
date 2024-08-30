<?php

    header('Content-Type: application/javascript');



    $cm = array(
        'codemirror.min.js',
        'css.min.js',
        'xml.js',
        'javascript.js',
        'css.js',
        'vbscript.js',
        'htmlmixed.min.js',
        'php.min.js',
        'autorefresh.js',
        'selection-pointer.js',
        'xml-fold.js',
        'matchtags.js',
        'beautify.min.js',
        'beautify-css.min.js',
        'beautify-html.min.js',
    );

    foreach ($cm as $script) {
        $file = __DIR__ . DIRECTORY_SEPARATOR  . $script;
        if(file_exists($file)) {
            include $file;
        } else {
            // print ';console.log("'.$file.'");';
        }
        print "\r\n";

    }


?>

