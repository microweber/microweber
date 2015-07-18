<?php

current_lang();

api_expose_admin('multilang_set_default');
function multilang_set_default() {
  $lang = app('request')->input('lang');
  if(strlen($lang) && in_array($lang, multilang_locales())) {
    Config::set('app.fallback_locale', $lang);
    Config::save();
  }
}

api_expose_admin('multilang_add');
function multilang_add() {
  $lang = app('request')->input('lang');
  if($lang) {
    $langs = multilang_locales();
    if(!array_key_exists($lang, $langs)) {
      $langs[] = $lang;
      save_option([
        'option_key' => 'multilang_locales',
        'option_value' => implode(';', $langs),
        'option_group' => 'website'
      ]);
    }
  }
}

api_expose_admin('multilang_remove');
function multilang_remove() {
  dd(app('request')->input('lang'));
}

api_expose('multilang_locales');
function multilang_locales() {
  $langs = get_option('multilang_locales', 'website');
  if($langs) $langs = explode(';', $langs);
  if(!is_array($langs) || !count($langs)) $langs = array(App::getLocale());
  return $langs;
}

require_once 'laravel/Processor.php';
require_once 'laravel/Translator.php';
