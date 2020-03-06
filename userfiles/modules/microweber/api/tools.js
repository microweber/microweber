//mw.require("files.js");
//mw.require("css_parser.js");
//mw.require("components.js");
//mw.require("color.js");
//mw.lib.require("acolorpicker");




mw.tools = {};

mw.require('tools/dropdown.js')

<?php
    $files = glob(__DIR__.DS.'tools'.DS.'*.js');
    foreach($files as $file) {
        include $file;
        //$arr = explode(DS, $file);

        //echo "mw.require('tools/" . array_pop($arr) . "')";
        echo ";\n";
    }
?>


mw.confirm = mw.tools.confirm;
mw.tabs = mw.tools.tabGroup;
mw.progress = mw.tools.progress;
mw.external = function (o) {
    return mw.tools._external(o);
};

mw.gallery = function (arr, start, modal) {

    return mw.tools.gallery.init(arr, start, modal)

};

window.Alert = mw.tools.alert;
mw.tooltip = mw.tools.tip;

mw.gallery = function (arr, start, modal) {
    return mw.tools.gallery.init(arr, start, modal)
};




