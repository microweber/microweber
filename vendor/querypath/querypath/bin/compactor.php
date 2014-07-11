<?php
/**
 * Compact PHP code.
 *
 * Strip comments, combine entire library into one file.
 */

if ($argc < 3) {
  print "Strip unecessary data from PHP source files.\n\n\tUsage: php compactor.php DESTINATION.php SOURCE.php";
  exit;
}


$source = $argv[2];
$target = $argv[1];
print "Compacting $source into $target.\n";

include $source;

$files = get_included_files();
print_r($files);

$out = fopen($target, 'w');
fwrite($out, '<?php' . PHP_EOL);
fwrite($out, '// QueryPath. Copyright (c) 2009, Matt Butcher.' . PHP_EOL);
fwrite($out, '// This software is released under the LGPL, v. 2.1 or an MIT-style license.' . PHP_EOL);
fwrite($out ,'// http://opensource.org/licenses/lgpl-2.1.php');
fwrite($out, '// http://querypath.org.' . PHP_EOL);
foreach ($files as $f) {
  if ($f !== __FILE__) {
    $contents = file_get_contents($f);
    foreach (token_get_all($contents) as $token) {
      if (is_string($token)) {
        fwrite($out, $token);
      }
      else {
        switch ($token[0]) {
          case T_REQUIRE:
          case T_REQUIRE_ONCE:
          case T_INCLUDE_ONCE:
          // We leave T_INCLUDE since it is rarely used to include
          // libraries and often used to include HTML/template files.
          case T_COMMENT:
          case T_DOC_COMMENT:
          case T_OPEN_TAG:
          case T_CLOSE_TAG:
            print "?";
            break;
          case T_WHITESPACE:
            fwrite($out, ' ');
            break;
          default:
            fwrite($out, $token[1]);
        }
        
      }
    }
  }
}
fclose($out);
?>