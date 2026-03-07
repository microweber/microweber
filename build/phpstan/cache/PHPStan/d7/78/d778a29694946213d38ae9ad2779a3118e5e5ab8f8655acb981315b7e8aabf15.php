<?php declare(strict_types = 1);

// odsl-/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Template/LayoutsManager.php-PHPStan\BetterReflection\Reflection\ReflectionClass-MicroweberPackages\Template\LayoutsManager
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.18-26e2d42660e4133c76b880baec583587cbd203df26d33012645ec257851b3459',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'MicroweberPackages\\Template\\LayoutsManager',
        'filename' => '/home/headless/Documents/GitHub/microweber/src/MicroweberPackages/Template/LayoutsManager.php',
      ),
    ),
    'namespace' => 'MicroweberPackages\\Template',
    'name' => 'MicroweberPackages\\Template\\LayoutsManager',
    'shortName' => 'LayoutsManager',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 19,
    'endLine' => 767,
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
      'app' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'name' => 'app',
        'modifiers' => 1,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 21,
        'endLine' => 21,
        'startColumn' => 5,
        'endColumn' => 16,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'external_layouts' => 
      array (
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'name' => 'external_layouts',
        'modifiers' => 4,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'array()',
          'attributes' => 
          array (
            'startLine' => 22,
            'endLine' => 22,
            'startTokenPos' => 36,
            'startFilePos' => 411,
            'endTokenPos' => 38,
            'endFilePos' => 417,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 22,
        'endLine' => 22,
        'startColumn' => 5,
        'endColumn' => 40,
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
          'app' => 
          array (
            'name' => 'app',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 24,
                'endLine' => 24,
                'startTokenPos' => 51,
                'startFilePos' => 460,
                'endTokenPos' => 51,
                'endFilePos' => 463,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 24,
            'endLine' => 24,
            'startColumn' => 33,
            'endColumn' => 43,
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
        'startLine' => 24,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Template',
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'currentClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'aliasName' => NULL,
      ),
      'get_all' => 
      array (
        'name' => 'get_all',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 50,
                'endLine' => 50,
                'startTokenPos' => 125,
                'startFilePos' => 1319,
                'endTokenPos' => 125,
                'endFilePos' => 1323,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 50,
            'endLine' => 50,
            'startColumn' => 29,
            'endColumn' => 44,
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
 * Lists the layout files from a given directory.
 *
 * You can use this function to get layouts from various folders in your web server.
 * It returns array of layouts with desctption, icon, etc
 *
 * This function caches the result in the \'templates\' cache group
 *
 * @param bool|array|string $options
 *
 * @return array|mixed
 *
 * @params $options[\'path\'] if set i will look for layouts in this folder
 * @params $options[\'get_dynamic_layouts\'] if set this function will scan for templates for the \'layout\' module in all templates folders
 */',
        'startLine' => 50,
        'endLine' => 61,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Template',
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'currentClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'aliasName' => NULL,
      ),
      'get_layout_details' => 
      array (
        'name' => 'get_layout_details',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 63,
            'endLine' => 63,
            'startColumn' => 40,
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
        'docComment' => NULL,
        'startLine' => 63,
        'endLine' => 96,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Template',
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'currentClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'aliasName' => NULL,
      ),
      'get_elements_from_current_site_template' => 
      array (
        'name' => 'get_elements_from_current_site_template',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 98,
        'endLine' => 113,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Template',
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'currentClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'aliasName' => NULL,
      ),
      'scan' => 
      array (
        'name' => 'scan',
        'parameters' => 
        array (
          'options' => 
          array (
            'name' => 'options',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 117,
                'endLine' => 117,
                'startTokenPos' => 633,
                'startFilePos' => 3572,
                'endTokenPos' => 633,
                'endFilePos' => 3576,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 117,
            'endLine' => 117,
            'startColumn' => 26,
            'endColumn' => 41,
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
        'startLine' => 117,
        'endLine' => 551,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => true,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Template',
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'currentClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'aliasName' => NULL,
      ),
      'template_remove_custom_css' => 
      array (
        'name' => 'template_remove_custom_css',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 555,
            'endLine' => 555,
            'startColumn' => 48,
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
        'docComment' => NULL,
        'startLine' => 555,
        'endLine' => 606,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Template',
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'currentClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'aliasName' => NULL,
      ),
      'template_check_for_custom_css' => 
      array (
        'name' => 'template_check_for_custom_css',
        'parameters' => 
        array (
          'template_name' => 
          array (
            'name' => 'template_name',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 608,
            'endLine' => 608,
            'startColumn' => 51,
            'endColumn' => 64,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'check_for_backup' => 
          array (
            'name' => 'check_for_backup',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 608,
                'endLine' => 608,
                'startTokenPos' => 4649,
                'startFilePos' => 24946,
                'endTokenPos' => 4649,
                'endFilePos' => 24950,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 608,
            'endLine' => 608,
            'startColumn' => 67,
            'endColumn' => 91,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 608,
        'endLine' => 630,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Template',
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'currentClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'aliasName' => NULL,
      ),
      'template_save_css' => 
      array (
        'name' => 'template_save_css',
        'parameters' => 
        array (
          'params' => 
          array (
            'name' => 'params',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 632,
            'endLine' => 632,
            'startColumn' => 39,
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
        'docComment' => NULL,
        'startLine' => 632,
        'endLine' => 757,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Template',
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'currentClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'aliasName' => NULL,
      ),
      'add_external' => 
      array (
        'name' => 'add_external',
        'parameters' => 
        array (
          'arr' => 
          array (
            'name' => 'arr',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 759,
            'endLine' => 759,
            'startColumn' => 34,
            'endColumn' => 37,
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
        'startLine' => 759,
        'endLine' => 764,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'MicroweberPackages\\Template',
        'declaringClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'implementingClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
        'currentClassName' => 'MicroweberPackages\\Template\\LayoutsManager',
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