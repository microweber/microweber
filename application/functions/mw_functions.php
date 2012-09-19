<?php



include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'api.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'users.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'utils.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'url.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'cache.php');

    include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'db.php');
 
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'content.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'categories.php');

include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'options.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'menus.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'templates.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'media.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'modules.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'history.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'language.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'forms.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'updates.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'fx.php');


// require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'users.php');
// require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'dashboard.php');
// require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'cart.php');
include (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser.php');
// require (APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'forms.php');

$module_functions = get_all_functions_files_for_modules();
if ($module_functions != false) {
    if (is_array($module_functions)) {
        foreach ($module_functions as $item) {
            include ($item);
        }
    }
}
// d($module_functions);