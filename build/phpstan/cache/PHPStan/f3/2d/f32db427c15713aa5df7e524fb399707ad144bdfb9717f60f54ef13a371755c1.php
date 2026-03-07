<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../voku/anti-xss/src/voku/helper/AntiXSS.php-PHPStan\BetterReflection\Reflection\ReflectionClass-voku\helper\AntiXSS
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-be23a654e9d0241ce54fcae1381f7bb6fd28f6fc12afd643ee3de8f17c9cf638-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'voku\\helper\\AntiXSS',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../voku/anti-xss/src/voku/helper/AntiXSS.php',
      ),
    ),
    'namespace' => 'voku\\helper',
    'name' => 'voku\\helper\\AntiXSS',
    'shortName' => 'AntiXSS',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => '/**
 * AntiXSS - ported from "CodeIgniter"
 *
 * ## 🇷🇺 Русским гражданам
 * В Украине сейчас идет война. Силами РФ наносятся удары по гражданской инфраструктуре в [Харькове][1], [Киеве][2], [Чернигове][3], [Сумах][4], [Ирпене][5] и десятках других городов. Гибнут люди - и гражданское население, и военные, в том числе российские призывники, которых бросили воевать. Чтобы лишить собственный народ доступа к информации, правительство РФ запретило называть войну войной, закрыло независимые СМИ и принимает сейчас ряд диктаторских законов. Эти законы призваны заткнуть рот всем, кто против войны. За обычный призыв к миру сейчас можно получить несколько лет тюрьмы.
 * Не молчите! Молчание - знак вашего согласия с политикой российского правительства.
 *  **Вы можете сделать выбор НЕ МОЛЧАТЬ.**
 * ---
 * ## 🇺🇸 To people of Russia
 * There is a war in Ukraine right now. The forces of the Russian Federation are attacking civilian infrastructure in [Kharkiv][1], [Kyiv][2], [Chernihiv][3], [Sumy][4], [Irpin][5] and dozens of other cities. People are dying – both civilians and military servicemen, including Russian conscripts who were thrown into the fighting. In order to deprive its own people of access to information, the government of the Russian Federation has forbidden calling a war a war, shut down independent media and is passing a number of dictatorial laws. These laws are meant to silence all those who are against war. You can be jailed for multiple years for simply calling for peace.
 * Do not be silent! Silence is a sign that you accept the Russian government\'s policy.
 * **You can choose NOT TO BE SILENT.**
 * ---
 * - [1] https://cloudfront-us-east-2.images.arcpublishing.com/reuters/P7K2MSZDGFMIJPDD7CI2GIROJI.jpg "Kharkiv under attack"
 * - [2] https://gdb.voanews.com/01bd0000-0aff-0242-fad0-08d9fc92c5b3_cx0_cy5_cw0_w1023_r1_s.jpg "Kyiv under attack"
 * - [3] https://ichef.bbci.co.uk/news/976/cpsprodpb/163DD/production/_123510119_hi074310744.jpg "Chernihiv under attack"
 * - [4] https://www.youtube.com/watch?v=8K-bkqKKf2A "Sumy under attack"
 * - [5] https://cloudfront-us-east-2.images.arcpublishing.com/reuters/K4MTMLEHTRKGFK3GSKAT4GR3NE.jpg "Irpin under attack"
 *
 * @copyright   Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright   Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @copyright   Copyright (c) 2015 - 2020, Lars Moelleken (https://moelleken.org/)
 * @license     http://opensource.org/licenses/MIT	MIT License
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 39,
    'endLine' => 2295,
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
      'VOKU_ANTI_XSS_GT' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => 'VOKU_ANTI_XSS_GT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'voku::anti-xss::gt\'',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 68,
            'startFilePos' => 3414,
            'endTokenPos' => 68,
            'endFilePos' => 3433,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'VOKU_ANTI_XSS_LT' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => 'VOKU_ANTI_XSS_LT',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'voku::anti-xss::lt\'',
          'attributes' => 
          array (
            'startLine' => 43,
            'endLine' => 43,
            'startTokenPos' => 77,
            'startFilePos' => 3466,
            'endTokenPos' => 77,
            'endFilePos' => 3485,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 43,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'VOKU_ANTI_XSS_STYLE' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => 'VOKU_ANTI_XSS_STYLE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'voku::anti-xss::STYLE\'',
          'attributes' => 
          array (
            'startLine' => 45,
            'endLine' => 45,
            'startTokenPos' => 86,
            'startFilePos' => 3521,
            'endTokenPos' => 86,
            'endFilePos' => 3543,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 45,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 56,
      ),
    ),
    'immediateProperties' => 
    array (
      '_never_allowed_regex' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_never_allowed_regex',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 97,
            'startFilePos' => 3676,
            'endTokenPos' => 98,
            'endFilePos' => 3677,
          ),
        ),
        'docComment' => '/**
 * List of never allowed regex replacements.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_do_not_close_html_tags' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_do_not_close_html_tags',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 59,
            'startTokenPos' => 109,
            'startFilePos' => 3824,
            'endTokenPos' => 110,
            'endFilePos' => 3825,
          ),
        ),
        'docComment' => '/**
 * List of html tags that will not close automatically.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_never_allowed_js_callback_regex' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_never_allowed_js_callback_regex',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'\\(?window\\)?\\.\', \'\\(?history\\)?\\.\', \'\\(?location\\)?\\.\', \'\\(?document\\)?\\.\', \'\\(?cookie\\)?\\.\', \'\\(?ScriptElement\\)?\\.\', \'d\\s*a\\s*t\\s*a\\s*:\']',
          'attributes' => 
          array (
            'startLine' => 66,
            'endLine' => 74,
            'startTokenPos' => 121,
            'startFilePos' => 3967,
            'endTokenPos' => 144,
            'endFilePos' => 4170,
          ),
        ),
        'docComment' => '/**
 * List of never allowed call statements.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 66,
        'endLine' => 74,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_never_allowed_call_strings' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_never_allowed_call_strings',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[
    // default javascript
    \'javascript\',
    // Java: jar-protocol is an XSS hazard
    \'jar\',
    // Mac (will not run the script, but open it in AppleScript Editor)
    \'applescript\',
    // IE: https://www.owasp.org/index.php/XSS_Filter_Evasion_Cheat_Sheet#VBscript_in_an_image
    \'vbscript\',
    \'vbs\',
    // IE, surprise!
    \'wscript\',
    // IE
    \'jscript\',
    // https://html5sec.org/#behavior
    \'behavior\',
    // old Netscape
    \'mocha\',
    // old Netscape
    \'livescript\',
    // default view source
    \'view-source\',
]',
          'attributes' => 
          array (
            'startLine' => 81,
            'endLine' => 103,
            'startTokenPos' => 155,
            'startFilePos' => 4318,
            'endTokenPos' => 210,
            'endFilePos' => 4951,
          ),
        ),
        'docComment' => '/**
 * List of simple never allowed call statements.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 81,
        'endLine' => 103,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_never_allowed_str_afterwards' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_never_allowed_str_afterwards',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'&lt;script&gt;\', \'&lt;/script&gt;\']',
          'attributes' => 
          array (
            'startLine' => 108,
            'endLine' => 111,
            'startTokenPos' => 221,
            'startFilePos' => 5037,
            'endTokenPos' => 229,
            'endFilePos' => 5096,
          ),
        ),
        'docComment' => '/**
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 108,
        'endLine' => 111,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_never_allowed_on_events_afterwards' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_never_allowed_on_events_afterwards',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'onAbort\', \'onActivate\', \'onAttribute\', \'onAfterPrint\', \'onAfterScriptExecute\', \'onAfterUpdate\', \'onAnimationCancel\', \'onAnimationEnd\', \'onAnimationIteration\', \'onAnimationStart\', \'onAriaRequest\', \'onAutoComplete\', \'onAutoCompleteError\', \'onAuxClick\', \'onBeforeActivate\', \'onBeforeCopy\', \'onBeforeCut\', \'onBeforeInput\', \'onBeforePrint\', \'onBeforeDeactivate\', \'onBeforeEditFocus\', \'onBeforePaste\', \'onBeforePrint\', \'onBeforeScriptExecute\', \'onBeforeToggle\', \'onBeforeUnload\', \'onBeforeUpdate\', \'onBegin\', \'onBlur\', \'onBounce\', \'onCancel\', \'onCanPlay\', \'onCanPlayThrough\', \'onCellChange\', \'onChange\', \'onClick\', \'onClose\', \'onCommand\', \'onCompassNeedsCalibration\', \'onContextMenu\', \'onControlSelect\', \'onCopy\', \'onCueChange\', \'onCut\', \'onDataAvailable\', \'onDataSetChanged\', \'onDataSetComplete\', \'onDblClick\', \'onDeactivate\', \'onDeviceLight\', \'onDeviceMotion\', \'onDeviceOrientation\', \'onDeviceProximity\', \'onDrag\', \'onDragDrop\', \'onDragEnd\', \'onDragExit\', \'onDragEnter\', \'onDragLeave\', \'onDragOver\', \'onDragStart\', \'onDrop\', \'onDurationChange\', \'onEmptied\', \'onEnd\', \'onEnded\', \'onError\', \'onErrorUpdate\', \'onExit\', \'onFilterChange\', \'onFinish\', \'onFocus\', \'onFocusIn\', \'onFocusOut\', \'onFormChange\', \'onFormInput\', \'onFullScreenChange\', \'onFullScreenError\', \'onGotPointerCapture\', \'onHashChange\', \'onHelp\', \'onInput\', \'onInvalid\', \'onKeyDown\', \'onKeyPress\', \'onKeyUp\', \'onLanguageChange\', \'onLayoutComplete\', \'onLoad\', \'onLoadEnd\', \'onLoadedData\', \'onLoadedMetaData\', \'onLoadStart\', \'onLoseCapture\', \'onLostPointerCapture\', \'onMediaComplete\', \'onMediaError\', \'onMessage\', \'onMouseDown\', \'onMouseEnter\', \'onMouseLeave\', \'onMouseMove\', \'onMouseOut\', \'onMouseOver\', \'onMouseUp\', \'onMouseWheel\', \'onMove\', \'onMoveEnd\', \'onMoveStart\', \'onMozFullScreenChange\', \'onMozFullScreenError\', \'onMozPointerLockChange\', \'onMozPointerLockError\', \'onMsContentZoom\', \'onMsFullScreenChange\', \'onMsFullScreenError\', \'onMsGestureChange\', \'onMsGestureDoubleTap\', \'onMsGestureEnd\', \'onMsGestureHold\', \'onMsGestureStart\', \'onMsGestureTap\', \'onMsGotPointerCapture\', \'onMsInertiaStart\', \'onMsLostPointerCapture\', \'onMsManipulationStateChanged\', \'onMsPointerCancel\', \'onMsPointerDown\', \'onMsPointerEnter\', \'onMsPointerLeave\', \'onMsPointerMove\', \'onMsPointerOut\', \'onMsPointerOver\', \'onMsPointerUp\', \'onMsSiteModeJumpListItemRemoved\', \'onMsThumbnailClick\', \'onOffline\', \'onOnline\', \'onOutOfSync\', \'onPage\', \'onPageHide\', \'onPageShow\', \'onPaste\', \'onPause\', \'onPlay\', \'onPlaying\', \'onPointerCancel\', \'onPointerDown\', \'onPointerEnter\', \'onPointerLeave\', \'onPointerLockChange\', \'onPointerLockError\', \'onPointerMove\', \'onPointerOut\', \'onPointerOver\', \'onPointerRawUpdate\', \'onPointerUp\', \'onPopState\', \'onProgress\', \'onPropertyChange\', \'onqt_error\', \'onRateChange\', \'onReadyStateChange\', \'onReceived\', \'onRepeat\', \'onReset\', \'onResize\', \'onResizeEnd\', \'onResizeStart\', \'onResume\', \'onReverse\', \'onRowDelete\', \'onRowEnter\', \'onRowExit\', \'onRowInserted\', \'onRowsDelete\', \'onRowsEnter\', \'onRowsExit\', \'onRowsInserted\', \'onScroll\', \'onSearch\', \'onSeek\', \'onSeeked\', \'onSeeking\', \'onSelect\', \'onSelectionChange\', \'onSelectStart\', \'onStalled\', \'onStorage\', \'onStorageCommit\', \'onStart\', \'onStop\', \'onShow\', \'onSyncRestored\', \'onSubmit\', \'onSuspend\', \'onSynchRestored\', \'onTimeError\', \'onTimeUpdate\', \'onTimer\', \'onTrackChange\', \'onTransitionEnd\', \'onTransitionRun\', \'onTransitionStart\', \'onToggle\', \'onTouchCancel\', \'onTouchEnd\', \'onTouchLeave\', \'onTouchMove\', \'onTouchStart\', \'onTransitionCancel\', \'onTransitionEnd\', \'onUnload\', \'onUnhandledRejection\', \'onURLFlip\', \'onUserProximity\', \'onVolumeChange\', \'onWaiting\', \'onWebKitAnimationEnd\', \'onWebKitAnimationIteration\', \'onWebKitAnimationStart\', \'onWebKitFullScreenChange\', \'onWebKitFullScreenError\', \'onWebKitTransitionEnd\', \'onWheel\']',
          'attributes' => 
          array (
            'startLine' => 118,
            'endLine' => 344,
            'startTokenPos' => 240,
            'startFilePos' => 5245,
            'endTokenPos' => 917,
            'endFilePos' => 10798,
          ),
        ),
        'docComment' => '/**
 * List of never allowed strings, afterwards.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 118,
        'endLine' => 344,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_evil_attributes_regex' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_evil_attributes_regex',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'style\', \'xmlns:xdp\', \'formaction\', \'form\', \'xlink:href\', \'seekSegmentTime\', \'FSCommand\']',
          'attributes' => 
          array (
            'startLine' => 351,
            'endLine' => 359,
            'startTokenPos' => 928,
            'startFilePos' => 10969,
            'endTokenPos' => 951,
            'endFilePos' => 11121,
          ),
        ),
        'docComment' => '/**
 * https://www.owasp.org/index.php/XSS_Filter_Evasion_Cheat_Sheet#Event_Handlers
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 351,
        'endLine' => 359,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_evil_html_tags' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_evil_html_tags',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'applet\', \'audio\', \'basefont\', \'base\', \'behavior\', \'bgsound\', \'blink\', \'body\', \'embed\', \'eval\', \'expression\', \'form\', \'frameset\', \'frame\', \'head\', \'html\', \'ilayer\', \'iframe\', \'input\', \'button\', \'select\', \'isindex\', \'layer\', \'link\', \'meta\', \'keygen\', \'object\', \'plaintext\', \'style\', \'script\', \'textarea\', \'title\', \'math\', \'noscript\', \'event-source\', \'vmlframe\', \'video\', \'source\', \'svg\', \'xml\']',
          'attributes' => 
          array (
            'startLine' => 364,
            'endLine' => 405,
            'startTokenPos' => 962,
            'startFilePos' => 11193,
            'endTokenPos' => 1084,
            'endFilePos' => 11913,
          ),
        ),
        'docComment' => '/**
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 364,
        'endLine' => 405,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_spacing_regex' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_spacing_regex',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'(?:\\s|"|\\\'|\\+|&#x0[9A-F];|%0[9a-f])*?\'',
          'attributes' => 
          array (
            'startLine' => 410,
            'endLine' => 410,
            'startTokenPos' => 1095,
            'startFilePos' => 11982,
            'endTokenPos' => 1095,
            'endFilePos' => 12020,
          ),
        ),
        'docComment' => '/**
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 410,
        'endLine' => 410,
        'startColumn' => 5,
        'endColumn' => 70,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_replacement' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_replacement',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 417,
            'endLine' => 417,
            'startTokenPos' => 1106,
            'startFilePos' => 12149,
            'endTokenPos' => 1106,
            'endFilePos' => 12150,
          ),
        ),
        'docComment' => '/**
 * The replacement-string for not allowed strings.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 417,
        'endLine' => 417,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_never_allowed_str' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_never_allowed_str',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 424,
            'endLine' => 424,
            'startTokenPos' => 1117,
            'startFilePos' => 12270,
            'endTokenPos' => 1118,
            'endFilePos' => 12271,
          ),
        ),
        'docComment' => '/**
 * List of never allowed strings.
 *
 * @var string[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 424,
        'endLine' => 424,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_stripe_4byte_chars' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_stripe_4byte_chars',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 432,
            'endLine' => 432,
            'startTokenPos' => 1129,
            'startFilePos' => 12509,
            'endTokenPos' => 1129,
            'endFilePos' => 12513,
          ),
        ),
        'docComment' => '/**
 * If your DB (MySQL) encoding is "utf8" and not "utf8mb4", then
 * you can\'t save 4-Bytes chars from UTF-8 and someone can create stored XSS-attacks.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 432,
        'endLine' => 432,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_xss_found' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_xss_found',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var bool|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 437,
        'endLine' => 437,
        'startColumn' => 5,
        'endColumn' => 24,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_cache_evil_attributes_regex_string' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_cache_evil_attributes_regex_string',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 442,
            'endLine' => 442,
            'startTokenPos' => 1147,
            'startFilePos' => 12667,
            'endTokenPos' => 1147,
            'endFilePos' => 12668,
          ),
        ),
        'docComment' => '/**
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 442,
        'endLine' => 442,
        'startColumn' => 5,
        'endColumn' => 54,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_cache_never_allowed_regex_string' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_cache_never_allowed_regex_string',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 447,
            'endLine' => 447,
            'startTokenPos' => 1158,
            'startFilePos' => 12756,
            'endTokenPos' => 1158,
            'endFilePos' => 12757,
          ),
        ),
        'docComment' => '/**
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 447,
        'endLine' => 447,
        'startColumn' => 5,
        'endColumn' => 52,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      '_cache__evil_html_tags_str' => 
      array (
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'name' => '_cache__evil_html_tags_str',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 452,
            'endLine' => 452,
            'startTokenPos' => 1169,
            'startFilePos' => 12838,
            'endTokenPos' => 1169,
            'endFilePos' => 12839,
          ),
        ),
        'docComment' => '/**
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 452,
        'endLine' => 452,
        'startColumn' => 5,
        'endColumn' => 45,
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
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * __construct()
 */',
        'startLine' => 457,
        'endLine' => 461,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_compact_exploded_javascript' => 
      array (
        'name' => '_compact_exploded_javascript',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 477,
            'endLine' => 477,
            'startColumn' => 51,
            'endColumn' => 54,
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
 * Compact any exploded words.
 *
 * <p>
 * <br />
 * INFO: This corrects words like:  j a v a s c r i p t
 * <br />
 * These words are compacted back to their correct state.
 * </p>
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 477,
        'endLine' => 537,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_compact_exploded_words_callback' => 
      array (
        'name' => '_compact_exploded_words_callback',
        'parameters' => 
        array (
          'matches' => 
          array (
            'name' => 'matches',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 551,
            'endLine' => 551,
            'startColumn' => 55,
            'endColumn' => 62,
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
 * Compact exploded words.
 *
 * <p>
 * <br />
 * INFO: Callback method for xss_clean() to remove whitespace from things like \'j a v a s c r i p t\'.
 * </p>
 *
 * @param string[] $matches
 *
 * @return  string
 */',
        'startLine' => 551,
        'endLine' => 558,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_decode_entity' => 
      array (
        'name' => '_decode_entity',
        'parameters' => 
        array (
          'match' => 
          array (
            'name' => 'match',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 567,
            'endLine' => 567,
            'startColumn' => 37,
            'endColumn' => 42,
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
 * HTML-Entity decode callback.
 *
 * @param string[] $match
 *
 * @return string
 */',
        'startLine' => 567,
        'endLine' => 615,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_decode_string' => 
      array (
        'name' => '_decode_string',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 624,
            'endLine' => 624,
            'startColumn' => 37,
            'endColumn' => 40,
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
 * Decode the html-tags but keep links without XSS.
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 624,
        'endLine' => 646,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_do' => 
      array (
        'name' => '_do',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 653,
            'endLine' => 653,
            'startColumn' => 26,
            'endColumn' => 29,
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
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 653,
        'endLine' => 731,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_do_never_allowed' => 
      array (
        'name' => '_do_never_allowed',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 740,
            'endLine' => 740,
            'startColumn' => 40,
            'endColumn' => 43,
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
 * Remove never allowed strings.
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 740,
        'endLine' => 802,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_get_never_allowed_on_events_afterwards_chunks' => 
      array (
        'name' => '_get_never_allowed_on_events_afterwards_chunks',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array
 *
 * @phpstan-return array<string, list<string>>
 */',
        'startLine' => 809,
        'endLine' => 819,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_do_never_allowed_afterwards' => 
      array (
        'name' => '_do_never_allowed_afterwards',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 833,
            'endLine' => 833,
            'startColumn' => 51,
            'endColumn' => 54,
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
 * Remove never allowed string, afterwards.
 *
 * <p>
 * <br />
 * INFO: clean-up also some string, if there is no html-tag
 * </p>
 *
 * @param string $str
 *
 * @return  string
 */',
        'startLine' => 833,
        'endLine' => 871,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_entity_decode' => 
      array (
        'name' => '_entity_decode',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 880,
            'endLine' => 880,
            'startColumn' => 37,
            'endColumn' => 40,
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
 * Entity-decoding.
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 880,
        'endLine' => 978,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_filter_attributes' => 
      array (
        'name' => '_filter_attributes',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 987,
            'endLine' => 987,
            'startColumn' => 41,
            'endColumn' => 44,
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
 * Filters tag attributes for consistency and safety.
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 987,
        'endLine' => 1009,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_get_data' => 
      array (
        'name' => '_get_data',
        'parameters' => 
        array (
          'file' => 
          array (
            'name' => 'file',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1020,
            'endLine' => 1020,
            'startColumn' => 39,
            'endColumn' => 43,
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
 * get data from "/data/*.php"
 *
 * @param string $file
 *
 * @return string[]
 *
 * @phpstan-return array<string, string>
 */',
        'startLine' => 1020,
        'endLine' => 1024,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_initNeverAllowedStr' => 
      array (
        'name' => '_initNeverAllowedStr',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * initialize "$this->_never_allowed_str"
 *
 * @return void
 */',
        'startLine' => 1031,
        'endLine' => 1049,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_initNeverAllowedRegex' => 
      array (
        'name' => '_initNeverAllowedRegex',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * initialize "$this->_never_allowed_regex"
 *
 * @return void
 */',
        'startLine' => 1056,
        'endLine' => 1071,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_js_link_removal_callback' => 
      array (
        'name' => '_js_link_removal_callback',
        'parameters' => 
        array (
          'match' => 
          array (
            'name' => 'match',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1087,
            'endLine' => 1087,
            'startColumn' => 48,
            'endColumn' => 53,
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
 * Callback method for xss_clean() to sanitize links.
 *
 * <p>
 * <br />
 * INFO: This limits the PCRE backtracks, making it more performance friendly
 * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
 * PHP 5.2+ on link-heavy strings.
 * </p>
 *
 * @param string[] $match
 *
 * @return string
 */',
        'startLine' => 1087,
        'endLine' => 1090,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_js_removal_callback' => 
      array (
        'name' => '_js_removal_callback',
        'parameters' => 
        array (
          'match' => 
          array (
            'name' => 'match',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1107,
            'endLine' => 1107,
            'startColumn' => 43,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'search' => 
          array (
            'name' => 'search',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1107,
            'endLine' => 1107,
            'startColumn' => 51,
            'endColumn' => 57,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Callback method for xss_clean() to sanitize tags.
 *
 * <p>
 * <br />
 * INFO: This limits the PCRE backtracks, making it more performance friendly
 * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
 * PHP 5.2+ on image tag heavy strings.
 * </p>
 *
 * @param string[]  $match
 * @param string $search
 *
 * @return string
 */',
        'startLine' => 1107,
        'endLine' => 1196,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_js_src_removal_callback' => 
      array (
        'name' => '_js_src_removal_callback',
        'parameters' => 
        array (
          'match' => 
          array (
            'name' => 'match',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1212,
            'endLine' => 1212,
            'startColumn' => 47,
            'endColumn' => 58,
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
 * Callback method for xss_clean() to sanitize image tags.
 *
 * <p>
 * <br />
 * INFO: This limits the PCRE backtracks, making it more performance friendly
 * and prevents PREG_BACKTRACK_LIMIT_ERROR from being triggered in
 * PHP 5.2+ on image tag heavy strings.
 * </p>
 *
 * @param string[] $match
 *
 * @return string
 */',
        'startLine' => 1212,
        'endLine' => 1215,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_remove_disallowed_javascript' => 
      array (
        'name' => '_remove_disallowed_javascript',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1240,
            'endLine' => 1240,
            'startColumn' => 52,
            'endColumn' => 55,
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
 * Remove disallowed Javascript in links or img tags
 *
 * <p>
 * <br />
 * We used to do some version comparisons and use of stripos(),
 * but it is dog slow compared to these simplified non-capturing
 * preg_match(), especially if the pattern exists in the string
 * </p>
 *
 * <p>
 * <br />
 * Note: It was reported that not only space characters, but all in
 * the following pattern can be parsed as separators between a tag name
 * and its attributes: [\\d\\s"\\\'`;,\\/\\=\\(\\x00\\x0B\\x09\\x0C]
 * ... however, UTF8::clean() above already strips the
 * hex-encoded ones, so we\'ll skip them below.
 * </p>
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 1240,
        'endLine' => 1371,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_remove_evil_attributes' => 
      array (
        'name' => '_remove_evil_attributes',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1395,
            'endLine' => 1395,
            'startColumn' => 46,
            'endColumn' => 49,
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
 * Remove Evil HTML Attributes (like event handlers and style).
 *
 * It removes the evil attribute and either:
 *
 *  - Everything up until a space. For example, everything between the pipes:
 *
 * <code>
 *   <a |style=document.write(\'hello\');alert(\'world\');| class=link>
 * </code>
 *
 *  - Everything inside the quotes. For example, everything between the pipes:
 *
 * <code>
 *   <a |style="document.write(\'hello\'); alert(\'world\');"| class="link">
 * </code>
 *
 * @param string $str <p>The string to check.</p>
 *
 * @return string
 *                <p>The string with the evil attributes removed.</p>
 */',
        'startLine' => 1395,
        'endLine' => 1470,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_repack_utf7' => 
      array (
        'name' => '_repack_utf7',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1479,
            'endLine' => 1479,
            'startColumn' => 35,
            'endColumn' => 38,
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
 * UTF-7 decoding function.
 *
 * @param string $str <p>HTML document for recode ASCII part of UTF-7 back to ASCII.</p>
 *
 * @return string
 */',
        'startLine' => 1479,
        'endLine' => 1492,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_repack_utf7_callback' => 
      array (
        'name' => '_repack_utf7_callback',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1501,
            'endLine' => 1501,
            'startColumn' => 44,
            'endColumn' => 51,
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
 * Additional UTF-7 decoding function.
 *
 * @param string[] $strings <p>Array of strings for recode ASCII part of UTF-7 back to ASCII.</p>
 *
 * @return string
 */',
        'startLine' => 1501,
        'endLine' => 1526,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_repack_utf7_callback_back' => 
      array (
        'name' => '_repack_utf7_callback_back',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1535,
            'endLine' => 1535,
            'startColumn' => 49,
            'endColumn' => 52,
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
 * Additional UTF-7 encoding function.
 *
 * @param string $str <p>String for recode ASCII part of UTF-7 back to ASCII.</p>
 *
 * @return string
 */',
        'startLine' => 1535,
        'endLine' => 1538,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_sanitize_naughty_html' => 
      array (
        'name' => '_sanitize_naughty_html',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1560,
            'endLine' => 1560,
            'startColumn' => 45,
            'endColumn' => 48,
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
 * Sanitize naughty HTML elements.
 *
 * <p>
 * <br />
 *
 * If a tag containing any of the words in the list
 * below is found, the tag gets converted to entities.
 *
 * <br /><br />
 *
 * So this: <blink>
 * <br />
 * Becomes: &lt;blink&gt;
 * </p>
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 1560,
        'endLine' => 1626,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_close_html_callback' => 
      array (
        'name' => '_close_html_callback',
        'parameters' => 
        array (
          'matches' => 
          array (
            'name' => 'matches',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1633,
            'endLine' => 1633,
            'startColumn' => 43,
            'endColumn' => 50,
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
 * @param string[] $matches
 *
 * @return string
 */',
        'startLine' => 1633,
        'endLine' => 1645,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_sanitize_naughty_html_callback' => 
      array (
        'name' => '_sanitize_naughty_html_callback',
        'parameters' => 
        array (
          'matches' => 
          array (
            'name' => 'matches',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1659,
            'endLine' => 1659,
            'startColumn' => 54,
            'endColumn' => 61,
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
 * Sanitize naughty HTML.
 *
 * <p>
 * <br />
 * Callback method for AntiXSS->sanitize_naughty_html() to remove naughty HTML elements.
 * </p>
 *
 * @param string[] $matches
 *
 * @return string
 */',
        'startLine' => 1659,
        'endLine' => 1700,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      '_sanitize_naughty_javascript' => 
      array (
        'name' => '_sanitize_naughty_javascript',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1725,
            'endLine' => 1725,
            'startColumn' => 51,
            'endColumn' => 54,
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
 * Sanitize naughty scripting elements
 *
 * <p>
 * <br />
 *
 * Similar to above, only instead of looking for
 * tags it looks for PHP and JavaScript commands
 * that are disallowed. Rather than removing the
 * code, it simply converts the parenthesis to entities
 * rendering the code un-executable.
 *
 * <br /><br />
 *
 * For example:  <pre>eval(\'some code\')</pre>
 * <br />
 * Becomes:      <pre>eval&#40;\'some code\'&#41;</pre>
 * </p>
 *
 * @param string $str
 *
 * @return string
 */',
        'startLine' => 1725,
        'endLine' => 1769,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 4,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'addEvilAttributes' => 
      array (
        'name' => 'addEvilAttributes',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1778,
            'endLine' => 1778,
            'startColumn' => 39,
            'endColumn' => 52,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add some strings to the "_evil_attributes"-array.
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 1778,
        'endLine' => 1793,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'addEvilHtmlTags' => 
      array (
        'name' => 'addEvilHtmlTags',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1802,
            'endLine' => 1802,
            'startColumn' => 37,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add some strings to the "_evil_html_tags"-array.
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 1802,
        'endLine' => 1817,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'addNeverAllowedRegex' => 
      array (
        'name' => 'addNeverAllowedRegex',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1826,
            'endLine' => 1826,
            'startColumn' => 42,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add some strings to the "_never_allowed_regex"-array.
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 1826,
        'endLine' => 1841,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'removeNeverAllowedRegex' => 
      array (
        'name' => 'removeNeverAllowedRegex',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1855,
            'endLine' => 1855,
            'startColumn' => 45,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove some strings from the "_never_allowed_regex"-array.
 *
 * <p>
 * <br />
 * WARNING: Use this method only if you have a really good reason.
 * </p>
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 1855,
        'endLine' => 1870,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'addNeverAllowedOnEventsAfterwards' => 
      array (
        'name' => 'addNeverAllowedOnEventsAfterwards',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1879,
            'endLine' => 1879,
            'startColumn' => 55,
            'endColumn' => 68,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add some strings to the "_never_allowed_on_events_afterwards"-array.
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 1879,
        'endLine' => 1894,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'addNeverAllowedStrAfterwards' => 
      array (
        'name' => 'addNeverAllowedStrAfterwards',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1903,
            'endLine' => 1903,
            'startColumn' => 50,
            'endColumn' => 63,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add some strings to the "_never_allowed_str_afterwards"-array.
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 1903,
        'endLine' => 1915,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'addDoNotCloseHtmlTags' => 
      array (
        'name' => 'addDoNotCloseHtmlTags',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1924,
            'endLine' => 1924,
            'startColumn' => 43,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add some strings to the "_do_not_close_html_tags"-array.
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 1924,
        'endLine' => 1936,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'addNeverAllowedJsCallbackRegex' => 
      array (
        'name' => 'addNeverAllowedJsCallbackRegex',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1945,
            'endLine' => 1945,
            'startColumn' => 52,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add some strings to the "_never_allowed_js_callback_regex"-array.
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 1945,
        'endLine' => 1957,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'addNeverAllowedCallStrings' => 
      array (
        'name' => 'addNeverAllowedCallStrings',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1966,
            'endLine' => 1966,
            'startColumn' => 48,
            'endColumn' => 61,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Add some strings to the "_never_allowed_call_strings"-array.
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 1966,
        'endLine' => 1978,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'removeDoNotCloseHtmlTags' => 
      array (
        'name' => 'removeDoNotCloseHtmlTags',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1992,
            'endLine' => 1992,
            'startColumn' => 46,
            'endColumn' => 59,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove some strings from the "_do_not_close_html_tags"-array.
 *
 * <p>
 * <br />
 * WARNING: Use this method only if you have a really good reason.
 * </p>
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 1992,
        'endLine' => 2004,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'isXssFound' => 
      array (
        'name' => 'isXssFound',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Check if the "AntiXSS->xss_clean()"-method found an XSS attack in the last run.
 *
 * @return bool|null
 *                   <p>Will return null if the "xss_clean()" wasn\'t running at all.</p>
 */',
        'startLine' => 2012,
        'endLine' => 2015,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'removeEvilAttributes' => 
      array (
        'name' => 'removeEvilAttributes',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2029,
            'endLine' => 2029,
            'startColumn' => 42,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove some strings from the "_evil_attributes"-array.
 *
 * <p>
 * <br />
 * WARNING: Use this method only if you have a really good reason.
 * </p>
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 2029,
        'endLine' => 2044,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'removeEvilHtmlTags' => 
      array (
        'name' => 'removeEvilHtmlTags',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2058,
            'endLine' => 2058,
            'startColumn' => 40,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove some strings from the "_evil_html_tags"-array.
 *
 * <p>
 * <br />
 * WARNING: Use this method only if you have a really good reason.
 * </p>
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 2058,
        'endLine' => 2073,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'removeNeverAllowedOnEventsAfterwards' => 
      array (
        'name' => 'removeNeverAllowedOnEventsAfterwards',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2087,
            'endLine' => 2087,
            'startColumn' => 58,
            'endColumn' => 71,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove some strings from the "_never_allowed_on_events_afterwards"-array.
 *
 * <p>
 * <br />
 * WARNING: Use this method only if you have a really good reason.
 * </p>
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 2087,
        'endLine' => 2102,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'removeNeverAllowedStrAfterwards' => 
      array (
        'name' => 'removeNeverAllowedStrAfterwards',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2116,
            'endLine' => 2116,
            'startColumn' => 53,
            'endColumn' => 66,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove some strings from the "_never_allowed_str_afterwards"-array.
 *
 * <p>
 * <br />
 * WARNING: Use this method only if you have a really good reason.
 * </p>
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 2116,
        'endLine' => 2128,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'removeNeverAllowedCallStrings' => 
      array (
        'name' => 'removeNeverAllowedCallStrings',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2142,
            'endLine' => 2142,
            'startColumn' => 51,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove some strings from the "_never_allowed_call_strings"-array.
 *
 * <p>
 * <br />
 * WARNING: Use this method only if you have a really good reason.
 * </p>
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 2142,
        'endLine' => 2154,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'removeNeverAllowedJsCallbackRegex' => 
      array (
        'name' => 'removeNeverAllowedJsCallbackRegex',
        'parameters' => 
        array (
          'strings' => 
          array (
            'name' => 'strings',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2168,
            'endLine' => 2168,
            'startColumn' => 55,
            'endColumn' => 68,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Remove some strings from the "_never_allowed_js_callback_regex"-array.
 *
 * <p>
 * <br />
 * WARNING: Use this method only if you have a really good reason.
 * </p>
 *
 * @param string[] $strings
 *
 * @return $this
 */',
        'startLine' => 2168,
        'endLine' => 2180,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'setReplacement' => 
      array (
        'name' => 'setReplacement',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2189,
            'endLine' => 2189,
            'startColumn' => 36,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the replacement-string for not allowed strings.
 *
 * @param string $string
 *
 * @return $this
 */',
        'startLine' => 2189,
        'endLine' => 2197,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'setStripe4byteChars' => 
      array (
        'name' => 'setStripe4byteChars',
        'parameters' => 
        array (
          'bool' => 
          array (
            'name' => 'bool',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2211,
            'endLine' => 2211,
            'startColumn' => 41,
            'endColumn' => 45,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'self',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Set the option to stripe 4-Byte chars.
 *
 * <p>
 * <br />
 * INFO: use it if your DB (MySQL) can\'t use "utf8mb4" -> preventing stored XSS-attacks
 * </p>
 *
 * @param bool $bool
 *
 * @return $this
 */',
        'startLine' => 2211,
        'endLine' => 2216,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
        'aliasName' => NULL,
      ),
      'xss_clean' => 
      array (
        'name' => 'xss_clean',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 2253,
            'endLine' => 2253,
            'startColumn' => 31,
            'endColumn' => 34,
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
 * XSS Clean
 *
 * <p>
 * <br />
 * Sanitizes data so that "Cross Site Scripting" hacks can be
 * prevented. This method does a fair amount of work but
 * it is extremely thorough, designed to prevent even the
 * most obscure XSS attempts. But keep in mind that nothing
 * is ever 100% foolproof...
 * </p>
 *
 * <p>
 * <br />
 * <strong>Note:</strong> Should only be used to deal with data upon submission.
 *   It\'s not something that should be used for general
 *   runtime processing.
 * </p>
 *
 * @see http://channel.bitflux.ch/wiki/XSS_Prevention
 *    Based in part on some code and ideas from Bitflux.
 * @see http://ha.ckers.org/xss.html
 *    To help develop this script I used this great list of
 *    vulnerabilities along with a few other hacks I\'ve
 *    harvested from examining vulnerabilities in other programs.
 *
 * @param string|string[] $str
 *                             <p>input data e.g. string or array of strings</p>
 *
 * @return string|string[]
 *
 * @template TXssCleanInput as string|string[]
 * @phpstan-param TXssCleanInput $str
 * @phpstan-return TXssCleanInput
 */',
        'startLine' => 2253,
        'endLine' => 2294,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'voku\\helper',
        'declaringClassName' => 'voku\\helper\\AntiXSS',
        'implementingClassName' => 'voku\\helper\\AntiXSS',
        'currentClassName' => 'voku\\helper\\AntiXSS',
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