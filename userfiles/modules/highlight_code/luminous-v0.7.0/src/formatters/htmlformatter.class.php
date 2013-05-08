<?php
/// @cond ALL

/**
  * Collection of templates and templating utilities
  */
class LuminousHTMLTemplates {

    // NOTE Don't worry about whitespace in the templates - it gets stripped from the innerHTML,
    // so the <pre>s aren't affected. Make it readable :)
    
    /// Normal container 
    const container_template = '
      <div 
        class="luminous" 
        data-language="{language}"
        style="{height_css}"
      >
        {subelement}
      </div>';
      
    /// Inline code container
    const inline_template = '
        <div 
          class="luminous inline"
          data-language="{language}"
        >
          {subelement}
        </div>';

    /// line number-less
    const numberless_template = '
      <pre 
        class="code" 
      >
        {code}
      </pre>';
    
    /// line numbered
    // NOTE: there's a good reason we use tables here and that's because
    // nothing else works reliably.
    const numbered_template = '
      <table>
        <tbody>
          <tr>
            <td>
                <pre class="line-numbers">
                  {line_numbers}
                </pre>
            </td>
            
            <td class="code-container">
              <pre class="code numbered"
                data-startline="{start_line}"
                data-highlightlines="{highlight_lines}"
              >
                {code}
              </pre>
            </td>
          </tr>
        </tbody>
      </table>';
    

    
    
    private static function _strip_template_whitespace_cb($matches) {
        return ($matches[0][0] === '<')? $matches[0] : '';
    }
    private static function _strip_template_whitespace($string) {
        return preg_replace_callback('/\s+|<[^>]++>/',
          array('self', '_strip_template_whitespace_cb'),
          $string);
    }
    
    /**
      * Formats a string with a given set of values
      * The format syntax uses {xyz} as a placeholder, which will be 
      * substituted from the 'xyz' key from $variables
      *
      * @param $template The template string
      * @param $variables An associative (keyed) array of values to be substituted
      * @param $strip_whitespace_from_template If @c TRUE, the template's whitespace is removed.
      *   This allows templates to be written to be easeier to read, without having to worry about
      *   the pre element inherting any unintended whitespace
      */
    public static function format($template, $variables, $strip_whitespace_from_template = true) {
    
        if ($strip_whitespace_from_template) {
            $template = self::_strip_template_whitespace($template);
        }
    
        foreach($variables as $search => $replace) {
            $template = str_replace("{" . $search . "}", $replace, $template);
        }
        return $template;
    }
}

class LuminousFormatterHTML extends LuminousFormatter {

  // overridden by inline formatter
  protected $inline = false;
  public $height = 0;
  /** 
   * strict HTML standards: the target attribute won't be used in links
   * \since  0.5.7
   */
  public $strict_standards = false;

  private function height_css() {
    $height = trim('' . $this->height);
    $css = '';  
    if (!empty($height) && (int)$height > 0) {
      // look for units, use px is there are none
      if (!preg_match('/\D$/', $height)) $height .= 'px';
      $css = "max-height: {$height};";
    }
    else 
      $css = '';  
    return $css;
   }

  private static function template_cb($matches) {
    
  }

  // strips out unnecessary whitespace from a template
  private static function template($t, $vars=array()) {
    $t = preg_replace_callback('/\s+|<[^>]++>/',
      array('self', 'template_cb'),
      $t);      
    array_unshift($vars, $t);
    $code = call_user_func_array('sprintf', $vars);
    return $code;
  }
  
  private function lines_numberless($src) {
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
    return $lines;
  }

  private function format_numberless($src) {
    return LuminousHTMLTemplates::format(
      LuminousHTMLTemplates::numberless_template,
      array(
        'height_css' => $this->height_css(),
        'code' => $this->lines_numberless($src)
      )
    );
  }
  
  
  public function format($src) {
  
    $line_numbers = false;

    if ($this->link)  $src = $this->linkify($src);
    
    $code_block = null;
    if ($this->line_numbers) {
        $code_block = $this->format_numbered($src);
    }
    else {
        $code_block = $this->format_numberless($src);
    }

    // convert </ABC> to </span>
    $code_block = preg_replace('/(?<=<\/)[A-Z_0-9]+(?=>)/S', 'span',
      $code_block);
    // convert <ABC> to <span class=ABC>
    $cb = create_function('$matches', 
                          '$m1 = strtolower($matches[1]);
                          return "<span class=\'" . $m1 . "\'>";
                          ');
    $code_block = preg_replace_callback('/<([A-Z_0-9]+)>/', $cb, $code_block);
    
    $format_data = array(
      'language' => ($this->language === null)? '' : htmlentities($this->language),
      'subelement' => $code_block,
      'height_css' => $this->height_css()
    );
    return LuminousHTMLTemplates::format(
      $this->inline? LuminousHTMLTemplates::inline_template : 
        LuminousHTMLTemplates::container_template,
      $format_data
    );
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
  
    $lines = '<span>' .
      str_replace("\n", "\n</span><span>", $src, $num_replacements) .
      "\n</span>";
    $num_lines = $num_replacements + 1;
    
    $line_numbers = '<span>' . implode('</span><span>',      
      range($this->start_line, $this->start_line + $num_lines - 1, 1)
    ) . '</span>';
    
    
    $format_data = array(
      'line_number_digits' => strlen( (string)($this->start_line) + $num_lines ), // max number of digits in the line - this is used by the CSS
      'start_line' => $this->start_line,
      'height_css' => $this->height_css(),
      'highlight_lines' => implode(',', $this->highlight_lines),
      'code' => $lines,
      'line_numbers' => $line_numbers
    );
    
    return LuminousHTMLTemplates::format(
      LuminousHTMLTemplates::numbered_template,
      $format_data
    );
    
  }
}


class LuminousFormatterHTMLInline extends LuminousFormatterHTML {

  public function format($src) {
    $this->line_numbers = false;
    $this->height = 0;
    $this->inline = true;
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
