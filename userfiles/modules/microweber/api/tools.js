

mw.tools = {};


<?php
    $files = glob(__DIR__.DS.'tools'.DS.'*.js');



foreach($files as $file) {
 //   print("mw.require('".mw_includes_url()."api/tools/".basename($file)."');");
    echo "\n";
    print("mw.required.push('".mw_includes_url()."api/tools/".basename($file)."');");
    echo "\n";
    echo "\n";
    include $file;
    echo "\n";
}


echo "\n";
echo "\n";


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
mw.tooltip = function (config) {
    return mw.tools.tooltip.init(config);
};

mw.gallery = function (arr, start, modal) {
    return mw.tools.gallery.init(arr, start, modal)
};



