<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/Reference/W3CReference.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Symfony\Component\HtmlSanitizer\Reference\W3CReference
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-4159807ced76b6cc8db5f30d40191f1971b8bcafe3098e5f69a20e9d2e7da512-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../symfony/html-sanitizer/Reference/W3CReference.php',
      ),
    ),
    'namespace' => 'Symfony\\Component\\HtmlSanitizer\\Reference',
    'name' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
    'shortName' => 'W3CReference',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * Stores reference data from the W3C Sanitizer API standard.
 *
 * @see https://wicg.github.io/sanitizer-api/#default-configuration
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @internal
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 23,
    'endLine' => 400,
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
      'CONTEXT_HEAD' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'name' => 'CONTEXT_HEAD',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'head\'',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 29,
            'startFilePos' => 813,
            'endTokenPos' => 29,
            'endFilePos' => 818,
          ),
        ),
        'docComment' => '/**
 * Sanitizer supported contexts.
 *
 * A parent element name can be passed as an argument to {@see HtmlSanitizer::sanitizeFor()}.
 * When doing so, depending on the given context, different elements will be allowed.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'CONTEXT_BODY' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'name' => 'CONTEXT_BODY',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'body\'',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 40,
            'startFilePos' => 853,
            'endTokenPos' => 40,
            'endFilePos' => 858,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'CONTEXT_TEXT' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'name' => 'CONTEXT_TEXT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'text\'',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 51,
            'startFilePos' => 893,
            'endTokenPos' => 51,
            'endFilePos' => 898,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'CONTEXTS_MAP' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'name' => 'CONTEXTS_MAP',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'head\' => self::CONTEXT_HEAD, \'textarea\' => self::CONTEXT_TEXT, \'title\' => self::CONTEXT_TEXT]',
          'attributes' => 
          array (
            'startLine' => 36,
            'endLine' => 40,
            'startTokenPos' => 64,
            'startFilePos' => 1008,
            'endTokenPos' => 93,
            'endFilePos' => 1133,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 36,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'HEAD_ELEMENTS' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'name' => 'HEAD_ELEMENTS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'head\' => true, \'link\' => true, \'meta\' => true, \'style\' => false, \'title\' => true]',
          'attributes' => 
          array (
            'startLine' => 46,
            'endLine' => 52,
            'startTokenPos' => 106,
            'startFilePos' => 1364,
            'endTokenPos' => 143,
            'endFilePos' => 1493,
          ),
        ),
        'docComment' => '/**
 * Elements allowed by the Sanitizer standard in <head> as keys, including whether
 * they are safe or not as values (safe meaning no global display/audio/video impact).
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 46,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'BODY_ELEMENTS' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'name' => 'BODY_ELEMENTS',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[\'a\' => true, \'abbr\' => true, \'acronym\' => true, \'address\' => true, \'area\' => true, \'article\' => true, \'aside\' => true, \'audio\' => true, \'b\' => true, \'basefont\' => true, \'bdi\' => true, \'bdo\' => true, \'bgsound\' => false, \'big\' => true, \'blockquote\' => true, \'body\' => true, \'br\' => true, \'button\' => true, \'canvas\' => true, \'caption\' => true, \'center\' => true, \'cite\' => true, \'code\' => true, \'col\' => true, \'colgroup\' => true, \'command\' => true, \'data\' => true, \'datalist\' => true, \'dd\' => true, \'del\' => true, \'details\' => true, \'dfn\' => true, \'dialog\' => true, \'dir\' => true, \'div\' => true, \'dl\' => true, \'dt\' => true, \'em\' => true, \'fieldset\' => true, \'figcaption\' => true, \'figure\' => true, \'font\' => true, \'footer\' => true, \'form\' => false, \'h1\' => true, \'h2\' => true, \'h3\' => true, \'h4\' => true, \'h5\' => true, \'h6\' => true, \'header\' => true, \'hgroup\' => true, \'hr\' => true, \'html\' => true, \'i\' => true, \'image\' => true, \'img\' => true, \'input\' => false, \'ins\' => true, \'kbd\' => true, \'keygen\' => true, \'label\' => true, \'layer\' => true, \'legend\' => true, \'li\' => true, \'listing\' => true, \'main\' => true, \'map\' => true, \'mark\' => true, \'marquee\' => true, \'menu\' => true, \'meter\' => true, \'nav\' => true, \'nobr\' => true, \'ol\' => true, \'optgroup\' => true, \'option\' => true, \'output\' => true, \'p\' => true, \'picture\' => true, \'plaintext\' => true, \'popup\' => true, \'portal\' => true, \'pre\' => true, \'progress\' => true, \'q\' => true, \'rb\' => true, \'rp\' => true, \'rt\' => true, \'rtc\' => true, \'ruby\' => true, \'s\' => true, \'samp\' => true, \'section\' => true, \'select\' => false, \'selectmenu\' => false, \'slot\' => true, \'small\' => true, \'source\' => true, \'span\' => true, \'strike\' => true, \'strong\' => true, \'sub\' => true, \'summary\' => true, \'sup\' => true, \'table\' => true, \'tbody\' => true, \'td\' => true, \'template\' => true, \'textarea\' => false, \'tfoot\' => true, \'th\' => true, \'thead\' => true, \'time\' => true, \'tr\' => true, \'track\' => true, \'tt\' => true, \'u\' => true, \'ul\' => true, \'var\' => true, \'video\' => true, \'wbr\' => true, \'xmp\' => true]',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 182,
            'startTokenPos' => 156,
            'startFilePos' => 1724,
            'endTokenPos' => 1019,
            'endFilePos' => 4743,
          ),
        ),
        'docComment' => '/**
 * Elements allowed by the Sanitizer standard in <body> as keys, including whether
 * they are safe or not as values (safe meaning no global display/audio/video impact).
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
      'ATTRIBUTES' => 
      array (
        'declaringClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'implementingClassName' => 'Symfony\\Component\\HtmlSanitizer\\Reference\\W3CReference',
        'name' => 'ATTRIBUTES',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '[
    \'abbr\' => true,
    \'accept\' => true,
    \'accept-charset\' => true,
    \'accesskey\' => true,
    \'action\' => true,
    \'align\' => true,
    \'alink\' => true,
    \'allow\' => true,
    \'allowfullscreen\' => true,
    \'allowpaymentrequest\' => false,
    \'alt\' => true,
    \'anchor\' => true,
    \'archive\' => true,
    \'as\' => true,
    \'async\' => false,
    \'autocapitalize\' => false,
    \'autocomplete\' => false,
    \'autocorrect\' => false,
    \'autofocus\' => false,
    \'autopictureinpicture\' => false,
    \'autoplay\' => false,
    \'axis\' => true,
    \'background\' => false,
    \'behavior\' => true,
    \'bgcolor\' => false,
    \'border\' => false,
    \'bordercolor\' => false,
    \'capture\' => true,
    \'cellpadding\' => true,
    \'cellspacing\' => true,
    \'challenge\' => true,
    \'char\' => true,
    \'charoff\' => true,
    \'charset\' => true,
    \'checked\' => false,
    \'cite\' => true,
    \'class\' => false,
    \'classid\' => false,
    \'clear\' => true,
    \'code\' => true,
    \'codebase\' => true,
    \'codetype\' => true,
    \'color\' => false,
    \'cols\' => true,
    \'colspan\' => true,
    \'compact\' => true,
    \'content\' => true,
    \'contenteditable\' => false,
    \'controls\' => true,
    \'controlslist\' => true,
    \'conversiondestination\' => true,
    \'coords\' => true,
    \'crossorigin\' => true,
    \'csp\' => true,
    \'data\' => true,
    \'datetime\' => true,
    \'declare\' => true,
    \'decoding\' => true,
    \'default\' => true,
    \'defer\' => true,
    \'dir\' => true,
    \'direction\' => true,
    \'dirname\' => true,
    \'disabled\' => true,
    \'disablepictureinpicture\' => true,
    \'disableremoteplayback\' => true,
    \'disallowdocumentaccess\' => true,
    \'download\' => true,
    \'draggable\' => true,
    \'elementtiming\' => true,
    \'enctype\' => true,
    \'end\' => true,
    \'enterkeyhint\' => true,
    \'event\' => true,
    \'exportparts\' => true,
    \'face\' => true,
    \'for\' => true,
    \'form\' => false,
    \'formaction\' => false,
    \'formenctype\' => false,
    \'formmethod\' => false,
    \'formnovalidate\' => false,
    \'formtarget\' => false,
    \'frame\' => false,
    \'frameborder\' => false,
    \'headers\' => true,
    \'height\' => true,
    \'hidden\' => false,
    \'high\' => true,
    \'href\' => true,
    \'hreflang\' => true,
    \'hreftranslate\' => true,
    \'hspace\' => true,
    \'http-equiv\' => false,
    \'id\' => true,
    \'imagesizes\' => true,
    \'imagesrcset\' => true,
    \'importance\' => true,
    \'impressiondata\' => true,
    \'impressionexpiry\' => true,
    \'incremental\' => true,
    \'inert\' => true,
    \'inputmode\' => true,
    \'integrity\' => true,
    \'invisible\' => true,
    \'is\' => true,
    \'ismap\' => true,
    \'keytype\' => true,
    \'kind\' => true,
    \'label\' => true,
    \'lang\' => true,
    \'language\' => true,
    \'latencyhint\' => true,
    \'leftmargin\' => true,
    \'link\' => true,
    \'list\' => true,
    \'loading\' => true,
    \'longdesc\' => true,
    \'loop\' => true,
    \'low\' => true,
    \'lowsrc\' => true,
    \'manifest\' => true,
    \'marginheight\' => true,
    \'marginwidth\' => true,
    \'max\' => true,
    \'maxlength\' => true,
    \'mayscript\' => true,
    \'media\' => true,
    \'method\' => true,
    \'min\' => true,
    \'minlength\' => true,
    \'multiple\' => true,
    \'muted\' => true,
    \'name\' => true,
    \'nohref\' => true,
    \'nomodule\' => true,
    \'nonce\' => true,
    \'noresize\' => true,
    \'noshade\' => true,
    \'novalidate\' => true,
    \'nowrap\' => true,
    \'object\' => true,
    \'open\' => true,
    \'optimum\' => true,
    \'part\' => true,
    \'pattern\' => true,
    \'ping\' => false,
    \'placeholder\' => true,
    \'playsinline\' => true,
    \'policy\' => true,
    \'poster\' => true,
    \'preload\' => true,
    \'pseudo\' => true,
    \'readonly\' => true,
    \'referrerpolicy\' => true,
    \'rel\' => true,
    \'reportingorigin\' => true,
    \'required\' => true,
    \'resources\' => true,
    \'rev\' => true,
    \'reversed\' => true,
    \'role\' => true,
    \'rows\' => true,
    \'rowspan\' => true,
    \'rules\' => true,
    \'sandbox\' => true,
    \'scheme\' => true,
    \'scope\' => true,
    \'scopes\' => true,
    \'scrollamount\' => true,
    \'scrolldelay\' => true,
    \'scrolling\' => true,
    \'select\' => false,
    \'selected\' => false,
    \'shadowroot\' => true,
    \'shadowrootdelegatesfocus\' => true,
    \'shape\' => true,
    \'size\' => true,
    \'sizes\' => true,
    \'slot\' => true,
    \'span\' => true,
    \'spellcheck\' => true,
    \'src\' => true,
    // \'srcdoc\' => false, // XSS vector if not properly sandboxed, should be enabled explicitly with ->allowAttribute(\'srcdoc\', \'iframe\')->forceAttribute(\'iframe\', \'sandbox\', \'\')
    \'srclang\' => true,
    \'srcset\' => true,
    \'standby\' => true,
    \'start\' => true,
    \'step\' => true,
    \'style\' => false,
    \'summary\' => true,
    \'tabindex\' => true,
    \'target\' => true,
    \'text\' => true,
    \'title\' => true,
    \'topmargin\' => true,
    \'translate\' => true,
    \'truespeed\' => true,
    \'trusttoken\' => true,
    \'type\' => true,
    \'usemap\' => true,
    \'valign\' => true,
    \'value\' => false,
    \'valuetype\' => true,
    \'version\' => true,
    \'virtualkeyboardpolicy\' => true,
    \'vlink\' => false,
    \'vspace\' => true,
    \'webkitdirectory\' => true,
    \'width\' => true,
    \'wrap\' => true,
]',
          'attributes' => 
          array (
            'startLine' => 187,
            'endLine' => 399,
            'startTokenPos' => 1032,
            'startFilePos' => 4836,
            'endTokenPos' => 2506,
            'endFilePos' => 10885,
          ),
        ),
        'docComment' => '/**
 * Attributes allowed by the standard.
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 187,
        'endLine' => 399,
        'startColumn' => 5,
        'endColumn' => 6,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
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