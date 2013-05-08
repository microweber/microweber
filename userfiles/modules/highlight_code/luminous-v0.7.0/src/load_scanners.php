<?php

/*
 * This is a horrible routine to register all the default
 * scanners. The code is distracting at best so it's been factored into this one
 * file.
 * 
 * We include it into the main program with a require statement, which 
 * due to the literal way PHP includes work, when done within a function gives 
 * us access to that function's scope.
 * We are in the scope of a method inside the Luminous_ object, so we refer to
 * $this as being the $luminous_ singleton object.
 */


$language_dir = luminous::root() . '/languages/';


// this is a dummy file which includes ECMAScript dependencies in a 
// non-circular way.
$this->scanners->AddScanner('ecma-includes', null, null, 
    "$language_dir/include/ecma.php");
    
$this->scanners->AddScanner(array('ada', 'adb', 'ads'), 
    'LuminousAdaScanner', 'Ada', "$language_dir/ada.php");

$this->scanners->AddScanner(array('as', 'actionscript'),
'LuminousActionScriptScanner', 'ActionScript', "$language_dir/as.php", 
'ecma');

$this->scanners->AddScanner(array('bnf'), 
    'LuminousBNFScanner', 'Backus Naur Form', "$language_dir/bnf.php");

$this->scanners->AddScanner(array('bash', 'sh'),
'LuminousBashScanner', 'Bash', "$language_dir/bash.php");

$this->scanners->AddScanner(array('c', 'cpp', 'h', 'hpp', 'cxx', 'hxx'),
    'LuminousCppScanner', 'C/C++', "$language_dir/cpp.php");
    
$this->scanners->AddScanner(array('cs', 'csharp', 'c#'),
    'LuminousCSharpScanner', 'C#', "$language_dir/csharp.php");
    
$this->scanners->AddScanner('css',
    'LuminousCSSScanner', 'CSS', "$language_dir/css.php");
    
$this->scanners->AddScanner(array('diff', 'patch'),
    'LuminousDiffScanner', 'Diff', "$language_dir/diff.php");

$this->scanners->AddScanner(array('prettydiff', 'prettypatch',
    'diffpretty', 'patchpretty'),
    'LuminousPrettyDiffScanner', 'Diff-Pretty', "$language_dir/diff.php");
    
$this->scanners->AddScanner(array('html', 'htm'),
    'LuminousHTMLScanner', 'HTML', "$language_dir/html.php",
    array('js', 'css'));
    
$this->scanners->AddScanner(array('ecma', 'ecmascript'),
    'LuminousECMAScriptScanner', 'ECMAScript', 
    "$language_dir/ecmascript.php", 'ecma-includes');

$this->scanners->AddScanner(array('erlang', 'erl', 'hrl'),
    'LuminousErlangScanner', 'Erlang', "$language_dir/erlang.php");

$this->scanners->AddScanner('go', 'LuminousGoScanner', 'Go',
    "$language_dir/go.php");


$this->scanners->AddScanner(array('groovy'),
    'LuminousGroovyScanner', 'Groovy',
    "$language_dir/groovy.php");

$this->scanners->AddScanner(array('haskell', 'hs'),
    'LuminousHaskellScanner', 'Haskell', "$language_dir/haskell.php");

$this->scanners->AddScanner('java',
    'LuminousJavaScanner', 'Java', "$language_dir/java.php");
    
$this->scanners->AddScanner(array('js', 'javascript'),
    'LuminousJavaScriptScanner', 'JavaScript', "$language_dir/javascript.php",
    array('ecma'));
    
$this->scanners->AddScanner('json',
    'LuminousJSONScanner', 'JSON', "$language_dir/json.php");

$this->scanners->AddScanner(array('latex', 'tex'),
    'LuminousLatexScanner', 'LaTeX', "$language_dir/latex.php");
    
$this->scanners->AddScanner(array('lolcode', 'lolc', 'lol'),
    'LuminousLOLCODEScanner', 'LOLCODE', "$language_dir/lolcode.php");
    
$this->scanners->AddScanner(array('m', 'matlab'),
    'LuminousMATLABScanner', 'MATLAB', "$language_dir/matlab.php");

$this->scanners->AddScanner(array('perl', 'pl', 'pm'),
    'LuminousPerlScanner', 'Perl', "$language_dir/perl.php");

$this->scanners->AddScanner(array('rails','rhtml', 'ror'),
    'LuminousRailsScanner', 'Ruby on Rails',
    "$language_dir/rails.php", array('ruby', 'html'));
    
$this->scanners->AddScanner(array('ruby','rb'),
    'LuminousRubyScanner', 'Ruby', "$language_dir/ruby.php");

$this->scanners->AddScanner(array('plain', 'text', 'txt'),
    'LuminousIdentityScanner', 'Plain', "$language_dir/identity.php");

// PHP Snippet does not require an initial <?php tag to begin highlighting
$this->scanners->AddScanner('php_snippet', 'LuminousPHPSnippetScanner',
    'PHP Snippet', "$language_dir/php.php", array('html'));
    
$this->scanners->AddScanner('php',
    'LuminousPHPScanner', 'PHP', "$language_dir/php.php",
    array('html'));
    
$this->scanners->AddScanner(array('python', 'py'),
    'LuminousPythonScanner', 'Python', "$language_dir/python.php");
$this->scanners->AddScanner(array('django', 'djt'),
    'LuminousDjangoScanner', 'Django', "$language_dir/python.php",
    array('html')
);
$this->scanners->AddScanner(array('scala', 'scl'),
    'LuminousScalaScanner', 'Scala', "$language_dir/scala.php", 'xml');
    
$this->scanners->AddScanner('scss',
    'LuminousSCSSScanner', 'SCSS', "$language_dir/scss.php");

$this->scanners->AddScanner(array('sql', 'mysql'),
    'LuminousSQLScanner', 'SQL', "$language_dir/sql.php");
    
$this->scanners->AddScanner(array('vim', 'vimscript'),
    'LuminousVimScriptScanner', 'Vim Script', "$language_dir/vim.php");

$this->scanners->AddScanner(array('vb', 'bas'),
    'LuminousVBScanner', 'Visual Basic', "$language_dir/vb.php",
    'xml');
    
$this->scanners->AddScanner('xml', 'LuminousXMLScanner', 
    'XML', "$language_dir/xml.php", 'html');

$this->scanners->SetDefaultScanner('plain');