<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../dompdf/dompdf/src/Options.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Dompdf\Options
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-df2c160cd437634360551348a6bf91cdffb28451b3294b6232eeda04f45fd146-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Dompdf\\Options',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../dompdf/dompdf/src/Options.php',
      ),
    ),
    'namespace' => 'Dompdf',
    'name' => 'Dompdf\\Options',
    'shortName' => 'Options',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 9,
    'endLine' => 1231,
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
      'rootDir' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'rootDir',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The root of your DOMPDF installation
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 16,
        'endLine' => 16,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'tempDir' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'tempDir',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The location of a temporary directory.
 *
 * The directory specified must be writable by the executing process.
 * The temporary directory is required to download remote images and when
 * using the PFDLib back end.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 27,
        'endLine' => 27,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fontDir' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'fontDir',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The location of the DOMPDF font directory
 *
 * The location of the directory where DOMPDF will store fonts and font metrics
 * Note: This directory must exist and be writable by the executing process.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 37,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fontCache' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'fontCache',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * The location of the DOMPDF font cache directory
 *
 * This directory contains the cached font metrics for the fonts used by DOMPDF.
 * This directory can be the same as $fontDir
 *
 * Note: This directory must exist and be writable by the executing process.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'chroot' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'chroot',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * dompdf\'s "chroot"
 *
 * Utilized by Dompdf\'s default file:// protocol URI validation rule.
 * All local files opened by dompdf must be in a subdirectory of the directory
 * or directories specified by this option.
 * DO NOT set this value to \'/\' since this could allow an attacker to use dompdf to
 * read any files on the server.  This should be an absolute path.
 *
 * ==== IMPORTANT ====
 * This setting may increase the risk of system exploit. Do not change
 * this settings without understanding the consequences. Additional
 * documentation is available on the dompdf wiki at:
 * https://github.com/dompdf/dompdf/wiki
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 68,
        'endLine' => 68,
        'startColumn' => 5,
        'endColumn' => 20,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'allowedProtocols' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'allowedProtocols',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '["data://" => ["rules" => []], "file://" => ["rules" => []], "http://" => ["rules" => []], "https://" => ["rules" => []]]',
          'attributes' => 
          array (
            'startLine' => 80,
            'endLine' => 85,
            'startTokenPos' => 57,
            'startFilePos' => 2313,
            'endTokenPos' => 114,
            'endFilePos' => 2471,
          ),
        ),
        'docComment' => '/**
 * Protocol whitelist
 *
 * Protocols and PHP wrappers allowed in URIs, and the validation rules
 * that determine if a resource may be loaded. Full support is not guaranteed
 * for the protocols/wrappers specified
 * by this array.
 *
 * @var array
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 80,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'artifactPathValidation' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'artifactPathValidation',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 92,
            'endLine' => 92,
            'startTokenPos' => 125,
            'startFilePos' => 2626,
            'endTokenPos' => 125,
            'endFilePos' => 2629,
          ),
        ),
        'docComment' => '/**
 * Operational artifact (log files, temporary files) path validation
 *
 * @var callable
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 92,
        'endLine' => 92,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'logOutputFile' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'logOutputFile',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 97,
            'endLine' => 97,
            'startTokenPos' => 136,
            'startFilePos' => 2697,
            'endTokenPos' => 136,
            'endFilePos' => 2698,
          ),
        ),
        'docComment' => '/**
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 97,
        'endLine' => 97,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultMediaType' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'defaultMediaType',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '"screen"',
          'attributes' => 
          array (
            'startLine' => 106,
            'endLine' => 106,
            'startTokenPos' => 147,
            'startFilePos' => 2966,
            'endTokenPos' => 147,
            'endFilePos' => 2973,
          ),
        ),
        'docComment' => '/**
 * Styles targeted to this media type are applied to the document.
 * This is on top of the media types that are always applied:
 *    all, static, visual, bitmap, paged, dompdf
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 106,
        'endLine' => 106,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultPaperSize' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'defaultPaperSize',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '"letter"',
          'attributes' => 
          array (
            'startLine' => 116,
            'endLine' => 116,
            'startTokenPos' => 158,
            'startFilePos' => 3233,
            'endTokenPos' => 158,
            'endFilePos' => 3240,
          ),
        ),
        'docComment' => '/**
 * The default paper size.
 *
 * North America standard is "letter"; other countries generally "a4"
 * @see \\Dompdf\\Adapter\\CPDF::PAPER_SIZES for valid sizes
 *
 * @var string|float[]
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 116,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultPaperOrientation' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'defaultPaperOrientation',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '"portrait"',
          'attributes' => 
          array (
            'startLine' => 125,
            'endLine' => 125,
            'startTokenPos' => 169,
            'startFilePos' => 3430,
            'endTokenPos' => 169,
            'endFilePos' => 3439,
          ),
        ),
        'docComment' => '/**
 * The default paper orientation.
 *
 * The orientation of the page (portrait or landscape).
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 125,
        'endLine' => 125,
        'startColumn' => 5,
        'endColumn' => 50,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'defaultFont' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'defaultFont',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '"serif"',
          'attributes' => 
          array (
            'startLine' => 134,
            'endLine' => 134,
            'startTokenPos' => 180,
            'startFilePos' => 3633,
            'endTokenPos' => 180,
            'endFilePos' => 3639,
          ),
        ),
        'docComment' => '/**
 * The default font family
 *
 * Used if no suitable fonts can be found. This must exist in the font folder.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 134,
        'endLine' => 134,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'dpi' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'dpi',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '96',
          'attributes' => 
          array (
            'startLine' => 157,
            'endLine' => 157,
            'startTokenPos' => 191,
            'startFilePos' => 4594,
            'endTokenPos' => 191,
            'endFilePos' => 4595,
          ),
        ),
        'docComment' => '/**
 * Image DPI setting
 *
 * This setting determines the default DPI setting for images and fonts.  The
 * DPI may be overridden for inline images by explicitly setting the
 * image\'s width & height style attributes (i.e. if the image\'s native
 * width is 600 pixels and you specify the image\'s width as 72 points,
 * the image will have a DPI of 600 in the rendered PDF.  The DPI of
 * background images can not be overridden and is controlled entirely
 * via this parameter.
 *
 * For the purposes of DOMPDF, pixels per inch (PPI) = dots per inch (DPI).
 * If a size in html is given as px (or without unit as image size),
 * this tells the corresponding size in pt at 72 DPI.
 * This adjusts the relative sizes to be similar to the rendering of the
 * html page in a reference browser.
 *
 * In pdf, always 1 pt = 1/72 inch
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 157,
        'endLine' => 157,
        'startColumn' => 5,
        'endColumn' => 22,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fontHeightRatio' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'fontHeightRatio',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '1.1',
          'attributes' => 
          array (
            'startLine' => 164,
            'endLine' => 164,
            'startTokenPos' => 202,
            'startFilePos' => 4752,
            'endTokenPos' => 202,
            'endFilePos' => 4754,
          ),
        ),
        'docComment' => '/**
 * A ratio applied to the fonts height to be more like browsers\' line height
 *
 * @var float
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 164,
        'endLine' => 164,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isPhpEnabled' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'isPhpEnabled',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 185,
            'endLine' => 185,
            'startTokenPos' => 213,
            'startFilePos' => 5604,
            'endTokenPos' => 213,
            'endFilePos' => 5608,
          ),
        ),
        'docComment' => '/**
 * Enable embedded PHP
 *
 * If this setting is set to true then DOMPDF will automatically evaluate
 * embedded PHP contained within <script type="text/php"> ... </script> tags.
 *
 * ==== IMPORTANT ====
 * Enabling this for documents you do not trust (e.g. arbitrary remote html
 * pages) is a security risk. Embedded scripts are run with the same level of
 * system access available to dompdf. Set this option to false (recommended)
 * if you wish to process untrusted documents.
 *
 * This setting may increase the risk of system exploit. Do not change
 * this settings without understanding the consequences. Additional
 * documentation is available on the dompdf wiki at:
 * https://github.com/dompdf/dompdf/wiki
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 185,
        'endLine' => 185,
        'startColumn' => 5,
        'endColumn' => 34,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isRemoteEnabled' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'isRemoteEnabled',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 207,
            'endLine' => 207,
            'startTokenPos' => 224,
            'startFilePos' => 6585,
            'endTokenPos' => 224,
            'endFilePos' => 6589,
          ),
        ),
        'docComment' => '/**
 * Enable remote file access
 *
 * If this setting is set to true, DOMPDF will access remote sites for
 * images and CSS files as required.
 *
 * ==== IMPORTANT ====
 * This can be a security risk, in particular in combination with isPhpEnabled and
 * allowing remote html code to be passed to $dompdf = new DOMPDF(); $dompdf->load_html(...);
 * This allows anonymous users to download legally doubtful internet content which on
 * tracing back appears to being downloaded by your server, or allows malicious php code
 * in remote html pages to be executed by your server with your account privileges.
 *
 * This setting may increase the risk of system exploit. Do not change
 * this settings without understanding the consequences. Additional
 * documentation is available on the dompdf wiki at:
 * https://github.com/dompdf/dompdf/wiki
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 207,
        'endLine' => 207,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'allowedRemoteHosts' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'allowedRemoteHosts',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 221,
            'endLine' => 221,
            'startTokenPos' => 235,
            'startFilePos' => 7003,
            'endTokenPos' => 235,
            'endFilePos' => 7006,
          ),
        ),
        'docComment' => '/**
 * List of allowed remote hosts
 *
 * Each value of the array must be a valid hostname.
 *
 * This will be used to filter which resources can be loaded in combination with
 * isRemoteEnabled. If isRemoteEnabled is FALSE, then this will have no effect.
 *
 * Leave to NULL to allow any remote host.
 *
 * @var array|null
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 221,
        'endLine' => 221,
        'startColumn' => 5,
        'endColumn' => 39,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isPdfAEnabled' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'isPdfAEnabled',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 235,
            'endLine' => 235,
            'startTokenPos' => 246,
            'startFilePos' => 7480,
            'endTokenPos' => 246,
            'endFilePos' => 7484,
          ),
        ),
        'docComment' => '/**
 * Enable PDF/A-3 compliance mode
 *
 * ==== EXPERIMENTAL ====
 * This feature is currently only supported with the CPDF backend and will
 * have no effect if used with any other.
 *
 * Currently this mode only takes care of adding the necessary metadata, output intents, etc.
 * It does not enforce font embedding, it\'s up to you to embed the fonts you plan on using.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 235,
        'endLine' => 235,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isJavascriptEnabled' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'isJavascriptEnabled',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 249,
            'endLine' => 249,
            'startTokenPos' => 257,
            'startFilePos' => 7940,
            'endTokenPos' => 257,
            'endFilePos' => 7943,
          ),
        ),
        'docComment' => '/**
 * Enable inline JavaScript
 *
 * If this setting is set to true then DOMPDF will automatically insert
 * JavaScript code contained within <script type="text/javascript"> ... </script>
 * tags as written into the PDF.
 *
 * NOTE: This is PDF-based JavaScript to be executed by the PDF viewer,
 * not browser-based JavaScript executed by Dompdf.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 249,
        'endLine' => 249,
        'startColumn' => 5,
        'endColumn' => 40,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isHtml5ParserEnabled' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'isHtml5ParserEnabled',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 257,
            'endLine' => 257,
            'startTokenPos' => 268,
            'startFilePos' => 8074,
            'endTokenPos' => 268,
            'endFilePos' => 8077,
          ),
        ),
        'docComment' => '/**
 * Use the HTML5 Lib parser
 *
 * @deprecated
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 257,
        'endLine' => 257,
        'startColumn' => 5,
        'endColumn' => 41,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'isFontSubsettingEnabled' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'isFontSubsettingEnabled',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 264,
            'endLine' => 264,
            'startTokenPos' => 279,
            'startFilePos' => 8209,
            'endTokenPos' => 279,
            'endFilePos' => 8212,
          ),
        ),
        'docComment' => '/**
 * Whether to enable font subsetting or not.
 *
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 264,
        'endLine' => 264,
        'startColumn' => 5,
        'endColumn' => 44,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'debugPng' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'debugPng',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 269,
            'endLine' => 269,
            'startTokenPos' => 290,
            'startFilePos' => 8273,
            'endTokenPos' => 290,
            'endFilePos' => 8277,
          ),
        ),
        'docComment' => '/**
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 269,
        'endLine' => 269,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'debugKeepTemp' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'debugKeepTemp',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 274,
            'endLine' => 274,
            'startTokenPos' => 301,
            'startFilePos' => 8343,
            'endTokenPos' => 301,
            'endFilePos' => 8347,
          ),
        ),
        'docComment' => '/**
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 274,
        'endLine' => 274,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'debugCss' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'debugCss',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 279,
            'endLine' => 279,
            'startTokenPos' => 312,
            'startFilePos' => 8408,
            'endTokenPos' => 312,
            'endFilePos' => 8412,
          ),
        ),
        'docComment' => '/**
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 279,
        'endLine' => 279,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'debugLayout' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'debugLayout',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'false',
          'attributes' => 
          array (
            'startLine' => 284,
            'endLine' => 284,
            'startTokenPos' => 323,
            'startFilePos' => 8476,
            'endTokenPos' => 323,
            'endFilePos' => 8480,
          ),
        ),
        'docComment' => '/**
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 284,
        'endLine' => 284,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'debugLayoutLines' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'debugLayoutLines',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 289,
            'endLine' => 289,
            'startTokenPos' => 334,
            'startFilePos' => 8549,
            'endTokenPos' => 334,
            'endFilePos' => 8552,
          ),
        ),
        'docComment' => '/**
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 289,
        'endLine' => 289,
        'startColumn' => 5,
        'endColumn' => 37,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'debugLayoutBlocks' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'debugLayoutBlocks',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 294,
            'endLine' => 294,
            'startTokenPos' => 345,
            'startFilePos' => 8622,
            'endTokenPos' => 345,
            'endFilePos' => 8625,
          ),
        ),
        'docComment' => '/**
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 294,
        'endLine' => 294,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'debugLayoutInline' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'debugLayoutInline',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 299,
            'endLine' => 299,
            'startTokenPos' => 356,
            'startFilePos' => 8695,
            'endTokenPos' => 356,
            'endFilePos' => 8698,
          ),
        ),
        'docComment' => '/**
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 299,
        'endLine' => 299,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'debugLayoutPaddingBox' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'debugLayoutPaddingBox',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'true',
          'attributes' => 
          array (
            'startLine' => 304,
            'endLine' => 304,
            'startTokenPos' => 367,
            'startFilePos' => 8772,
            'endTokenPos' => 367,
            'endFilePos' => 8775,
          ),
        ),
        'docComment' => '/**
 * @var bool
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 304,
        'endLine' => 304,
        'startColumn' => 5,
        'endColumn' => 42,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'pdfBackend' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'pdfBackend',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '"CPDF"',
          'attributes' => 
          array (
            'startLine' => 317,
            'endLine' => 317,
            'startTokenPos' => 378,
            'startFilePos' => 9217,
            'endTokenPos' => 378,
            'endFilePos' => 9222,
          ),
        ),
        'docComment' => '/**
 * The PDF rendering backend to use
 *
 * Valid settings are \'PDFLib\', \'CPDF\', \'GD\', and \'auto\'. \'auto\' will
 * look for PDFLib and use it if found, or if not it will fall back on
 * CPDF. \'GD\' renders PDFs to graphic files. {@link Dompdf\\CanvasFactory}
 * ultimately determines which rendering class to instantiate
 * based on this setting.
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 317,
        'endLine' => 317,
        'startColumn' => 5,
        'endColumn' => 33,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'pdflibLicense' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'pdflibLicense',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => '""',
          'attributes' => 
          array (
            'startLine' => 333,
            'endLine' => 333,
            'startTokenPos' => 389,
            'startFilePos' => 9715,
            'endTokenPos' => 389,
            'endFilePos' => 9716,
          ),
        ),
        'docComment' => '/**
 * PDFlib license key
 *
 * If you are using a licensed, commercial version of PDFlib, specify
 * your license key here.  If you are using PDFlib-Lite or are evaluating
 * the commercial version of PDFlib, comment out this setting.
 *
 * @link http://www.pdflib.com
 *
 * If pdflib present in web server and auto or selected explicitly above,
 * a real license code must exist!
 *
 * @var string
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 333,
        'endLine' => 333,
        'startColumn' => 5,
        'endColumn' => 32,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'httpContext' => 
      array (
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'name' => 'httpContext',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * HTTP context created with stream_context_create()
 * Will be used for file_get_contents
 *
 * @link https://www.php.net/manual/context.php
 *
 * @var resource
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 343,
        'endLine' => 343,
        'startColumn' => 5,
        'endColumn' => 25,
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
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 348,
                'endLine' => 348,
                'startTokenPos' => 414,
                'startFilePos' => 10050,
                'endTokenPos' => 414,
                'endFilePos' => 10053,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 348,
            'endLine' => 348,
            'startColumn' => 33,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param array $attributes
 */',
        'startLine' => 348,
        'endLine' => 379,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'set' => 
      array (
        'name' => 'set',
        'parameters' => 
        array (
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 386,
            'endLine' => 386,
            'startColumn' => 25,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 386,
                'endLine' => 386,
                'startTokenPos' => 677,
                'startFilePos' => 11271,
                'endTokenPos' => 677,
                'endFilePos' => 11274,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 386,
            'endLine' => 386,
            'startColumn' => 38,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param array|string $attributes
 * @param null|mixed $value
 * @return $this
 */',
        'startLine' => 386,
        'endLine' => 416,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'get' => 
      array (
        'name' => 'get',
        'parameters' => 
        array (
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 422,
            'endLine' => 422,
            'startColumn' => 25,
            'endColumn' => 28,
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
 * @param string $key
 * @return mixed
 */',
        'startLine' => 422,
        'endLine' => 446,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setPdfBackend' => 
      array (
        'name' => 'setPdfBackend',
        'parameters' => 
        array (
          'pdfBackend' => 
          array (
            'name' => 'pdfBackend',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 452,
            'endLine' => 452,
            'startColumn' => 35,
            'endColumn' => 45,
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
 * @param string $pdfBackend
 * @return $this
 */',
        'startLine' => 452,
        'endLine' => 456,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getPdfBackend' => 
      array (
        'name' => 'getPdfBackend',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 461,
        'endLine' => 464,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setPdflibLicense' => 
      array (
        'name' => 'setPdflibLicense',
        'parameters' => 
        array (
          'pdflibLicense' => 
          array (
            'name' => 'pdflibLicense',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 470,
            'endLine' => 470,
            'startColumn' => 38,
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
 * @param string $pdflibLicense
 * @return $this
 */',
        'startLine' => 470,
        'endLine' => 474,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getPdflibLicense' => 
      array (
        'name' => 'getPdflibLicense',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 479,
        'endLine' => 482,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setChroot' => 
      array (
        'name' => 'setChroot',
        'parameters' => 
        array (
          'chroot' => 
          array (
            'name' => 'chroot',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 488,
            'endLine' => 488,
            'startColumn' => 31,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'delimiter' => 
          array (
            'name' => 'delimiter',
            'default' => 
            array (
              'code' => '\',\'',
              'attributes' => 
              array (
                'startLine' => 488,
                'endLine' => 488,
                'startTokenPos' => 1264,
                'startFilePos' => 14329,
                'endTokenPos' => 1264,
                'endFilePos' => 14331,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 488,
            'endLine' => 488,
            'startColumn' => 40,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param array|string $chroot
 * @return $this
 */',
        'startLine' => 488,
        'endLine' => 496,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getAllowedProtocols' => 
      array (
        'name' => 'getAllowedProtocols',
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
 */',
        'startLine' => 501,
        'endLine' => 504,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setAllowedProtocols' => 
      array (
        'name' => 'setAllowedProtocols',
        'parameters' => 
        array (
          'allowedProtocols' => 
          array (
            'name' => 'allowedProtocols',
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
            'startLine' => 513,
            'endLine' => 513,
            'startColumn' => 41,
            'endColumn' => 63,
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
 * @param array $allowedProtocols The protocols to allow, as an array
 * formatted as ["protocol://" => ["rules" => [callable]], ...]
 * or ["protocol://", ...]
 *
 * @return $this
 */',
        'startLine' => 513,
        'endLine' => 531,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'addAllowedProtocol' => 
      array (
        'name' => 'addAllowedProtocol',
        'parameters' => 
        array (
          'protocol' => 
          array (
            'name' => 'protocol',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 540,
            'endLine' => 540,
            'startColumn' => 40,
            'endColumn' => 55,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'rules' => 
          array (
            'name' => 'rules',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'callable',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => true,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 540,
            'endLine' => 540,
            'startColumn' => 58,
            'endColumn' => 75,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Adds a new protocol to the allowed protocols collection
 *
 * @param string $protocol The scheme to add (e.g. "http://")
 * @param callable $rule A callable that validates the protocol
 * @return $this
 */',
        'startLine' => 540,
        'endLine' => 562,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getArtifactPathValidation' => 
      array (
        'name' => 'getArtifactPathValidation',
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
 */',
        'startLine' => 567,
        'endLine' => 570,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setArtifactPathValidation' => 
      array (
        'name' => 'setArtifactPathValidation',
        'parameters' => 
        array (
          'validator' => 
          array (
            'name' => 'validator',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 576,
            'endLine' => 576,
            'startColumn' => 47,
            'endColumn' => 56,
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
 * @param callable $validator
 * @return $this
 */',
        'startLine' => 576,
        'endLine' => 580,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getChroot' => 
      array (
        'name' => 'getChroot',
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
 */',
        'startLine' => 585,
        'endLine' => 592,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDebugCss' => 
      array (
        'name' => 'setDebugCss',
        'parameters' => 
        array (
          'debugCss' => 
          array (
            'name' => 'debugCss',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 598,
            'endLine' => 598,
            'startColumn' => 33,
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
 * @param boolean $debugCss
 * @return $this
 */',
        'startLine' => 598,
        'endLine' => 602,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDebugCss' => 
      array (
        'name' => 'getDebugCss',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 607,
        'endLine' => 610,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDebugKeepTemp' => 
      array (
        'name' => 'setDebugKeepTemp',
        'parameters' => 
        array (
          'debugKeepTemp' => 
          array (
            'name' => 'debugKeepTemp',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 616,
            'endLine' => 616,
            'startColumn' => 38,
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
 * @param boolean $debugKeepTemp
 * @return $this
 */',
        'startLine' => 616,
        'endLine' => 620,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDebugKeepTemp' => 
      array (
        'name' => 'getDebugKeepTemp',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 625,
        'endLine' => 628,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDebugLayout' => 
      array (
        'name' => 'setDebugLayout',
        'parameters' => 
        array (
          'debugLayout' => 
          array (
            'name' => 'debugLayout',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 634,
            'endLine' => 634,
            'startColumn' => 36,
            'endColumn' => 47,
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
 * @param boolean $debugLayout
 * @return $this
 */',
        'startLine' => 634,
        'endLine' => 638,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDebugLayout' => 
      array (
        'name' => 'getDebugLayout',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 643,
        'endLine' => 646,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDebugLayoutBlocks' => 
      array (
        'name' => 'setDebugLayoutBlocks',
        'parameters' => 
        array (
          'debugLayoutBlocks' => 
          array (
            'name' => 'debugLayoutBlocks',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 652,
            'endLine' => 652,
            'startColumn' => 42,
            'endColumn' => 59,
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
 * @param boolean $debugLayoutBlocks
 * @return $this
 */',
        'startLine' => 652,
        'endLine' => 656,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDebugLayoutBlocks' => 
      array (
        'name' => 'getDebugLayoutBlocks',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 661,
        'endLine' => 664,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDebugLayoutInline' => 
      array (
        'name' => 'setDebugLayoutInline',
        'parameters' => 
        array (
          'debugLayoutInline' => 
          array (
            'name' => 'debugLayoutInline',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 670,
            'endLine' => 670,
            'startColumn' => 42,
            'endColumn' => 59,
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
 * @param boolean $debugLayoutInline
 * @return $this
 */',
        'startLine' => 670,
        'endLine' => 674,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDebugLayoutInline' => 
      array (
        'name' => 'getDebugLayoutInline',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 679,
        'endLine' => 682,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDebugLayoutLines' => 
      array (
        'name' => 'setDebugLayoutLines',
        'parameters' => 
        array (
          'debugLayoutLines' => 
          array (
            'name' => 'debugLayoutLines',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 688,
            'endLine' => 688,
            'startColumn' => 41,
            'endColumn' => 57,
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
 * @param boolean $debugLayoutLines
 * @return $this
 */',
        'startLine' => 688,
        'endLine' => 692,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDebugLayoutLines' => 
      array (
        'name' => 'getDebugLayoutLines',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 697,
        'endLine' => 700,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDebugLayoutPaddingBox' => 
      array (
        'name' => 'setDebugLayoutPaddingBox',
        'parameters' => 
        array (
          'debugLayoutPaddingBox' => 
          array (
            'name' => 'debugLayoutPaddingBox',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 706,
            'endLine' => 706,
            'startColumn' => 46,
            'endColumn' => 67,
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
 * @param boolean $debugLayoutPaddingBox
 * @return $this
 */',
        'startLine' => 706,
        'endLine' => 710,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDebugLayoutPaddingBox' => 
      array (
        'name' => 'getDebugLayoutPaddingBox',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 715,
        'endLine' => 718,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDebugPng' => 
      array (
        'name' => 'setDebugPng',
        'parameters' => 
        array (
          'debugPng' => 
          array (
            'name' => 'debugPng',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 724,
            'endLine' => 724,
            'startColumn' => 33,
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
 * @param boolean $debugPng
 * @return $this
 */',
        'startLine' => 724,
        'endLine' => 728,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDebugPng' => 
      array (
        'name' => 'getDebugPng',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 733,
        'endLine' => 736,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDefaultFont' => 
      array (
        'name' => 'setDefaultFont',
        'parameters' => 
        array (
          'defaultFont' => 
          array (
            'name' => 'defaultFont',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 742,
            'endLine' => 742,
            'startColumn' => 36,
            'endColumn' => 47,
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
 * @param string $defaultFont
 * @return $this
 */',
        'startLine' => 742,
        'endLine' => 750,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDefaultFont' => 
      array (
        'name' => 'getDefaultFont',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 755,
        'endLine' => 758,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDefaultMediaType' => 
      array (
        'name' => 'setDefaultMediaType',
        'parameters' => 
        array (
          'defaultMediaType' => 
          array (
            'name' => 'defaultMediaType',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 764,
            'endLine' => 764,
            'startColumn' => 41,
            'endColumn' => 57,
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
 * @param string $defaultMediaType
 * @return $this
 */',
        'startLine' => 764,
        'endLine' => 768,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDefaultMediaType' => 
      array (
        'name' => 'getDefaultMediaType',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 773,
        'endLine' => 776,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDefaultPaperSize' => 
      array (
        'name' => 'setDefaultPaperSize',
        'parameters' => 
        array (
          'defaultPaperSize' => 
          array (
            'name' => 'defaultPaperSize',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 782,
            'endLine' => 782,
            'startColumn' => 41,
            'endColumn' => 57,
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
 * @param string|float[] $defaultPaperSize
 * @return $this
 */',
        'startLine' => 782,
        'endLine' => 786,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDefaultPaperOrientation' => 
      array (
        'name' => 'setDefaultPaperOrientation',
        'parameters' => 
        array (
          'defaultPaperOrientation' => 
          array (
            'name' => 'defaultPaperOrientation',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 792,
            'endLine' => 792,
            'startColumn' => 48,
            'endColumn' => 78,
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
 * @param string $defaultPaperOrientation
 * @return $this
 */',
        'startLine' => 792,
        'endLine' => 796,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDefaultPaperSize' => 
      array (
        'name' => 'getDefaultPaperSize',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string|float[]
 */',
        'startLine' => 801,
        'endLine' => 804,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDefaultPaperOrientation' => 
      array (
        'name' => 'getDefaultPaperOrientation',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 809,
        'endLine' => 812,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setDpi' => 
      array (
        'name' => 'setDpi',
        'parameters' => 
        array (
          'dpi' => 
          array (
            'name' => 'dpi',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 818,
            'endLine' => 818,
            'startColumn' => 28,
            'endColumn' => 31,
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
 * @param int $dpi
 * @return $this
 */',
        'startLine' => 818,
        'endLine' => 822,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getDpi' => 
      array (
        'name' => 'getDpi',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return int
 */',
        'startLine' => 827,
        'endLine' => 830,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setFontCache' => 
      array (
        'name' => 'setFontCache',
        'parameters' => 
        array (
          'fontCache' => 
          array (
            'name' => 'fontCache',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 836,
            'endLine' => 836,
            'startColumn' => 34,
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
 * @param string $fontCache
 * @return $this
 */',
        'startLine' => 836,
        'endLine' => 842,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getFontCache' => 
      array (
        'name' => 'getFontCache',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 847,
        'endLine' => 850,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setFontDir' => 
      array (
        'name' => 'setFontDir',
        'parameters' => 
        array (
          'fontDir' => 
          array (
            'name' => 'fontDir',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 856,
            'endLine' => 856,
            'startColumn' => 32,
            'endColumn' => 39,
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
 * @param string $fontDir
 * @return $this
 */',
        'startLine' => 856,
        'endLine' => 862,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getFontDir' => 
      array (
        'name' => 'getFontDir',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 867,
        'endLine' => 870,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setFontHeightRatio' => 
      array (
        'name' => 'setFontHeightRatio',
        'parameters' => 
        array (
          'fontHeightRatio' => 
          array (
            'name' => 'fontHeightRatio',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 876,
            'endLine' => 876,
            'startColumn' => 40,
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
 * @param float $fontHeightRatio
 * @return $this
 */',
        'startLine' => 876,
        'endLine' => 880,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getFontHeightRatio' => 
      array (
        'name' => 'getFontHeightRatio',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return float
 */',
        'startLine' => 885,
        'endLine' => 888,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setIsFontSubsettingEnabled' => 
      array (
        'name' => 'setIsFontSubsettingEnabled',
        'parameters' => 
        array (
          'isFontSubsettingEnabled' => 
          array (
            'name' => 'isFontSubsettingEnabled',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 894,
            'endLine' => 894,
            'startColumn' => 48,
            'endColumn' => 71,
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
 * @param boolean $isFontSubsettingEnabled
 * @return $this
 */',
        'startLine' => 894,
        'endLine' => 898,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getIsFontSubsettingEnabled' => 
      array (
        'name' => 'getIsFontSubsettingEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 903,
        'endLine' => 906,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'isFontSubsettingEnabled' => 
      array (
        'name' => 'isFontSubsettingEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 911,
        'endLine' => 914,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setIsHtml5ParserEnabled' => 
      array (
        'name' => 'setIsHtml5ParserEnabled',
        'parameters' => 
        array (
          'isHtml5ParserEnabled' => 
          array (
            'name' => 'isHtml5ParserEnabled',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 921,
            'endLine' => 921,
            'startColumn' => 45,
            'endColumn' => 65,
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
 * @deprecated
 * @param boolean $isHtml5ParserEnabled
 * @return $this
 */',
        'startLine' => 921,
        'endLine' => 925,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getIsHtml5ParserEnabled' => 
      array (
        'name' => 'getIsHtml5ParserEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated
 * @return boolean
 */',
        'startLine' => 931,
        'endLine' => 934,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'isHtml5ParserEnabled' => 
      array (
        'name' => 'isHtml5ParserEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated
 * @return boolean
 */',
        'startLine' => 940,
        'endLine' => 943,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setIsJavascriptEnabled' => 
      array (
        'name' => 'setIsJavascriptEnabled',
        'parameters' => 
        array (
          'isJavascriptEnabled' => 
          array (
            'name' => 'isJavascriptEnabled',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 949,
            'endLine' => 949,
            'startColumn' => 44,
            'endColumn' => 63,
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
 * @param boolean $isJavascriptEnabled
 * @return $this
 */',
        'startLine' => 949,
        'endLine' => 953,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getIsJavascriptEnabled' => 
      array (
        'name' => 'getIsJavascriptEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 958,
        'endLine' => 961,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'isJavascriptEnabled' => 
      array (
        'name' => 'isJavascriptEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 966,
        'endLine' => 969,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setIsPhpEnabled' => 
      array (
        'name' => 'setIsPhpEnabled',
        'parameters' => 
        array (
          'isPhpEnabled' => 
          array (
            'name' => 'isPhpEnabled',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 975,
            'endLine' => 975,
            'startColumn' => 37,
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
 * @param boolean $isPhpEnabled
 * @return $this
 */',
        'startLine' => 975,
        'endLine' => 979,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getIsPhpEnabled' => 
      array (
        'name' => 'getIsPhpEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 984,
        'endLine' => 987,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'isPhpEnabled' => 
      array (
        'name' => 'isPhpEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 992,
        'endLine' => 995,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setIsRemoteEnabled' => 
      array (
        'name' => 'setIsRemoteEnabled',
        'parameters' => 
        array (
          'isRemoteEnabled' => 
          array (
            'name' => 'isRemoteEnabled',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1001,
            'endLine' => 1001,
            'startColumn' => 40,
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
 * @param boolean $isRemoteEnabled
 * @return $this
 */',
        'startLine' => 1001,
        'endLine' => 1005,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getIsRemoteEnabled' => 
      array (
        'name' => 'getIsRemoteEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 1010,
        'endLine' => 1013,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'isRemoteEnabled' => 
      array (
        'name' => 'isRemoteEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 1018,
        'endLine' => 1021,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setAllowedRemoteHosts' => 
      array (
        'name' => 'setAllowedRemoteHosts',
        'parameters' => 
        array (
          'allowedRemoteHosts' => 
          array (
            'name' => 'allowedRemoteHosts',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1027,
            'endLine' => 1027,
            'startColumn' => 43,
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
 * @param array|null $allowedRemoteHosts
 * @return $this
 */',
        'startLine' => 1027,
        'endLine' => 1040,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getAllowedRemoteHosts' => 
      array (
        'name' => 'getAllowedRemoteHosts',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return array|null
 */',
        'startLine' => 1045,
        'endLine' => 1048,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setIsPdfAEnabled' => 
      array (
        'name' => 'setIsPdfAEnabled',
        'parameters' => 
        array (
          'isPdfAEnabled' => 
          array (
            'name' => 'isPdfAEnabled',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1054,
            'endLine' => 1054,
            'startColumn' => 38,
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
 * @param boolean $isRemoteEnabled
 * @return $this
 */',
        'startLine' => 1054,
        'endLine' => 1058,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getIsPdfAEnabled' => 
      array (
        'name' => 'getIsPdfAEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 1063,
        'endLine' => 1066,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'isPdfAEnabled' => 
      array (
        'name' => 'isPdfAEnabled',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return boolean
 */',
        'startLine' => 1071,
        'endLine' => 1074,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setLogOutputFile' => 
      array (
        'name' => 'setLogOutputFile',
        'parameters' => 
        array (
          'logOutputFile' => 
          array (
            'name' => 'logOutputFile',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1080,
            'endLine' => 1080,
            'startColumn' => 38,
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
 * @param string $logOutputFile
 * @return $this
 */',
        'startLine' => 1080,
        'endLine' => 1086,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getLogOutputFile' => 
      array (
        'name' => 'getLogOutputFile',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 1091,
        'endLine' => 1094,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setTempDir' => 
      array (
        'name' => 'setTempDir',
        'parameters' => 
        array (
          'tempDir' => 
          array (
            'name' => 'tempDir',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1100,
            'endLine' => 1100,
            'startColumn' => 32,
            'endColumn' => 39,
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
 * @param string $tempDir
 * @return $this
 */',
        'startLine' => 1100,
        'endLine' => 1106,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getTempDir' => 
      array (
        'name' => 'getTempDir',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 1111,
        'endLine' => 1114,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setRootDir' => 
      array (
        'name' => 'setRootDir',
        'parameters' => 
        array (
          'rootDir' => 
          array (
            'name' => 'rootDir',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1120,
            'endLine' => 1120,
            'startColumn' => 32,
            'endColumn' => 39,
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
 * @param string $rootDir
 * @return $this
 */',
        'startLine' => 1120,
        'endLine' => 1126,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getRootDir' => 
      array (
        'name' => 'getRootDir',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string
 */',
        'startLine' => 1131,
        'endLine' => 1134,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'setHttpContext' => 
      array (
        'name' => 'setHttpContext',
        'parameters' => 
        array (
          'httpContext' => 
          array (
            'name' => 'httpContext',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1142,
            'endLine' => 1142,
            'startColumn' => 36,
            'endColumn' => 47,
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
 * Sets the HTTP context
 *
 * @param resource|array $httpContext
 * @return $this
 */',
        'startLine' => 1142,
        'endLine' => 1146,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'getHttpContext' => 
      array (
        'name' => 'getHttpContext',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns the HTTP context
 *
 * @return resource
 */',
        'startLine' => 1153,
        'endLine' => 1156,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'validateArtifactPath' => 
      array (
        'name' => 'validateArtifactPath',
        'parameters' => 
        array (
          'path' => 
          array (
            'name' => 'path',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1159,
            'endLine' => 1159,
            'startColumn' => 42,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'option' => 
          array (
            'name' => 'option',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1159,
            'endLine' => 1159,
            'startColumn' => 57,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1159,
        'endLine' => 1169,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'validateLocalUri' => 
      array (
        'name' => 'validateLocalUri',
        'parameters' => 
        array (
          'uri' => 
          array (
            'name' => 'uri',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1171,
            'endLine' => 1171,
            'startColumn' => 38,
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
        'docComment' => NULL,
        'startLine' => 1171,
        'endLine' => 1198,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'validatePharUri' => 
      array (
        'name' => 'validatePharUri',
        'parameters' => 
        array (
          'uri' => 
          array (
            'name' => 'uri',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1200,
            'endLine' => 1200,
            'startColumn' => 37,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 1200,
        'endLine' => 1208,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
        'aliasName' => NULL,
      ),
      'validateRemoteUri' => 
      array (
        'name' => 'validateRemoteUri',
        'parameters' => 
        array (
          'uri' => 
          array (
            'name' => 'uri',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 1210,
            'endLine' => 1210,
            'startColumn' => 39,
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
        'docComment' => NULL,
        'startLine' => 1210,
        'endLine' => 1230,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Dompdf',
        'declaringClassName' => 'Dompdf\\Options',
        'implementingClassName' => 'Dompdf\\Options',
        'currentClassName' => 'Dompdf\\Options',
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