<?php
/*
 * Rails. Basically a wrapper around Ruby and HTML.
 */

class LuminousRailsScanner extends LuminousScanner {

  
  // HTML scanner has to be persistent. Ruby doesn't.
  private $html_scanner;

  public function init() {
    $this->html_scanner = new LuminousHTMLScanner();
    $this->html_scanner->string($this->string());
    $this->html_scanner->embedded_server = true;
    $this->html_scanner->server_tags = '/<%/';
    $this->html_scanner->init();
  }

  public function scan_html() {
    $this->html_scanner->pos($this->pos());
    $this->html_scanner->main();
    $this->record($this->html_scanner->tagged(), null, true);
    $this->pos($this->html_scanner->pos());
  }




  public function scan_ruby($short=false) {
    $ruby_scanner = new LuminousRubyScanner($this->string());
    $ruby_scanner->rails = true;
    $ruby_scanner->init();
    $ruby_scanner->pos($this->pos());
    $ruby_scanner->main();
    $this->record($ruby_scanner->tagged(), $short? 'INTERPOLATION' : null, true);
    $this->pos($ruby_scanner->pos());
  }


  public function main() {
    while(!$this->eos()) {
      $p = $this->pos();
      if ($this->scan('/<%#?([\-=]?)/')) {
        $this->record($this->match(), 'DELIMITER');
        $this->scan_ruby($this->match_group(1) === '=');
        if ($this->scan('/-?%>/')) {
          $this->record($this->match(), 'DELIMITER');
        }
      }
      else {
        $this->scan_html();
      }
      assert($p < $this->pos());
    }
  }


  public static function guess_language($src, $info) {
    $p = LuminousRubyScanner::guess_language($src, $info);
    if ($p > 0) {
      if (preg_match('/<%.*%>/', $src)) $p += 0.02;
      else $p = 0.0;
      $p = min($p, 1);
    }
    return $p;
  }


}
