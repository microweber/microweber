<?php


$language_content_saved = false;
function __store_lang_file($lang = 'en'){
    global $language_content;
    global $language_content_saved;
    if($language_content_saved == true){
        return;
    }

    if($lang == false or $lang == ''){
        $lang = 'en';
    }




    $lang_file = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.php';

    if(isarr($language_content)){
        $language_content = array_unique($language_content);

        $lang_file_str = '<?php '. "\n";
        $lang_file_str .= ' $language=array();'."\n";
        foreach ($language_content as $key => $value) {

            $value = addslashes( $value);
            $lang_file_str .= '$language["' . $key . '"]' . "= '{$value}' ; \n";

        }
        $language_content_saved = 1;
        if (is_admin() == true) {
            file_put_contents($lang_file, $lang_file_str);
        }
    }

}


$language_content = array();
function _e($k, $to_return = false) {
    global $language_content;
    static $lang_file;

    //$k = str_replace(' ', '-', $k);
    $k1 = URLify::filter(($k));
    if (isset($_SESSION)){
      $lang = session_get('lang');
  }

  if(!isset($lang) or $lang == false){
    if(isset($_COOKIE['lang'])){
        $lang = $_COOKIE['lang'];
    }
}

        $def_language = get_option('language','website');



if(!isset($lang) and $def_language == false){
    $lang = 'en';
} else {

 $lang = $def_language;

}

if(!defined('MW_LANG') and isset($lang)){
    define('MW_LANG',$lang);
}


//	$k1 = url_title($k);
if ($language_content === NULL or !is_array($language_content) or empty($language_content)) {
    if ($lang_file === NULL) {
        if (!isset($_SESSION) or session_get('lang') == 'en') {
            $lang = 'en';
        } elseif( $lang != false){

        }else {
            $lang = session_get('lang');

        }

        $lang = str_replace('..', '', $lang);
        if (trim($lang) == '') {
            $lang = 'en';
        }
        $lang_file = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.php';
        $lang_file = normalize_path($lang_file, false);

        $cust_lang = $lang_file = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR.'custom'.DIRECTORY_SEPARATOR . $lang . '.php';


        if (is_file($lang_file)) {
            include_once ($lang_file);
        } else {
                // if (is_admin() == true) {
                //     $b = '<?php ' . "\n " . '$language' . " = array(); \n";
                //   // @file_put_contents($lang_file, $b);
                // }
                // $lang_file = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en.php';
                // $lang_file = normalize_path($lang_file, false);
                // include_once ($lang_file);
        }
        if(isset($language) and isarr($language)){
            $language_content = $language;
        }

        if( (isset($language) and isarr($language)) and isset( $cust_lang ) and is_file( $cust_lang )){

include_once ($cust_lang);
$language_content2 = $language;

if(!empty($language_content2)){
    foreach ($language_content2 as $key => $value) {
        $language_content[$key] = $value;
    }
}



        }



    }
} else {

}







if (isset($language_content[$k1]) == false) {
    if (is_admin() == true) {
        $k2 = addslashes($k);
        $language_content[$k1] = $k2;
        $b = '$language["' . $k1 . '"]' . "= '{$k2}' ; \n";



        $scheduler = new \mw\utils\Events();
                    // schedule a global scope function:
        $scheduler -> registerShutdownEvent("__store_lang_file",$lang);


            //@file_put_contents($lang_file, $b, FILE_APPEND);
    }
    if($to_return == true ){
     return   $k;
 }
 print $k;
} else {
  if($to_return == true ){
     return   $language_content[$k1];
 }
 print $language_content[$k1];
}
}

function set_language($lang = 'en') {

    session_set('lang', $lang);
    return $lang;
}





api_expose('save_language_file_content');
function save_language_file_content($data){
    if (is_admin() == true) {
         if(isset($data['unicode_temp_remove'])){
            unset($data['unicode_temp_remove']);
        }



 $def_language = get_option('language','website');
    if(!isset($lang) and $def_language == false){
        $lang = 'en';
    } else {

     $lang = $def_language;

    }







$cust_dir = $lang_file = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR.'custom'.DIRECTORY_SEPARATOR;
if(!is_dir($cust_dir)){
    mkdir_recursive($cust_dir);
}

$language_content = $data;

    $lang_file = $cust_dir . $lang . '.php';

    if(isarr($language_content)){
        $language_content = array_unique($language_content);

        $lang_file_str = '<?php '. "\n";
        $lang_file_str .= ' $language=array();'."\n";
        foreach ($language_content as $key => $value) {

            $value = addslashes( $value);
            $lang_file_str .= '$language["' . $key . '"]' . "= '{$value}' ; \n";

        }
        $language_content_saved = 1;
        if (is_admin() == true) {
            file_put_contents($lang_file, $lang_file_str);
        }
    }
return array('success' => 'Language file [' . $lang . '] is updated');





















}


}

function get_language_file_content(){

    $def_language = get_option('language','website');
    if(!isset($lang) and $def_language == false){
        $lang = 'en';
    } else {

     $lang = $def_language;

    }

    $lang_file = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $lang . '.php';
        $lang_file = normalize_path($lang_file, false);


        if (is_file($lang_file)) {
            include ($lang_file);



  if(isset($language) and isarr($language)){
           return $language;
        }





        }

 }




$mw_all_langs = array();
function get_available_languages(){
global $mw_all_langs;

if(!empty($mw_all_langs)){
    return $mw_all_langs;
}

  $lang_dir = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR;



    $files = array();
    $directory = opendir($lang_dir);
    while($item = readdir($directory)){




          if(($item != ".") && ($item != "..") && ($item != ".svn") ){
            $item = no_ext($item);
            if(trim($item != "")){
              $mw_all_langs[] = $item;
          }
         }
    }

    return $mw_all_langs;

}



function show_help($section = 'main'){
    $lang = 'en';
        if (!isset($_SESSION) or session_get('lang') == 'en') {
            $lang = 'en';
        } elseif( $lang != false){

        }else {
            $lang = session_get('lang');

        }

        $lang = str_replace('..', '', $lang);
        if (trim($lang) == '') {
            $lang = 'en';
        }



    $lang_file = MW_APPPATH_FULL . 'functions' . DIRECTORY_SEPARATOR . 'help' . DIRECTORY_SEPARATOR . $lang . '.php';
    $lang_file = normalize_path($lang_file, false);

    if (is_file($lang_file)) {
        include($lang_file);
    }

}