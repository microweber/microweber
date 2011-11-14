<?php
/// @cond ALL
class LuminousFormatterHTML extends LuminousFormatter {

  public $height = 0;
  /** 
   * strict HTML standards: the target attribute won't be used in links
   * \since  0.5.7
   */
  public $strict_standards = false;

  /// line number-less
  protected $numberless_template = '<div class="code">
  <pre class="code">%s</pre>
</div>';

  /// line numbered
  protected $numbered_template = '<table class="code_container">
  <tr>
    <td class="line_number_bar">
      <pre class="line_numbers">%s</pre>
    </td>
    <td class="code">
      <pre class="code">%s</pre>
    </td>
  </tr>
</table>';

  /// container template, placeholders are 1: inline style 2: code
  protected $template = '<div class="luminous">
  <div class="code_container" %s>%s</div>
</div>';


  private function format_numberless($src) {
    $lines = array();
    $lines_original = explode("\n", $src);
    foreach($lines_original as $line) {
      $l = $line;
      $num = $this->wrap_line($l, $this->wrap_length);
      // strip the newline if we're going to join it. Seems the easiest way to 
      // fix http://code.google.com/p/luminous/issues/detail?id=10
      $l = substr($l, 0, -1);
      $lines[] = $l;
    }
    $lines = implode("\n", $lines);
    return sprintf($this->numberless_template, $lines);
  }
  
  
  public function format($src) {
    $line_numbers = false;

    if ($this->link)  $src = $this->linkify($src);
    
    $code_block = $this->line_numbers? $this->format_numbered($src)
      : $this->format_numberless($src);

    // convert </ABC> to </span>
    $code_block = preg_replace('/(?<=<\/)[A-Z_0-9]+(?=>)/S', 'span',
      $code_block);
    // convert <ABC> to <span class=ABC>
    $cb = create_function('$matches', 
                          '$m1 = strtolower($matches[1]);
                          return "<span class=\'" . $m1 . "\'>";
                          ');
    $code_block = preg_replace_callback('/<([A-Z_0-9]+)>/', $cb, $code_block);
    
    $height = trim('' . $this->height);
    $css = '';
    
    if (!empty($height) && (int)$height > 0) {
      // look for units, use px is there are none
      if (!preg_match('/\D$/', $height)) $height .= 'px';
      $css = " style='max-height: {$height};'";
    }
    else 
      $css = ' style="overflow:visible !important;"';
    return sprintf($this->template, $css, $code_block);
  }
  
  /**
   * Detects and links URLs - callback
   */
  protected function linkify_cb($matches) {
    $uri = (isset($matches[1]) && strlen(trim($matches[1])))? $matches[0]
      : "http://" . $matches[0];

    // we dont want to link if it would cause malformed HTML
    $open_tags = array();
    $close_tags = array();
    preg_match_all("/<(?!\/)([^\s>]*).*?>/", $matches[0], $open_tags,
      PREG_SET_ORDER);
    preg_match_all("/<\/([^\s>]*).*?>/", $matches[0], $close_tags,
      PREG_SET_ORDER);
    
    if (count($open_tags) != count($close_tags))
      return $matches[0];
    if (isset($open_tags[0]) 
      && trim($open_tags[0][1]) !== trim($close_tags[0][1])
    )
      return $matches[0];
    
    $uri = strip_tags($uri);
    
    $target = ($this->strict_standards)? '' : ' target="_blank"';
    return "<a href='{$uri}' class='link'{$target}>{$matches[0]}</a>";
  }
  
  /**
   * Detects and links URLs
   */
  protected function linkify($src) {
    if (stripos($src, "http") === false && stripos($src, "www") === false)
        return $src;
    
    $chars = "0-9a-zA-Z\$\-_\.+!\*,%";
    $src_ = $src;
    // everyone stand back, I know regular expressions
    $src = preg_replace_callback(
      "@(?<![\w])
      (?:(https?://(?:www[0-9]*\.)?) | (?:www\d*\.)   )
      
      # domain and tld
      (?:[$chars]+)+\.[$chars]{2,}
      # we don't include tags at the EOL because these are likely to be 
      # line-enclosing tags.
      (?:[/$chars/?=\#;]+|&amp;|<[^>]+>(?!$))*
      @xm",
      array($this, 'linkify_cb'), $src);
    // this can hit a backtracking limit, in which case it nulls our string
    // FIXME: see if we can make the above regex more resiliant wrt
    // backtracking
    if (preg_last_error() !== PREG_NO_ERROR) {
      $src = $src_;
    }
    return $src;
  }
  
  
  private function format_numbered($src) {

    $linenos = '';
    $lines = '';
    
    $lines_original = explode("\n", $src);

    // this seems to run a bit faster if we keep the literals out of
    // the loop.

    $line_no_plain_tag0 = '<span class="line_number">&nbsp;';
    $line_no_emph_tag0 = '<span class="line_number line_number_emphasised">&nbsp;';
    $line_no_tag1 = "&nbsp;\n</span>";
    
    $wrap_line = "<span class='line_number'>&nbsp;|_\n</span>";
    
    $line_tag0 = '<span class="line">';
    $line_alt_tag0 = '<span class="line line_alt">';
    $line_tag1 = '</span>';

    $lineno = 1;
    foreach($lines_original as $line)  {

      $linenos .= ($lineno % 5 === 0)? $line_no_emph_tag0
        : $line_no_plain_tag0;
      $linenos .= $lineno . $line_no_tag1;

      $num = $this->wrap_line($line, $this->wrap_length);

      for ($i=1; $i<$num; $i++)  $linenos .= $wrap_line;

      $lines .= ($lineno % 2 === 0)? $line_alt_tag0 : $line_tag0;
      $lines .= $line . $line_tag1;

      ++$lineno;

    }
    return sprintf($this->numbered_template, $linenos, $lines);
  }
}


class LuminousFormatterHTMLInline extends LuminousFormatterHTML {
  protected $template = '<div class="luminous luminous_inline">
  <div class="code_container" %s>%s</div>
</div>';

  public function format($src) {
    $this->line_numbers = false;
    $this->height = 0;
    return parent::format($src);
  }

}


class LuminousFormatterHTMLFullPage extends LuminousFormatterHTML {
  protected $theme_css = null;
  protected $css = null;
  public function set_theme($css) {
    $this->theme_css = $css;
  }
  protected function get_layout() {
    // this path info shouldn't really be here
    $path = luminous::root() . '/style/luminous.css';
    $this->css = file_get_contents($path);
  }
  public function format($src) {
    $this->height = 0;
    $this->get_layout();
    $fmted = parent::format($src);
    return <<<EOF
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <style type='text/css'>
    body {
      margin: 0;
    }
    /* luminous.css */
    {$this->css}
    /* End luminous.css */
    /* Theme CSS */
    {$this->theme_css}
    /* End theme CSS */    
    </style>
  </head>
  <body>
    <!-- Begin luminous code //-->
    $fmted
    <!-- End Luminous code //-->
  </body>
</html>

EOF;
  }
}
/// @endcond
