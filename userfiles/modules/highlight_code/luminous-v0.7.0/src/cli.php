<?php
///@cond ALL
/* command line interface */

class LuminousCLI {
  private $options = array(
    'input-file' => null,
    'output-file' => null,
    'lang' => null,
    'format' => 'html-full',
    'height' => 0,
    'theme' => 'geonyx',
    'code' => null,
    'line-numbers' => true,
  );
  private $cmd_option_map = array(
    '-i' => 'input-file',
    '-o' => 'output-file',
    '-l' => 'lang',
    '-f' => 'format',
    '-h' => 'height',
    '-t' => 'theme',
  );
  
  static function print_help() {
    echo <<<EOF
Usage:
  php luminous.php [OPTIONS] [SOURCE_CODE]

SOURCE_CODE may be omitted if you specify -i. Use '-' to read code from stdin.

Options:
  -f <format>           Output format. This can be:
                        'html' - An HTML snippet (div). CSS is not included
                          on the page, the style-sheets must be included by
                          hand.
                        'html-full' - A full HTML page. CSS is embedded.
                        'latex' - A LaTeX document.
                        Default: 'html-full'
  -h <height>           Constrains the height of the widget with 'html'
                        formatter. Has no effect on other formatters.
                        Default: 0
  -i <filename>         Input file. If this is omitted, SOURCE_CODE is used.
  -l <language>         Language code. If this is omitted, the language is
                        guessed.
  -o <filename>         Output file to write. If this is omitted, stdout is
                        used.
  -t <theme>            Theme to use. See --list-themes for valid themes
  --no-numbers          Disables line numbering
  
  --list-codes          Lists valid language codes and exits
  --list-themes         Lists valid themes and exits

  --help                Display this text and exit
  --version             Display version number and exit

EOF;
  }


  function error($string) {
    echo "Error: $string
see --help for help
";
    exit(1);
  }

  function set_lookahead($option, $i) {
    global $argv;
    if (isset($argv[$i+1]))
      $this->options[$this->cmd_option_map[$option]] = $argv[$i+1];
    else self::error('Missing option for ' . $option);
  }


  function list_codes() {
    foreach(luminous::scanners() as $name=>$codes) {
      echo sprintf("%s: %s\n", $name, join(', ', $codes));
    }
    exit(0);
  }
  
  function list_themes() {
    echo preg_replace('/\.css$/m', '', join("\n", luminous::themes()) . "\n");
    exit(0);
  }

  function parse_args() {
    global $argv, $argc;
    for($i=1; $i<$argc; $i++) {
      $a = $argv[$i];
      
      if (isset($this->cmd_option_map[$a])) {
        $this->set_lookahead($a, $i++);
      }
      elseif ($a === '--list-codes') {
        self::list_codes();
      }
      elseif ($a === '--list-themes') {
        self::list_themes();
      }
      elseif ($a === '--help') {
        self::print_help();
        exit(0);
      }
      elseif($a === '--version') {
        echo LUMINOUS_VERSION;
        echo "\n";
        exit(0);
      }
      elseif ($a === '--no-numbers') {
        $this->options['line-numbers'] = false;
      }
      else {
        if ($this->options['code'] !== null) {
          self::error('Unknown option: ' . $a);
        } else { 
          $this->options['code'] = $a;
        }
      }
    }
  }


  function highlight() {
    $this->parse_args();

    // figure out the code

    // error cases are:
    // no input file or source code,
    if ($this->options['code'] === null
      && $this->options['input-file'] === null) {
      $this->error('No input file or source code specified');
    }
    // or both input file and source code
    elseif ($this->options['code'] !== null
      && $this->options['input-file'] !== null) {
        $this->error('Input file (-i) and source code specified. You probably '
        . 'didn\'t mean this');
    }

    // is there an input file? use that.
    if ($this->options['input-file'] !== null) {
      $c = @file_get_contents($this->options['input-file']);
      if ($c === false) {
        $this->error('Could not read from ' . $this->options['input-file']);
      }
      else {
        $this->options['code'] = $c;
      }
    }
    // else we're expecting code to have been given on the command line,
    // but it might be '-' which means read stdin
    elseif ($this->options['code'] === '-') {
      $code = '';
      while (($line = fgets(STDIN)) !== false)
        $code .= $line;
      $this->options['code'] = $code;
    }

    // set the formatter
    luminous::set('format', $this->options['format']);
    // lame check that the formatter is okay
    try { luminous::formatter(); }
    catch(Exception $e) {
      $this->error('Unknown formatter ' . $this->options['format']);
    }

    // set the theme
    $valid_themes = luminous::themes();
    $theme = $this->options['theme'];
    if (!preg_match('/\.css$/', $theme)) $theme .= '.css';
    if (!luminous::theme_exists($theme)) {
      $this->error('No such theme: ' . $theme);
    } else {
      luminous::set('theme', $theme);
    }
    

    // set the language
    if ($this->options['lang'] === null) {
      // guessing
      $this->options['lang'] = luminous::guess_language($this->options['code']);
    }
    
    // user provided language
    $scanners = luminous::scanners();
    $valid_scanner = false;
    foreach($scanners as $lang=>$codes) {
      if (in_array($this->options['lang'], $codes)) {
        $valid_scanner = true;
        break;
      }
    }
    if (!$valid_scanner) $this->error('No such language: '
      . $this->options['lang']);


    // other options
    luminous::set('max-height', $this->options['height']);
    luminous::set('line-numbers', $this->options['line-numbers']);

    $h = luminous::highlight($this->options['lang'], $this->options['code']);
    if ($this->options['output-file'] !== null) {
      $r = @file_put_contents($this->options['output-file'], $h, LOCK_EX);
      if ($r === false)
        $this->error('Could not write to ' . $this->options['output-file']);
    } else {
      echo $h;
    }
    exit(0);
  }
}

function main() {
  $luminous_cli = new LuminousCLI();
  $luminous_cli->highlight();
}
main();

///@endcond
