<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Utils/ThirdPartyLibs/SimpleTextImage.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Utils\ThirdPartyLibs\SimpleTextImage
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-31adec0ead57c1f7ce0eee949b2c064bbf3d500f74e346fa34b091ff5c46d810',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Utils/ThirdPartyLibs/SimpleTextImage.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
    'name' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
    'shortName' => 'SimpleTextImage',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 *
 * @from https://gist.github.com/mistic100/9433241
 *
 *
 * Generate a very simple image containing some text
 *
 * Basic usage:
 *    (new SimpleTextImage(\'Hello world!\'))->render();
 *
 * All functionalities:
 *    (new SimpleTextImage())
 *      ->setText(\'Hello world!\')
 *      ->setBackground(255,0,0)
 *      ->setForeground(0,255,255)
 *      ->setFontSize(2)
 *      ->setPadding(10)
 *      ->setFile(\'hello.jpg\')
 *      ->render(\'jpg\');
 *
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 27,
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
      'text' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'name' => 'text',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'\'',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 21,
            'startFilePos' => 564,
            'endTokenPos' => 21,
            'endFilePos' => 565,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 19,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'font_size' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'name' => 'font_size',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '4',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 30,
            'startFilePos' => 589,
            'endTokenPos' => 30,
            'endFilePos' => 589,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'padding' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'name' => 'padding',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => '2',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 39,
            'startFilePos' => 611,
            'endTokenPos' => 39,
            'endFilePos' => 611,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 21,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'bg' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'name' => 'bg',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array(0, 0, 0)',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 33,
            'startTokenPos' => 48,
            'startFilePos' => 628,
            'endTokenPos' => 57,
            'endFilePos' => 641,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fg' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'name' => 'fg',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array(255, 255, 255)',
          'attributes' => 
          array (
            'startLine' => 34,
            'endLine' => 34,
            'startTokenPos' => 66,
            'startFilePos' => 658,
            'endTokenPos' => 75,
            'endFilePos' => 677,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 34,
        'endLine' => 34,
        'startColumn' => 5,
        'endColumn' => 35,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'file' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'name' => 'file',
        'modifiers' => 1,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 35,
            'endLine' => 35,
            'startTokenPos' => 84,
            'startFilePos' => 696,
            'endTokenPos' => 84,
            'endFilePos' => 699,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 35,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 21,
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
          'text' => 
          array (
            'name' => 'text',
            'default' => 
            array (
              'code' => '\'\'',
              'attributes' => 
              array (
                'startLine' => 37,
                'endLine' => 37,
                'startTokenPos' => 95,
                'startFilePos' => 736,
                'endTokenPos' => 95,
                'endFilePos' => 737,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 37,
            'endLine' => 37,
            'startColumn' => 26,
            'endColumn' => 35,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 37,
        'endLine' => 40,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'aliasName' => NULL,
      ),
      'setText' => 
      array (
        'name' => 'setText',
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
            'startLine' => 42,
            'endLine' => 42,
            'startColumn' => 22,
            'endColumn' => 26,
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
        'startLine' => 42,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'aliasName' => NULL,
      ),
      'setFontSize' => 
      array (
        'name' => 'setFontSize',
        'parameters' => 
        array (
          'font_size' => 
          array (
            'name' => 'font_size',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 48,
            'endLine' => 48,
            'startColumn' => 26,
            'endColumn' => 35,
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
        'startLine' => 48,
        'endLine' => 53,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'aliasName' => NULL,
      ),
      'setPadding' => 
      array (
        'name' => 'setPadding',
        'parameters' => 
        array (
          'padding' => 
          array (
            'name' => 'padding',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 55,
            'endLine' => 55,
            'startColumn' => 25,
            'endColumn' => 32,
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
        'startLine' => 55,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'aliasName' => NULL,
      ),
      'setBackground' => 
      array (
        'name' => 'setBackground',
        'parameters' => 
        array (
          'r' => 
          array (
            'name' => 'r',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 28,
            'endColumn' => 29,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'g' => 
          array (
            'name' => 'g',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 32,
            'endColumn' => 33,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'b' => 
          array (
            'name' => 'b',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 61,
            'endLine' => 61,
            'startColumn' => 36,
            'endColumn' => 37,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 61,
        'endLine' => 65,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'aliasName' => NULL,
      ),
      'setForeground' => 
      array (
        'name' => 'setForeground',
        'parameters' => 
        array (
          'r' => 
          array (
            'name' => 'r',
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
            'startColumn' => 28,
            'endColumn' => 29,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'g' => 
          array (
            'name' => 'g',
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
            'startColumn' => 32,
            'endColumn' => 33,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'b' => 
          array (
            'name' => 'b',
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
            'startColumn' => 36,
            'endColumn' => 37,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 67,
        'endLine' => 71,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'aliasName' => NULL,
      ),
      'setFile' => 
      array (
        'name' => 'setFile',
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
            'startLine' => 73,
            'endLine' => 73,
            'startColumn' => 22,
            'endColumn' => 26,
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
        'startLine' => 73,
        'endLine' => 77,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'aliasName' => NULL,
      ),
      'render' => 
      array (
        'name' => 'render',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
            'default' => 
            array (
              'code' => '\'png\'',
              'attributes' => 
              array (
                'startLine' => 79,
                'endLine' => 79,
                'startTokenPos' => 299,
                'startFilePos' => 1436,
                'endTokenPos' => 299,
                'endFilePos' => 1440,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 79,
            'endLine' => 79,
            'startColumn' => 21,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 79,
        'endLine' => 136,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Utils\\ThirdPartyLibs',
        'declaringClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'implementingClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
        'currentClassName' => 'MicroweberPackages\\Utils\\ThirdPartyLibs\\SimpleTextImage',
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