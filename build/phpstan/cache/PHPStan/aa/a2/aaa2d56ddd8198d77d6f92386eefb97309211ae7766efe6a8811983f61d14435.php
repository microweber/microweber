<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Helper/URLify.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Helper\URLify
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-09b11f70bf7cbc7fabf7940c34978ddc572df569cb9db7d26abd48b51856ea0e',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Helper\\URLify',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Helper/URLify.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Helper',
    'name' => 'MicroweberPackages\\Helper\\URLify',
    'shortName' => 'URLify',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * A PHP port of URLify.js from the Django project
 * (https://github.com/django/django/blob/master/django/contrib/admin/static/admin/js/urlify.js).
 * Handles symbols from Latin languages, Greek, Turkish, Russian, Ukrainian,
 * Czech, Polish, and Latvian. Symbols it cannot transliterate
 * it will simply omit.
 *
 * Usage:
 *
 * echo URLify::filter (\' J\\\'étudie le français \');
 * // "jetudie-le-francais"
 *
 * echo URLify::filter (\'Lo siento, no hablo español.\');
 * // "lo-siento-no-hablo-espanol"
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 22,
    'endLine' => 139,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'maps' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'name' => 'maps',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array(\'latin_map\' => array(\'À\' => \'A\', \'Á\' => \'A\', \'Â\' => \'A\', \'Ã\' => \'A\', \'Ä\' => \'A\', \'Å\' => \'A\', \'Æ\' => \'AE\', \'Ç\' => \'C\', \'È\' => \'E\', \'É\' => \'E\', \'Ê\' => \'E\', \'Ë\' => \'E\', \'Ì\' => \'I\', \'Í\' => \'I\', \'Î\' => \'I\', \'Ï\' => \'I\', \'Ð\' => \'D\', \'Ñ\' => \'N\', \'Ò\' => \'O\', \'Ó\' => \'O\', \'Ô\' => \'O\', \'Õ\' => \'O\', \'Ö\' => \'O\', \'Ő\' => \'O\', \'Ø\' => \'O\', \'Ù\' => \'U\', \'Ú\' => \'U\', \'Û\' => \'U\', \'Ü\' => \'U\', \'Ű\' => \'U\', \'Ý\' => \'Y\', \'Þ\' => \'TH\', \'ß\' => \'ss\', \'à\' => \'a\', \'á\' => \'a\', \'â\' => \'a\', \'ã\' => \'a\', \'ä\' => \'a\', \'å\' => \'a\', \'æ\' => \'ae\', \'ç\' => \'c\', \'è\' => \'e\', \'é\' => \'e\', \'ê\' => \'e\', \'ë\' => \'e\', \'ì\' => \'i\', \'í\' => \'i\', \'î\' => \'i\', \'ï\' => \'i\', \'ð\' => \'d\', \'ñ\' => \'n\', \'ò\' => \'o\', \'ó\' => \'o\', \'ô\' => \'o\', \'õ\' => \'o\', \'ö\' => \'o\', \'ő\' => \'o\', \'ø\' => \'o\', \'ù\' => \'u\', \'ú\' => \'u\', \'û\' => \'u\', \'ü\' => \'u\', \'ű\' => \'u\', \'ý\' => \'y\', \'þ\' => \'th\', \'ÿ\' => \'y\'), \'latin_symbols_map\' => array(\'©\' => \'(c)\'), \'greek_map\' => array(\'α\' => \'a\', \'β\' => \'b\', \'γ\' => \'g\', \'δ\' => \'d\', \'ε\' => \'e\', \'ζ\' => \'z\', \'η\' => \'h\', \'θ\' => \'8\', \'ι\' => \'i\', \'κ\' => \'k\', \'λ\' => \'l\', \'μ\' => \'m\', \'ν\' => \'n\', \'ξ\' => \'3\', \'ο\' => \'o\', \'π\' => \'p\', \'ρ\' => \'r\', \'σ\' => \'s\', \'τ\' => \'t\', \'υ\' => \'y\', \'φ\' => \'f\', \'χ\' => \'x\', \'ψ\' => \'ps\', \'ω\' => \'w\', \'ά\' => \'a\', \'έ\' => \'e\', \'ί\' => \'i\', \'ό\' => \'o\', \'ύ\' => \'y\', \'ή\' => \'h\', \'ώ\' => \'w\', \'ς\' => \'s\', \'ϊ\' => \'i\', \'ΰ\' => \'y\', \'ϋ\' => \'y\', \'ΐ\' => \'i\', \'Α\' => \'A\', \'Β\' => \'B\', \'Γ\' => \'G\', \'Δ\' => \'D\', \'Ε\' => \'E\', \'Ζ\' => \'Z\', \'Η\' => \'H\', \'Θ\' => \'8\', \'Ι\' => \'I\', \'Κ\' => \'K\', \'Λ\' => \'L\', \'Μ\' => \'M\', \'Ν\' => \'N\', \'Ξ\' => \'3\', \'Ο\' => \'O\', \'Π\' => \'P\', \'Ρ\' => \'R\', \'Σ\' => \'S\', \'Τ\' => \'T\', \'Υ\' => \'Y\', \'Φ\' => \'F\', \'Χ\' => \'X\', \'Ψ\' => \'PS\', \'Ω\' => \'W\', \'Ά\' => \'A\', \'Έ\' => \'E\', \'Ί\' => \'I\', \'Ό\' => \'O\', \'Ύ\' => \'Y\', \'Ή\' => \'H\', \'Ώ\' => \'W\', \'Ϊ\' => \'I\', \'Ϋ\' => \'Y\'), \'turkish_map\' => array(\'ş\' => \'s\', \'Ş\' => \'S\', \'ı\' => \'i\', \'İ\' => \'I\', \'ç\' => \'c\', \'Ç\' => \'C\', \'ü\' => \'u\', \'Ü\' => \'U\', \'ö\' => \'o\', \'Ö\' => \'O\', \'ğ\' => \'g\', \'Ğ\' => \'G\'), \'russian_map\' => array(\'а\' => \'a\', \'б\' => \'b\', \'в\' => \'v\', \'г\' => \'g\', \'д\' => \'d\', \'е\' => \'e\', \'ё\' => \'yo\', \'ж\' => \'zh\', \'з\' => \'z\', \'и\' => \'i\', \'й\' => \'j\', \'к\' => \'k\', \'л\' => \'l\', \'м\' => \'m\', \'н\' => \'n\', \'о\' => \'o\', \'п\' => \'p\', \'р\' => \'r\', \'с\' => \'s\', \'т\' => \'t\', \'у\' => \'u\', \'ф\' => \'f\', \'х\' => \'h\', \'ц\' => \'c\', \'ч\' => \'ch\', \'ш\' => \'sh\', \'щ\' => \'sh\', \'ъ\' => \'\', \'ы\' => \'y\', \'ь\' => \'\', \'э\' => \'e\', \'ю\' => \'yu\', \'я\' => \'ya\', \'А\' => \'A\', \'Б\' => \'B\', \'В\' => \'V\', \'Г\' => \'G\', \'Д\' => \'D\', \'Е\' => \'E\', \'Ё\' => \'Yo\', \'Ж\' => \'Zh\', \'З\' => \'Z\', \'И\' => \'I\', \'Й\' => \'J\', \'К\' => \'K\', \'Л\' => \'L\', \'М\' => \'M\', \'Н\' => \'N\', \'О\' => \'O\', \'П\' => \'P\', \'Р\' => \'R\', \'С\' => \'S\', \'Т\' => \'T\', \'У\' => \'U\', \'Ф\' => \'F\', \'Х\' => \'H\', \'Ц\' => \'C\', \'Ч\' => \'Ch\', \'Ш\' => \'Sh\', \'Щ\' => \'Sh\', \'Ъ\' => \'\', \'Ы\' => \'Y\', \'Ь\' => \'\', \'Э\' => \'E\', \'Ю\' => \'Yu\', \'Я\' => \'Ya\'), \'ukrainian_map\' => array(\'Є\' => \'Ye\', \'І\' => \'I\', \'Ї\' => \'Yi\', \'Ґ\' => \'G\', \'є\' => \'ye\', \'і\' => \'i\', \'ї\' => \'yi\', \'ґ\' => \'g\'), \'czech_map\' => array(\'č\' => \'c\', \'ď\' => \'d\', \'ě\' => \'e\', \'ň\' => \'n\', \'ř\' => \'r\', \'š\' => \'s\', \'ť\' => \'t\', \'ů\' => \'u\', \'ž\' => \'z\', \'Č\' => \'C\', \'Ď\' => \'D\', \'Ě\' => \'E\', \'Ň\' => \'N\', \'Ř\' => \'R\', \'Š\' => \'S\', \'Ť\' => \'T\', \'Ů\' => \'U\', \'Ž\' => \'Z\'), \'polish_map\' => array(\'ą\' => \'a\', \'ć\' => \'c\', \'ę\' => \'e\', \'ł\' => \'l\', \'ń\' => \'n\', \'ó\' => \'o\', \'ś\' => \'s\', \'ź\' => \'z\', \'ż\' => \'z\', \'Ą\' => \'A\', \'Ć\' => \'C\', \'Ę\' => \'e\', \'Ł\' => \'L\', \'Ń\' => \'N\', \'Ó\' => \'O\', \'Ś\' => \'S\', \'Ź\' => \'Z\', \'Ż\' => \'Z\'), \'latvian_map\' => array(\'ā\' => \'a\', \'č\' => \'c\', \'ē\' => \'e\', \'ģ\' => \'g\', \'ī\' => \'i\', \'ķ\' => \'k\', \'ļ\' => \'l\', \'ņ\' => \'n\', \'š\' => \'s\', \'ū\' => \'u\', \'ž\' => \'z\', \'Ā\' => \'A\', \'Č\' => \'C\', \'Ē\' => \'E\', \'Ģ\' => \'G\', \'Ī\' => \'i\', \'Ķ\' => \'k\', \'Ļ\' => \'L\', \'Ņ\' => \'N\', \'Š\' => \'S\', \'Ū\' => \'u\', \'Ž\' => \'Z\'), \'vietnamese_map\' => array(\'à\' => \'a\', \'á\' => \'a\', \'ạ\' => \'a\', \'ả\' => \'a\', \'ã\' => \'a\', \'â\' => \'a\', \'ầ\' => \'a\', \'ấ\' => \'a\', \'ậ\' => \'a\', \'ẩ\' => \'a\', \'ẫ\' => \'a\', \'ă\' => \'a\', \'ằ\' => \'a\', \'ắ\' => \'a\', \'ặ\' => \'a\', \'ẳ\' => \'a\', \'ẵ\' => \'a\', \'À\' => \'A\', \'Á\' => \'A\', \'Ạ\' => \'A\', \'Ả\' => \'A\', \'Ã\' => \'A\', \'Â\' => \'A\', \'Ầ\' => \'A\', \'Ấ\' => \'A\', \'Ậ\' => \'A\', \'Ẩ\' => \'A\', \'Ẫ\' => \'A\', \'Ă\' => \'A\', \'Ằ\' => \'A\', \'Ắ\' => \'A\', \'Ặ\' => \'A\', \'Ẳ\' => \'A\', \'Ẵ\' => \'A\', \'ì\' => \'i\', \'í\' => \'i\', \'ị\' => \'i\', \'ỉ\' => \'i\', \'ĩ\' => \'i\', \'Ì\' => \'I\', \'Í\' => \'I\', \'Ị\' => \'I\', \'Ỉ\' => \'I\', \'Ĩ\' => \'I\', \'ù\' => \'u\', \'ú\' => \'u\', \'ụ\' => \'u\', \'ủ\' => \'u\', \'ũ\' => \'u\', \'ư\' => \'u\', \'ừ\' => \'u\', \'ứ\' => \'u\', \'ự\' => \'u\', \'ử\' => \'u\', \'ữ\' => \'u\', \'Ù\' => \'U\', \'Ú\' => \'U\', \'Ụ\' => \'U\', \'Ủ\' => \'U\', \'Ũ\' => \'U\', \'Ư\' => \'U\', \'Ừ\' => \'U\', \'Ứ\' => \'U\', \'Ự\' => \'U\', \'Ử\' => \'U\', \'Ữ\' => \'U\', \'è\' => \'e\', \'é\' => \'e\', \'ẹ\' => \'e\', \'ẻ\' => \'e\', \'ẽ\' => \'e\', \'ê\' => \'e\', \'ề\' => \'e\', \'ế\' => \'e\', \'ệ\' => \'e\', \'ể\' => \'e\', \'ễ\' => \'e\', \'È\' => \'E\', \'É\' => \'E\', \'Ẹ\' => \'E\', \'Ẻ\' => \'E\', \'Ẽ\' => \'E\', \'Ê\' => \'E\', \'Ề\' => \'E\', \'Ế\' => \'E\', \'Ệ\' => \'E\', \'Ể\' => \'E\', \'Ễ\' => \'E\', \'ò\' => \'o\', \'ó\' => \'o\', \'ọ\' => \'o\', \'ỏ\' => \'o\', \'õ\' => \'o\', \'ô\' => \'o\', \'ồ\' => \'o\', \'ố\' => \'o\', \'ộ\' => \'o\', \'ổ\' => \'o\', \'ỗ\' => \'o\', \'ơ\' => \'o\', \'ờ\' => \'o\', \'ớ\' => \'o\', \'ợ\' => \'o\', \'ở\' => \'o\', \'ỡ\' => \'o\', \'Ò\' => \'O\', \'Ó\' => \'O\', \'Ọ\' => \'O\', \'Ỏ\' => \'O\', \'Õ\' => \'O\', \'Ô\' => \'O\', \'Ồ\' => \'O\', \'Ố\' => \'O\', \'Ộ\' => \'O\', \'Ổ\' => \'O\', \'Ỗ\' => \'O\', \'Ơ\' => \'O\', \'Ờ\' => \'O\', \'Ớ\' => \'O\', \'Ợ\' => \'O\', \'Ở\' => \'O\', \'Ỡ\' => \'O\', \'ỳ\' => \'y\', \'ý\' => \'y\', \'ỵ\' => \'y\', \'ỷ\' => \'y\', \'ỹ\' => \'y\', \'Ỳ\' => \'Y\', \'Ý\' => \'Y\', \'Ỵ\' => \'Y\', \'Ỷ\' => \'Y\', \'Ỹ\' => \'Y\', \'đ\' => \'d\', \'Đ\' => \'D\'))',
          'attributes' => 
          array (
            'startLine' => 23,
            'endLine' => 23,
            'startTokenPos' => 27,
            'startFilePos' => 630,
            'endTokenPos' => 2995,
            'endFilePos' => 6373,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 23,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 5771,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'remove_list' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'name' => 'remove_list',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 27,
            'endLine' => 27,
            'startTokenPos' => 3008,
            'startFilePos' => 6467,
            'endTokenPos' => 3010,
            'endFilePos' => 6473,
          ),
        ),
        'docComment' => '/**
 * List of words to remove from URLs.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'map' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'name' => 'map',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 3023,
            'startFilePos' => 6544,
            'endTokenPos' => 3025,
            'endFilePos' => 6550,
          ),
        ),
        'docComment' => '/**
 * The character map.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'chars' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'name' => 'chars',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 3038,
            'startFilePos' => 6636,
            'endTokenPos' => 3038,
            'endFilePos' => 6637,
          ),
        ),
        'docComment' => '/**
 * The character list as a string.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'regex' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'name' => 'regex',
        'modifiers' => 20,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 39,
            'endLine' => 39,
            'startTokenPos' => 3051,
            'startFilePos' => 6735,
            'endTokenPos' => 3051,
            'endFilePos' => 6736,
          ),
        ),
        'docComment' => '/**
 * The character list as a regular expression.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 39,
        'endLine' => 39,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'add_chars' => 
      array (
        'name' => 'add_chars',
        'parameters' => 
        array (
          'map' => 
          array (
            'name' => 'map',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 38,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add new characters to the list.
 * `$map` should be a hash.
 */',
        'startLine' => 45,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => true,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'currentClassName' => 'MicroweberPackages\\Helper\\URLify',
        'aliasName' => NULL,
      ),
      'remove_words' => 
      array (
        'name' => 'remove_words',
        'parameters' => 
        array (
          'words' => 
          array (
            'name' => 'words',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 59,
            'endLine' => 59,
            'startColumn' => 41,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Append words to the remove list.
 * Accepts either single words
 * or an array of words.
 */',
        'startLine' => 59,
        'endLine' => 62,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'currentClassName' => 'MicroweberPackages\\Helper\\URLify',
        'aliasName' => NULL,
      ),
      'filter' => 
      array (
        'name' => 'filter',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 35,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'length' => 
          array (
            'name' => 'length',
            'default' => 
            array (
              'code' => '60',
              'attributes' => 
              array (
                'startLine' => 67,
                'endLine' => 67,
                'startTokenPos' => 3197,
                'startFilePos' => 7519,
                'endTokenPos' => 3197,
                'endFilePos' => 7520,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 42,
            'endColumn' => 53,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'to_lower' => 
          array (
            'name' => 'to_lower',
            'default' => 
            array (
              'code' => 'true',
              'attributes' => 
              array (
                'startLine' => 67,
                'endLine' => 67,
                'startTokenPos' => 3201,
                'startFilePos' => 7532,
                'endTokenPos' => 3201,
                'endFilePos' => 7535,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 67,
            'endLine' => 67,
            'startColumn' => 55,
            'endColumn' => 68,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Filters a string, e.g., "Petty theft" to "petty-theft".
 */',
        'startLine' => 67,
        'endLine' => 95,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'currentClassName' => 'MicroweberPackages\\Helper\\URLify',
        'aliasName' => NULL,
      ),
      'transliterate' => 
      array (
        'name' => 'transliterate',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 100,
            'endLine' => 100,
            'startColumn' => 42,
            'endColumn' => 46,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Alias of `URLify::downcode()`.
 */',
        'startLine' => 100,
        'endLine' => 102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'currentClassName' => 'MicroweberPackages\\Helper\\URLify',
        'aliasName' => NULL,
      ),
      'downcode' => 
      array (
        'name' => 'downcode',
        'parameters' => 
        array (
          'text' => 
          array (
            'name' => 'text',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 107,
            'endLine' => 107,
            'startColumn' => 37,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Transliterates characters to their ASCII equivalents.
 */',
        'startLine' => 107,
        'endLine' => 120,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'currentClassName' => 'MicroweberPackages\\Helper\\URLify',
        'aliasName' => NULL,
      ),
      'init' => 
      array (
        'name' => 'init',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Initializes the character map.
 */',
        'startLine' => 125,
        'endLine' => 138,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'MicroweberPackages\\Helper',
        'declaringClassName' => 'MicroweberPackages\\Helper\\URLify',
        'implementingClassName' => 'MicroweberPackages\\Helper\\URLify',
        'currentClassName' => 'MicroweberPackages\\Helper\\URLify',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));