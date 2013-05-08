<?php


/*
 * HTML is the 'root' scanner, we just override a couple of config settings
 * here, to prevent it from looking for CSS or JS.
 */
class LuminousXMLScanner extends LuminousHTMLScanner {
  public $scripts = false;
  public $embedded_server = false;

  public static function guess_language($src, $info) {
    if (strpos(ltrim($src), '<?xml') === 0) return 1.0;
    // don't catch HTML doctypes
    if (strpos($src, '<!DOCTYPE') !== false) return 0;
    $p = 0;
    // simple tag
    $lines = preg_match_all('/$/m', 
      preg_replace('/^\s+/m', '', $src), $m);
    // avg 1 tag every 4 lines 
    if (preg_match_all('%<[!?/]?[a-zA-Z_:\\-]%', $src, $m) 
      > $lines/4) $p += 0.15;
    // self closing tag
    if (strpos($src, '/>') !== false) $p += 0.05;
    // tag with attr
    if (preg_match('/<[a-zA-Z_]\w*\s+[a-zA-Z_]\w+\s*=["\']/', $src)) 
      $p += 0.1;

    return $p;
  }
}
