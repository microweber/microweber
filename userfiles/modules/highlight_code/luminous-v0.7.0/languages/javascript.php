<?php

class LuminousJavaScriptScanner extends LuminousECMAScriptScanner {
  // mostly the same for now
  
  public static function guess_language($src, $info) {
    // JavaScript is surprisingly indistinct when you think about it,
    // so these are a bit of a stretch and it's hard to ever return a 
    // value more than ~40%
    $p = 0;
    // var x = 
    // not amazingly distinct, but something
    if (preg_match('/var\s++\w++\s*+=/', $src)) $p += 0.05;
    // $, jquery
    if (preg_match('/\\b$\\( | jQuery/x', $src)) $p += 0.20;
    // typeof x == undefined
    if (preg_match('/typeof\s++\w++\s*+[!=]{2,3}+\s*+[\'"]?+undefined/i', $src))
      $p += 0.10;
    if (strpos($src, 'document.') !== false) $p += 0.10;
    if (strpos($src, 'Math.') !== false) $p += 0.05;
    // Anonymous functions
    if (preg_match('/function\s*+\\([^)]*+\\)\s*+\\{/', $src))
      $p += 0.05;
    return $p;
  }
}
